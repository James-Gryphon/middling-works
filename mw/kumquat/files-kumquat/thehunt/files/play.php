<a id="most_specific" class="skip">Content</a>
<?php
$errors = array();
if(isset($_GET['game_code'])){ $game_code = $_GET['game_code'];} else {$game_code = "";}
if(isset($_GET['gm']) && is_numeric($_GET['gm'])){ $game_id = $_GET['gm'];}
elseif(isset($_GET['game_code'])){$game_id = 0;}
else { exit(header("Location: index.php?s=thehunt&a=lobby")); }
// include something here for invalid IDs

$query = $pdo->prepare("SELECT *, UNIX_TIMESTAMP(`updated`) as `timestamp` FROM thnt_games WHERE game_id = ? OR access_code = ?");
$query->execute([$game_id, $game_code]);
$game_data = $query->fetch(PDO::FETCH_ASSOC);
// include something here for empty/nonexistent games
if(empty($game_data))
{
	exit(header("Location: index.php?s=thehunt&a=lobby"));
}
elseif(empty($game_id))
{ // The access code is, necessarily, good
	$game_id = $game_data['game_id'];
}

$query = $pdo->prepare(
	"SELECT thnt_players.*, home_accounts.username FROM thnt_players LEFT JOIN home_accounts ON (thnt_players.user_id = home_accounts.id) WHERE game_id = :game_id ORDER BY move_order, local_id;
	SELECT * FROM thnt_boards WHERE board_id = :board_id;
	SELECT * FROM thnt_continents WHERE board_id = :board_id ORDER BY `continent_id`;
	SELECT * FROM thnt_territories WHERE board_id = :board_id;
	SELECT * FROM thnt_territory_links WHERE board_id = :board_id;
	SELECT * from thnt_card_types;
	SELECT * from thnt_messages WHERE game_id = :game_id ORDER BY `move_id` DESC;"
);
$query->execute(
	[
	":game_id" => $game_data['game_id'],
	":board_id" => $game_data['board_id']
	]
);
// Thanks to edmondscommerce: https://stackoverflow.com/questions/21485868/php-pdo-multiple-select-query-consistently-dropping-last-rowset
// do this first so we can kick you to the lobby if you don't belong here
$player_data = $query->fetchAll(PDO::FETCH_ASSOC);
$act_player = act_id_getter();
if($game_data['private'] == 1 && ($act_player === 0 && $game_code != $game_data['access_code']))
{ 
	exit(header("Location: index.php?s=thehunt&a=lobby"));
}
$query->nextRowset();
$board_data = $query->fetch(PDO::FETCH_ASSOC);
$query->nextRowset();
$continent_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$territory_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$territory_link_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$card_types = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$game_messages = $query->fetchAll(PDO::FETCH_ASSOC);
$board_image_array = json_decode($board_data['board_image'], true);

$Continents = array();
$Territories = array();
foreach($territory_data as $title => $territory_row)
{
	$Territories[$territory_row['territory_id']] = $territory_row;
	$Continents[$territory_row['continent_id']][] = $territory_row['territory_id'];
}

// Player inventories
foreach($player_data as $key => $value)
{
$player_data[$key]['inventory'] = json_decode($player_data[$key]['inventory'], true);
}

$agents_array = json_decode($game_data['agents'], true);
$card_decks = json_decode($game_data['card_decks'], true);
if(!empty($game_data['triggers'])){ $triggers = json_decode($game_data['triggers'], true);} else {$triggers = array();}

// Join game
$player_game_count = 0;
$nonewgames = false;
if($game_data['game_status'] === "open" && $user_id > 0 && $act_player === 0)
{
	// Not really a great solution, but meh
	$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id FROM thnt_players WHERE user_id = $user_id) AND game_status != 'completed'");
	$player_game_count = $query->fetchAll(PDO::FETCH_ASSOC);
	$player_game_count = count($player_game_count);
	if($player_game_count > 6){$nonewgames = true;}
	if(isset($_POST['order_button']) && $_POST['order_button'] == "join" && isset($_POST['join_box']) && $nonewgames === false)
	{
		join_handler();
		// Add something here to update roster after you join
		refreshOrder(false);
	}
}
if(isset($_POST['order_button']) && $_POST['order_button'] == "leave" && $user_id > 0 && isset($_POST['leave_box']))
{
	$handle = leave_handler(); //rewrite leave_handler so it works here and in lobby
	if($handle === "host"){	exit(header("Location: index.php?s=thehunt&a=lobby"));}
	refreshOrder(false);
}

// Start game?
if(isset($_POST['order_button']) && $_POST['order_button'] == "start" && isset($_SESSION['id']) && isset($_POST['start_box']))
{
    // Leave out stuff to check if game exists, because that should be done earlier in play.php
    if($_SESSION['id'] == $player_data[0]['user_id'])
    {
        if($game_data['game_status'] != "open")
        {
            $errors['td']['game_not_open'] = true;
        }
        else
        {
            $territory_count = count($Territories);
            $player = random_int(1, $territory_count);
            while(!isset($mastermind) || $mastermind == $player)
            {
            	$mastermind = random_int(1, $territory_count);
            }
            $agents = array();
			$inventory = array("agents" => array(), "cards" => array());
            foreach($Continents as $key => $value)
            {
				$max_terr = array_key_last($value);
                while(!isset($agent) || $agent == $mastermind || $agent == $player)
                {
					$rand_territory = random_int(0, $max_terr);
					$agent = $value[$rand_territory];				
                }
				$agents[$key]['cards'] = $board_data['agent_card_count'];
				$agents[$key]['location'] = $agent;
				// Prepare the basic inventory array.
				$inventory['agents'][$key] = 0;
				unset($agent);
            }
			$move_order = array();
			foreach($player_data as $sing_player_data)
			{
				$move_order[] = $sing_player_data['local_id'];
			}
			$move_order = rand_shuffle($move_order);
			$agents["mastermind"] = $mastermind;
			$agents["player"] = $player;
			$json = json_encode($agents);
			$json_inventory = json_encode($inventory);

			$board_deck_array = json_decode($board_data['board_deck']);
			$card_decks = array
			(
				"main" => array(),
				"discards" => array(),
			);
			foreach($board_deck_array as $key => $value)
			{
				$i = $value;
				while ($i > 0)
				{
				$card_decks['main'][] = $key;
				$i -= 1;
				}
			}
			$card_decks['main'] = rand_shuffle($card_decks['main']);
			$card_decks = json_encode($card_decks, true);
			$triggers = array();
			$new_triggers = json_encode($triggers, true);
            // Now we should have all the locations set, so get ready to start the game.
            $sth = $pdo->prepare("UPDATE `thnt_games` SET game_status = :game_status, current_player = :current_player, round = :round, agents = :agents, card_decks = :card_decks, triggers = :triggers WHERE `game_id` = :game_id");
            $sth->execute([
                ':game_status' => "active",
				':current_player' => "1",
				':round' => "1",
				':agents' => $json,
                ':game_id' => $game_id,
				':card_decks' => $card_decks,
				':triggers' => $new_triggers
            ]);

			$sth = $pdo->prepare("UPDATE `thnt_players` SET move_order = :move_order, location = :location, status = :status, inventory = :inventory WHERE `game_id` = :game_id AND `local_id` = :local_id");
			foreach($move_order as $key => $value)
			{
				$move_order_value = $key + 1;
				$sth->execute([
					':move_order' => $move_order_value,
					':location' => $player,
					':status' => "active",
					':inventory' => $json_inventory,
					':game_id' => $game_id,
					':local_id' => $value
				]);
			}
			/* For a move in the move log, things that need to be accounted for:
			1. game_id
			2. local_id
			3. move_id
			4. action
			5. m_param
			6. s_param
			7. op_cost
			8. message
			9. sec_message
			10. secret
			11. acted
			Review "structure of a move log entry" for details
			*/

			// This is a counterintuitive 'hack'.
			$message = "<span class='z3'><b>{$player_data[$move_order[0]-1]['username']}</b>'s turn</span><hr>";
			$message .= "Welcome to The Hunt! All players start at: ";
			$message .= $Territories[$player]['territory_name'];
			$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
			$query->execute([
			":game_id" => $game_id,
			":message" => $message,
			":secret" => 0,
			":recipient" => 0,
			":move_id" => 0,
			]);		

			// The game should now be ready
			$act_player = act_id_getter();
			refreshOrder(false); // to prevent the 'no links' bug
        }
    }
    else 
    {
        $errors['td']['not_game_host'] = true;
    }
    // Make sure 
}

// End start game

// Begin 'active game' section - this stuff only comes into play when the game has started
if($game_data['game_status'] === "active" && $act_player !== 0)
{
	/* Build territory link table - for use in pathfinder */
	foreach($territory_link_data as $territory_link)
	{
		$Territories[$territory_link['territory_one_id']]['links'][$territory_link['territory_two_id']]['dest'] = $territory_link['territory_two_id'];
		$Territories[$territory_link['territory_one_id']]['links'][$territory_link['territory_two_id']]['one_way'] = $territory_link['one_way'];
		$Territories[$territory_link['territory_one_id']]['links'][$territory_link['territory_two_id']]['type'] = $territory_link['type'];
		if($territory_link['one_way'] == 0)
		{
			$Territories[$territory_link['territory_two_id']]['links'][$territory_link['territory_one_id']]['dest'] = $territory_link['territory_one_id'];
			$Territories[$territory_link['territory_two_id']]['links'][$territory_link['territory_one_id']]['one_way'] = $territory_link['one_way'];
			$Territories[$territory_link['territory_two_id']]['links'][$territory_link['territory_one_id']]['type'] = $territory_link['type'];
		}
	}

	/*
	Trigger system:
	At the beginning of the game, add an empty "trigger" array to the column.

	Certain cards will activate triggers. In the regular game, that includes "Close Borders", "Monolingual", and such like.  
	These change the moves that players are allowed to make.
	There are several types of restrictions:
	1. By link type. This is the simplest type and corresponds directly to the restrictions in the original game. 
	Whenever a move is normally possible, the link type is checked; if it matches, then the move is forbidden. 
	"Close Roads" blocks road-type links (cars and motorcycles), "Close Airports" prevents jets.

	2. By region. If the region is blocked, then nobody can move into this region from another region, and nobody can move out of it to another region.

	3. By link type *AND* region. Combine these two.

	4. By link. This specifically looks for a certain link and flips it - if it does exist, it is made unusable.

	The norm for a trigger is that it ends after a full cycle (when the player who played it gets to move again), not counting "Take Another Turn".
	However, we might imagine cases where it is desirable to have triggers that end at other times.

	The trigger array is checked each turn, and at the end of turns, to determine when a trigger is expiring. 
	The effects of a trigger should be noted at the beginning and end of each trigger, to avoid confusion.

	The trigger array is updated when cards that cause triggers are played, and when one expires.

	Layout of the trigger array:

	trigger = array (this is what gets json_encoded)
	(
		[0] => array
			(
				player => "1",
				link_styles => array("car", "motorcycle"), // "", or "no link style", must be specifically mentioned here
				regions => array("1"), // prevents travel to/from this region to/from another region
				links => array
				(
					[0] => array(1, 2), // 1-2 is prevented, and if it is a two-way link, so will 2-1 be so
					[1] => array(3, 4)
				)
			)
	)
	*/
	$bad_links = BadLinksBuilder($triggers);
	/*
	foreach($Territories[$player_data[$act_player-1]['location']]['links'] as $current_link)
	{
		foreach($triggers as $trigger)
		{
			if($trigger['link_styles'] === $current_link['type'])
			{ // If the link style matches, then bad link
				$bad_links[$current_link['dest']] = $current_link['dest'];
			}
			// $Territories[$player_data[$act_player-1]['location']]['continent_id'] is the player's current location on the continent
			// current_link...
			// $Territories[$current_link['dest']]['continent_id'] should be the continent id for the player's dest
			elseif(in_array($Territories[$current_link['dest']]['continent_id'], $trigger['regions']) && $Territories[$player_data[$act_player-1]['location']]['continent_id'] !== $Territories[$current_link['dest']]['continent_id'])
			{ // If the continent you're going to is on a blacklisted region
				$bad_links[$current_link['dest']] = $current_link['dest'];
			}
			elseif(in_array($Territories[$player_data[$act_player-1]['location']]['continent_id'], $trigger['regions']) && $Territories[$current_link['dest']]['continent_id'] !== $Territories[$player_data[$act_player-1]['location']]['continent_id'])
			{ // If the continent you're coming *from* is a blacklisted region
				$bad_links[$current_link['dest']] = $current_link['dest'];
			}
			else 
			{ // If there is a link-lock for this pairing
				foreach($trigger['links'] as $trigger_link)
				{
					if(($trigger_link[0] == $current_link['dest'] && $trigger_link[1] == $Territories[$player_data[$act_player-1]['location']]) || ($trigger_link[1] == $current_link['dest'] && $trigger_link[0] == $Territories[$player_data[$act_player-1]['location']])){ $bad_links[] = $current_link['dest']; break;}
				}
			}
		}
	} 
	*/

	$Board = array();

	foreach($territory_link_data as $ind_territory)
	{
		$Board[$ind_territory['territory_one_id']][$ind_territory['territory_two_id']] = $ind_territory['territory_two_id'];
		if($ind_territory['one_way'] === 0)
		{
			$Board[$ind_territory['territory_two_id']][$ind_territory['territory_one_id']] = $ind_territory['territory_one_id'];
		}
	}

	if(isset($_POST['loc_chooser']) && is_numeric($_POST['loc_chooser']) && $act_player == $game_data['current_player'] && $game_data['game_status'] == "active")
	{
		// Begin processing a move
		if(isset($Territories[$player_data[$act_player-1]['location']]['links'][$_POST['loc_chooser']]) && !in_array($_POST['loc_chooser'], $bad_links))
		{
		$move_type = 1;
		}
		else 
		{ // this needs redesigned with an error notice that works for this page
			// echo "Invalid link detected... {$territory_data[$player_data[$act_player-1]['location']-1]['territory_name']} > {$territory_data[$_POST['loc_chooser']-1]['territory_name']} ";
		}
	}

	elseif(isset($_POST['card_chooser']) && is_numeric($_POST['card_chooser']) && $act_player == $game_data['current_player'] && $game_data['game_status'] == "active")
	{
		$_POST['card_chooser'] = intval($_POST['card_chooser']);
		if(isset($player_data[$act_player-1]['inventory']['cards'][$_POST['card_chooser']]))
		{
			// Begin processing a card
			$move_type = 2;
		}
		else
		{
			// echo "Invalid card detected.";
		}
	}

	elseif(isset($_POST['order_button']) && $_POST['order_button'] == "capture" && isset($_POST['capture_box']) && $act_player == $game_data['current_player'] && $game_data['game_status'] == "active")
	{
		// Attempt to capture the Mole
		$move_type = 5;
	}

	elseif(isset($_POST['order_button']) && $_POST['order_button'] == "pass" && isset($_POST['pass_box']) && $act_player == $game_data['current_player'] && $game_data['game_status'] == "active")
	{
		// Pass the turn
		$move_type = 3;
	}

	elseif(isset($_POST['order_button']) && $_POST['order_button'] == "leave" && isset($_POST['leave_box']) && $act_player == $game_data['current_player'] && $game_data['game_status'] == "active")
	{
		// Leave/resign the game - now requires the player to be active, although this wasn't always the plan
		$move_type = 4;
	}

	if(isset($move_type) && $move_type > 0)
	{
		$query = $pdo->prepare("SELECT * FROM thnt_messages WHERE game_id = ? ORDER BY move_id DESC LIMIT 1");
		$query->execute([$game_id]);
		$last_move_in_log = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($last_move_in_log)){ $last_move_in_log = $last_move_in_log['move_id']; }
		else { $last_move_in_log = 0;}
		$end_game = 0;
		$next_move_in_log = $last_move_in_log + 1;

		// Most of this is going to need to be put into functions, so that it can be reused when the player passes or plays a card

		if($move_type === 1)
		{ // The valid link has already been detected, or else move_type wouldn't be set to this.
		// This section specifically concerns movement.
		$energy_return = energyProcess();
			$sth = $pdo->prepare("UPDATE `thnt_players` SET location = :location WHERE `game_id` = :game_id AND move_order = :move_order");
			$sth->execute([
				':location' => $_POST['loc_chooser'],
				':game_id' => $game_id,
				':move_order' => $game_data['current_player']
				]);
			$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
			$message = "";
			$message .= "<b>{$msg_player_name}</b>";
			$message .= " moves from <b><i>";
			$message .= $Territories[$player_data[$game_data['current_player']-1]['location']]['territory_name'];
			$message .= " </b></i> to <b><i>";
			$message .= $Territories[$_POST['loc_chooser']]['territory_name'];
			$message .= "</b></i>.<br>";
			$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
			$query->execute([
			":game_id" => $game_id,
			":message" => $message,
			":secret" => 0,
			":recipient" => 0,
			":move_id" => $next_move_in_log,
			]);	
			$next_move_in_log += 1;
		}

		elseif($move_type === 2)
		{ // Card playing
			$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
			$message = "";
			$message .= "<b>{$msg_player_name}</b>";
			$message .= " plays \"<i>";
			$message .= $card_types[$_POST['card_chooser']-1]['card_name'];
			$message .= " </i>\".<br>";
			$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
			$query->execute([
			":game_id" => $game_id,
			":message" => $message,
			":secret" => 0,
			":recipient" => 0,
			":move_id" => $next_move_in_log,
			]);	
			$next_move_in_log += 1;
			// Get rid of the card
			$remove_card = $_POST['card_chooser'];
			$player_data[$act_player-1]['inventory']['cards'][$remove_card] -= 1;
			if($player_data[$act_player-1]['inventory']['cards'][$remove_card]	=== 0)
			{ // remove it from the inventory altogether
				unset($player_data[$act_player-1]['inventory']['cards'][$remove_card]);
			}
			// Add the card to the discard deck
			$discard_card = $_POST['card_chooser'];
			$card_decks['discards'][] = $discard_card;
			$new_card_decks = json_encode($card_decks, true);
			$sth = $pdo->prepare("UPDATE `thnt_games` SET card_decks = :card_decks WHERE `game_id` = :game_id");
			$sth->execute([
				':game_id' => $game_id,
				':card_decks' => $new_card_decks
			]);
			// Update player inventory
			UpdateInventory();

			// Now, perform the action.

			if($_POST['card_chooser'] === 1)
			{	// Search for Mastermind
				$var = PathFinder2($player_data[$act_player-1]['location'], $agents_array['mastermind']);
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				$message = "<i><b>{$msg_player_name}:</b> ";
				if($var === 0)
				{
					$message .= "The Mole is at your current location, <u>$msg_territory_name</u>.";
				}
				else 
				{
					$message .= "The Mole is {$var} territor";
					if($var === 1) { $message .= "y";} else {$message .= "ies";}
					$message .= " away from <u>$msg_territory_name</u>."; 
				}
				$message .= "</i><br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 1,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
			}
			elseif($_POST['card_chooser'] === 2)
			{	// Safe Capture
				if($player_data[$act_player-1]['location'] == $agents_array['mastermind'])
				{	// Success!
					capture_handler();
				}
				else
				{ // Failure
					$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
					$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
					// Include the location that was investigated below
					$message = "<b>{$msg_player_name}</b> fully investigated <u>$msg_territory_name</u>, but found no trace of the Mole.<br>";
					$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
					$query->execute([
					":game_id" => $game_id,
					":message" => $message,
					":secret" => 0,
					":recipient" => 0,
					":move_id" => $next_move_in_log,
					]);
					$next_move_in_log += 1;
				}
			}
			elseif($_POST['card_chooser'] === 3)
			{	// Point to Mastermind
				
				$var = PathFinder2($player_data[$act_player-1]['location'], $agents_array['mastermind'], "point");
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				$message = "<i><b>{$msg_player_name}:</b> ";
				if($var === 0)
				{
					$message .= "The Mole is at your current location, <u>$msg_territory_name</u>.";
				}
				else {
				$message .= "At <u>$msg_territory_name</u>, <u>{$Territories[$var]['territory_name']}</u> is closer to the Mole.";}
				$message .= "<br></i>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 1,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
			}
			elseif($_POST['card_chooser'] === 4)
			{	// Bug Another Agent
				$player_count = count($player_data);
				if($player_count > 1)
				{
					$rand_player = $act_player-1;
					while($rand_player === $act_player -1)
					{
						$rand_player = random_int(0, $player_count-1);
						$rand_player_location = $player_data[$rand_player]['location'];
					}
				}
				else 
				{ // In solitaire mode, use the locations of agents in territories
					$agent_count = count($agents_array) - 2; // subtracting the mastermind and player
					$rand_player = random_int(1,$agent_count);
					$rand_player_location = $agents_array[$rand_player]['location'];
				}
				$msg_territory_name = $Territories[$rand_player_location]['territory_name'];
				$var = PathFinder2($rand_player_location, $agents_array['mastermind']);
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				if($player_count > 1)
				{
					$rand_player_name = "{$player_data[$rand_player]['username']}";
				}
				else
				{
					$rand_player_name = "{$continent_data[$rand_player-1]['agent_name']}";
				}
				$message = "<i><b>{$msg_player_name}:</b> ";
				if($var === 0)
				{
					$message .= "The Mole is at the same location as {$rand_player_name}, <u>{$msg_territory_name}</u>.";
				}
				else {
				$message .= "The Mole is {$var} territor";
				if($var === 1){ $message .= "y";} else {$message .= "ies";}
				$message .= " away from {$rand_player_name}";
				if($player_count > 1){ $message .= " in <u>{$msg_territory_name}</u>";}
				}
				$message .= ".</i><br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 1,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
			}
			elseif($_POST['card_chooser'] === 5)
			{	// Close Borders
				// Used in "North America". This closes the borders between the major countries (US, Canada, Mexico).
				/* This is a rather complicated card, since the countries (perhaps injudiciously) often share regions.
				The links that need to be broken are:
				Anchorage - Vancouver √
				Seattle - Vancouver √
				Minneapolis-Winnipeg √
				Buffalo-Toronto (44,52) √
				Concord-Montreal (46,49) √
				Quebec City-Augusta √
				Ciudad-Phoenix √
				Ciudad-Albuquerque √
				San Antonio-Monterrey ()
				New Orleans-Merida ()
				Tampa-Merida ()
				*/
				$triggers[] = array(
					"player" => $game_data['current_player'],
					"trigger_name" => "close_borders",
					"link_styles" => [],
					"regions" => [],
					"links" => [[1,3],[3,4],[12,13],[22,23],[21,23],[47,48],[44,52],[46,49],[62,64],[66,67],[67,68]]
				);
				// Include message here to show the continent that is restricted
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				// Include the location that was investigated below
				$message = "<b>{$msg_player_name}</b> has closed all the national borders.<br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 0,
				":recipient" => 0,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;

				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);
			}
			elseif($_POST['card_chooser'] === 6)
			{	// Local Lockdown
				// Prevents someone from entering or leaving the region the player is in.
				$triggers[] = array(
				"player" => $game_data['current_player'],
				"trigger_name" => "local_lockdown",
				"link_styles" => [],
				"regions" => [	$Territories[$player_data[$act_player-1]['location']]['continent_id']	],
				"links" => []
				);
				// Include message here to show the continent that is restricted
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				// Include the location that was investigated below
				$message = "<b>{$msg_player_name}</b> has locked down travel in and out of <u>";
				$message .= $continent_data[$Territories[$player_data[$act_player-1]['location']]['continent_id']-1]['continent_name'];
				$message .= "</u>.<br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 0,
				":recipient" => 0,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;

				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);	
			}

			elseif($_POST['card_chooser'] === 7)
			{	// Take Another Turn
				$game_data['current_energy'] += 2;
				$sth = $pdo->prepare("UPDATE `thnt_games` SET current_energy = :current_energy WHERE `game_id` = :game_id");
				$sth->execute([
					':current_energy' => $game_data['current_energy'],
					':game_id' => $game_id
				]);
			}
			elseif($_POST['card_chooser'] === 8)
			{	// Global Jump
				// Uses a mechanism to take the player to a location at 'the other side of the world'.
				// One way to do this, and the means chosen, is to pick a territory furthest away from the player.
				// The original mechanism in Spy Trackdown is currently unstudied.
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				$max_dist_territory = Glob_Calculator($player_data[$act_player-1]['location'], count($Territories));
				$message = "<b>{$msg_player_name}</b> ";
				$message .= "has performed a Long Jump from <u>$msg_territory_name</u> to <u>{$Territories[$max_dist_territory]['territory_name']}</u>.<br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 0,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
				$sth = $pdo->prepare("UPDATE `thnt_players` SET location = :location WHERE `game_id` = :game_id AND move_order = :move_order");
				$sth->execute([
					':location' => $max_dist_territory,
					':game_id' => $game_id,
					':move_order' => $game_data['current_player']
				]);
				$move_territory = $max_dist_territory;
			}
			elseif($_POST['card_chooser'] === 9)
			{	// Flight Check
				// Checks the distance from the player to the Mole - 'as the crow flies'
				$var = AngleFinder(
					$Territories[$player_data[$act_player-1]['location']]['x'], 
					$Territories[$player_data[$act_player-1]['location']]['y'], 
					$Territories[$agents_array['mastermind']]['x'],
					$Territories[$agents_array['mastermind']]['y'],
					false
				);
				$squaresize = $board_image_array['circle_width'];
				$mod = $squaresize/7;
				$var = round($var);
				$mod = round($mod, 2);
				if($var == 0)
				{
					$dist = -1;
				}
				elseif($var <= 56 * $mod)
				{
					$dist = 1;
				}
				elseif($var <= 91 * $mod)
				{
					$dist = 2;
				}
				elseif($var <= 116 * $mod)
				{
					$dist = 3;
				}
				elseif($var <= 145 * $mod)
				{
					$dist = 4;
				}
				elseif($var > 145 * $mod)
				{
					$dist = 5;
				}
				$printvar = $var * $mod;
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				$message = "<i><b>{$msg_player_name}:</b> ";
				$message .= "The Mole is ";
				if($dist === -1){ $message .= "at your current location, <u>$msg_territory_name</u>.";}
				else
				{
				if($dist === 1){ $message .= "close to"; }
				if($dist === 2){ $message .= "not quite near"; }
				if($dist === 3){ $message .= "some distance from"; }
				if($dist === 4){ $message .= "a ways away from"; }
				if($dist === 5){ $message .= "far from"; }
				$message .= " <u>$msg_territory_name</u>.";
				}
				$message .= "</i><br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 1,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;

			}
			elseif($_POST['card_chooser'] === 15)
			{	// Compass Direction
				/* This gets the angle for the player to the mole, and then sees which sector it fits into,
				one of the eight compass directions:
					 90
					 |
				     |
			   180---*---0
					 |
					 |
					270
				*/
				if($player_data[$act_player-1]['location'] === $agents_array['mastermind'])
				{
					$mole_found = 1;
				}
				else 
				{
					$var = AngleFinder(
						$Territories[$player_data[$act_player-1]['location']]['x'], 
						$Territories[$player_data[$act_player-1]['location']]['y'], 
						$Territories[$agents_array['mastermind']]['x'],
						$Territories[$agents_array['mastermind']]['y']
					);
					/*
					We have to figure out what range it fits into, as quickly as possible.
					Since there are eight possibilities, the minimum number of checks to determine this would seem to be three.
					You have to figure out a way to eliminate half of the possibilities each check.
					This seems problematic.
					___

					Alternatively, we could just do a table and ifelse all the possibilities...
					*/
					if($var > 337.5 || $var < 22.5){ $string = "east";}
					if($var >= 292.5 && $var <= 337.5){ $string = "southeast";}
					if($var > 247.5 && $var < 292.5){ $string = "south";}
					if($var >= 202.5 && $var <= 247.5){ $string = "southwest";}
					if($var > 157.5 && $var < 202.5){ $string = "west";}
					if($var >= 112.5 && $var <= 157.5){ $string = "northwest";}
					if($var > 67.5 && $var < 112.5){ $string = "north";}
					if($var >= 22.5 && $var <= 67.5){ $string = "northeast";}
				}

				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				$message = "<i><b>{$msg_player_name}:</b> ";
				if($mole_found)
				{
					$message .= "The Mole is at your current location, <u>$msg_territory_name</u><br>.";
				}
				else 
				{
				$message .= "The Mole is $string of <u>$msg_territory_name</u>.</i><br>.";
				}
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 1,
				":recipient" => $act_player,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;

			}
			elseif($_POST['card_chooser'] === 10)
			{	// Open Borders
				// Removes restrictions from "Close Borders", "Local Lockdown", and "Monolingual".)
				foreach($triggers as $key => $var)
				{
					if($var['trigger_name'] === "close_borders" || $var['trigger_name'] === "local_lockdown" || $var['trigger_name'] === "monolingual")
					{
						unset($triggers[$key]);
					}
					$new_triggers = json_encode($triggers, true);
					$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
					$sth->execute([
						':triggers' => $new_triggers,
						'game_id' => $game_id
					]);	
				}

				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);	

			}
			elseif($_POST['card_chooser'] === 11)
			{	// Close Roads
				// Prevents car and motorcycle travel, etc. Blocks travel by type.
				$triggers[] = array(
					"player" => $game_data['current_player'],
					"trigger_name" => "close_roads",
					"link_styles" => ["car", "motorcycle"],
					"regions" => [],
					"links" => []
					);

				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);	
			}
			elseif($_POST['card_chooser'] === 12)
			{	// Close Airports
				// Blocks jet and prop plane travel. Blocks travel by type.
				$triggers[] = array(
					"player" => $game_data['current_player'],
					"trigger_name" => "close_airports",
					"link_styles" => ["jet", "prop"],
					"regions" => [],
					"links" => []
					);

				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);	
			}
			elseif($_POST['card_chooser'] === 13)
			{	// Open Roads and Airports
				// Removes blocks from "Close Roads" and "Close Airports".
				foreach($triggers as $key => $var)
				{
					if($var['trigger_name'] === "close_roads" || $var['trigger_name'] === "close_airports")
					{
						unset($triggers[$key]);
					}
				}
				$new_triggers = json_encode($triggers, true);
				$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
				$sth->execute([
					':triggers' => $new_triggers,
					'game_id' => $game_id
				]);
			}
			elseif($_POST['card_chooser'] === 14)
			{	// Monolingual
				// Prevents travel between *all* regions; used in Alphabet Soup
				$triggers[] = array(
					"player" => $game_data['current_player'],
					"trigger_name" => "monolingual",
					"link_styles" => [],
					"regions" => [	"1", "2", "3", "4"	],
					"links" => []
					);
					// Include message here to show the continent that is restricted
					$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
					// Include the location that was investigated below
					$message = "<b>{$msg_player_name}</b> has locked down travel between <u><b>";
					$message .= "all</b></u> regions.<br>";
					$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
					$query->execute([
					":game_id" => $game_id,
					":message" => $message,
					":secret" => 0,
					":recipient" => 0,
					":move_id" => $next_move_in_log,
					]);
					$next_move_in_log += 1;
	
					$new_triggers = json_encode($triggers, true);
					$sth = $pdo->prepare("UPDATE `thnt_games` SET triggers = :triggers WHERE `game_id` = :game_id");
					$sth->execute([
						':triggers' => $new_triggers,
						'game_id' => $game_id
					]);	
			}
			// If we don't do this, then 'energyProcess' will overwrite the current player and cause problems
			if(!isset($end_game) || $end_game === 0){ $energy_return = energyProcess(); }
		}

		elseif($move_type === 5)
		{
			$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
			// Include the location that was investigated below
			$message = "<b>{$msg_player_name}</b> attempts to capture the Mole...<br>";
			$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
			$query->execute([
			":game_id" => $game_id,
			":message" => $message,
			":secret" => 0,
			":recipient" => 0,
			":move_id" => $next_move_in_log,
			]);
			$next_move_in_log += 1;
			// Capture the Mole, via the button - if you fail this attempt, you are out of the game
			if($player_data[$act_player-1]['location'] == $agents_array['mastermind'])
			{	// Success!
				$end_game = 1;
				capture_handler();
			}
			else
			{ // Failure
				$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
				$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
				// Include the location that was investigated below
				$message = "The Mole is not in <u>{$msg_territory_name}</u>, and <b>{$msg_player_name}</b> is now out of the hunt.<br>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 0,
				":recipient" => 0,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
				resign_handler(true);
			}
		}

		elseif($move_type === 3)
		{ // Passing the turn
			$energy_return = energyProcess();
			$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
			$msg_territory_name = $Territories[$player_data[$act_player-1]['location']]['territory_name'];
			$message = "";
			$message .= "<b>{$msg_player_name}</b>";
			$message .= " passes a turn in <u>$msg_territory_name</u>.<br>";
			$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
			$query->execute([
			":game_id" => $game_id,
			":message" => $message,
			":secret" => 0,
			":recipient" => 0,
			":move_id" => $next_move_in_log,
			]);	
			$next_move_in_log += 1;	
		}

		elseif($move_type === 4)
		{ // oh noes i just cant win its time to give up!11!!11!
			resign_handler();
		} // end "leave game" section
		if(!isset($end_game) || $end_game === 0)
		{

			/*
			We're moving, so we need to keep the positions of local agents in account.
			c) The 'state' of the agent in relation to a player:
			0) Undiscovered - these agents aren't in the array at all
			1) Discovered - the player has moved onto this agent's territory this turn - at the end of their turn, they should learn that they found an agent
			2) Known - the player knows where the agent is, but hasn't drawn cards yet
			3) Revealed; the player has drawn cards
			At the end of a turn, if there are any local agents 
			*/
			$old_territory = $player_data[$game_data['current_player']-1]['location'];

			// include a check here so that we don't bother to run the pathfinder if the local agent here has already been found
			if($move_type === 1)
			{
				$move_territory = $_POST['loc_chooser'];
				$player_data[$game_data['current_player']-1]['location'] = $_POST['loc_chooser'];
			} 
			elseif($move_type === 2)
			{
				if(!isset($move_territory)){$move_territory = $old_territory;}
				// move_territory will have been set by an earlier card
			}
			elseif($move_type === 3 || $move_type === 4)
			{
				$move_territory = $player_data[$act_player-1]['location'];
			}
			if(isset($player_data[$act_player-1]['inventory']['agents'][$Territories[$move_territory]['continent_id']]) && 
			$player_data[$act_player-1]['inventory']['agents'][$Territories[$move_territory]['continent_id']] != 0)
			{
				$distance = -2;
			} 
			else
			{
			//	var_see($move_territory, "Move territory");
			//	var_see($agents_array[$Territories[$player_data[$act_player-1]['location']]['continent_id']]['location'], "Agent territory");
//			$demo = PathFinder($move_territory, $agents_array[$Territories[$move_territory]['continent_id']]['location']);
//			echo "Demo: ", var_dump($demo), "<br>";
			$distance = PathFinder2($move_territory, $agents_array[$Territories[$move_territory]['continent_id']]['location']);
//			echo "Distance: ", var_dump($distance), "<br>";
			}
			if ($distance === 0)
			{
				// Convert undiscovered agents into discovered, but unknown, ones
				$player_data[$act_player-1]['inventory']['agents'][$Territories[$move_territory]['continent_id']] = 1;
				$json_inventory = json_encode($player_data[$act_player-1]['inventory']);
				$sth = $pdo->prepare("UPDATE `thnt_players` SET inventory = :inventory WHERE `game_id` = :game_id AND `move_order` = :move_order");
				$sth->execute([
					':inventory' => $json_inventory,
					':game_id' => $game_id,
					':move_order' => $game_data['current_player']
					]);
			}
			$player_agent_updates = array();
			$agent_array_changes = array();
			$array_count = count($player_data[$act_player-1]['inventory']['agents']);
			$array_sum = array_sum($player_data[$act_player-1]['inventory']['agents']);
			if($energy_return !== false)
			{
				if(!isset($resign))
					{
					/*
					It is a new turn now, so we need to know four things:
					a) Are there any unknown agents (agents discovered, but that the player doesn't know are discovered)? If so, we need to find out about them.
					b) Are there any known agents, and if so, are we outside of the region they're in? If so, we draw cards and reveal them.
					Both of these steps can happen at once, if a player discovers the agent and leaves the region simultaneously.
					c) Are all the agents discovered? If so, you draw a free card. This should probably be the first thing checked, in programming order.
					d) Are there any triggers that need to be undone?
					*/
					if($array_sum / $array_count === 3)
					{
						// Include a special message when all the agents are discovered later on in the "finding agent" section
						// Draw a free card
						$cards_drawn = DrawCards(1);
						$message = "<b>";
						$message .= say($player_data[$act_player-1]['username']);
						$message .= "</b> ";
						if($cards_drawn > 0)
						{
							$message .= "draws a card.<br>";
						}
						else
						{
						$message .= "was unable to draw from the empty deck.<br>";
						}
						$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
						$query->execute([
						":game_id" => $game_id,
						":message" => $message,
						":secret" => 0,
						":recipient" => 0,
						":move_id" => $next_move_in_log,
						]);
						$next_move_in_log += 1;
						UpdateInventory();
					}
					else 
					{
						// Show local agent
						if($player_data[$act_player-1]['inventory']['agents'][$Territories[$move_territory]['continent_id']] === 0)
						{
							$msg_player_name = say($player_data[$game_data['current_player']-1]['username']);
							$msg_territory_name = $Territories[$move_territory]['territory_name'];
							$message = "<i><b>{$msg_player_name}:</b> <u>";
							$message .= $continent_data[$Territories[$move_territory]['continent_id']-1]['agent_name'];
							$message .= "</u> is {$distance} territor";
							if($distance === 1) { $message .= "y";} else {$message .= "ies";}
							$message .= " away from $msg_territory_name."; 
							$message .= "</i><br>";
							$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
							$query->execute([
							":game_id" => $game_id,
							":message" => $message,
							":secret" => 1,
							":recipient" => $act_player,
							":move_id" => $next_move_in_log,
							]);
							$next_move_in_log += 1;	
						}

						$card_count = array();

						foreach($player_data[$act_player-1]['inventory']['agents'] as $key => $value)
						{
							if($value === 1)
							{
								// This changes discovered agents into known ones, and should only happen on turn #2
								$message = "<b><i>";
								$message .= say($player_data[$act_player-1]['username']);
								$message .= "</b>, you discovered ";
								$message .= $continent_data[$key-1]['agent_name'];
								$message .= " in ";
								$message .= $Territories[$agents_array[$key]['location']]['territory_name'];
								$message .= ". Once you leave ";
								$message .= $continent_data[$key-1]['continent_name'];
								$message .= ", you will draw your cards.</i><br>";
								
								// The player has moved onto this agent's territory, but hasn't known about it up until now. Since the turn is ending, it's time to correct that.
								$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
								$query->execute([
								":game_id" => $game_id,
								":message" => $message,
								":secret" => 1,
								":recipient" => $act_player,
								":move_id" => $next_move_in_log,
								]);
								/* To accurately keep track of who discovered agents, and who is owed what cards, we take the agents_array card count,
								multiply it by a negative number, and store it in the player array. Then we update the agents array now, instead of waiting
								for the card draw.
								The following sections will need to be rewritten to account for this.
								*/
								//$agents_array[$key]['cards']
								$player_agent_updates[$key] = $agents_array[$key]['cards'] * -1;
								// var_see($player_agent_updates, "Player agent updates");
								$next_move_in_log += 1;
								// We want to decrement agent card counts as soon as they're discovered, not as soon as they're drawn
								if($agents_array[$key]['cards'] > 1)
								{
									$agent_array_changes[$key] = $agents_array[$key]['cards'] - 1;
								}
							}
							// If the player agent value is *already* set, from before, then we know that you didn't just discover this agent this turn. In this case, the fact that you came from another continent means you moved in and out, qualifying you.
							if(($value < 0) && ($Territories[$move_territory]['continent_id'] != $key || ($Territories[$old_territory]['continent_id'] != $key && $Territories[$move_territory]['continent_id'] == $key)))
							{
								$card_count[$key] = $value;
							//	echo "Card count 1: $card_count";
								$player_agent_updates[$key] = 3;
							}
							// If you just found an agent, and ended your turn outside of the continent, you immediately earn cards.
							elseif(((isset($player_agent_updates[$key]) && $player_agent_updates[$key] < 0)) && ($Territories[$move_territory]['continent_id'] != $key))
							{
								$card_count[$key] = $player_agent_updates[$key];
							//	echo "Card count 2: $card_count";
								$player_agent_updates[$key] = 3;
							}
							// This should end the player agent thing
						}
						if(!empty($player_agent_updates))
						{
							foreach($player_agent_updates as $key => $value)
							{
								if($value == 3)
								{
									// $card_count is set right up above, and should always be a legit value... except for when it randomly isn't
									// var_see($card_count, "Card count");
									$card_count[$key] = $card_count[$key] * -1;
									$cards_drawn = DrawCards($card_count[$key]);
									// This changes known agents into revealed ones, which means a public message and cards - provided the continent is different
									$message = "<b>";
									$message .= say($player_data[$act_player-1]['username']);
									$message .= "</b> discovered ";
									$message .= $continent_data[$key-1]['agent_name'];
									$message .= " in ";
									$message .= $continent_data[$key-1]['continent_name'];
									if($cards_drawn > 0)
									{
										$message .= ", and has now drawn ";
										$message .= $cards_drawn;
										$message .= " cards";
										if($cards_drawn < $card_count[$key])
										{
											$message .= ", all that was left in the deck.<br>";
										}
										else 
										{
											$message .= ".<br>";
										}
									}
									else
									{
									$message .= ", but there are no cards in the deck to draw.<br>";
									}
									$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
									$query->execute([
									":game_id" => $game_id,
									":message" => $message,
									":secret" => 0,
									":recipient" => 0,
									":move_id" => $next_move_in_log,
									]);
									$next_move_in_log += 1;
								}
								$player_data[$act_player-1]['inventory']['agents'][$key] = $value;
							}
							$array_count = count($player_data[$act_player-1]['inventory']['agents']);
							$array_sum = array_sum($player_data[$act_player-1]['inventory']['agents']);
							if($array_sum / $array_count === 3)
							{
								// Announce that all the agents have been discovered
								$message = "<b>";
								$message .= say($player_data[$act_player-1]['username']);
								$message .= "</b> has found all of the agents, and from now on, will draw a card at the end of each turn.<br>";
							}
							if(!empty($agent_array_changes))
							{
								foreach($agent_array_changes as $key => $value)
								{
									$agents_array[$key]['cards'] = $value;
								}
								$json_agents = json_encode($agents_array);
								$sth = $pdo->prepare("UPDATE `thnt_games` SET agents = :agents WHERE `game_id` = :game_id");
								$sth->execute(
									[
									':agents' => $json_agents,
									':game_id' => $game_id,
									]);
							}
							// echo "Update inventory: ";
							UpdateInventory();
						}
					}
				} // end 'didn't resign'
				$message = "<span class='z3'>It is now <b>";
				$message .= $player_data[$energy_return-1]['username'];
				$message .= "</b>'s turn.</span><hr>";
				$query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
				$query->execute([
				":game_id" => $game_id,
				":message" => $message,
				":secret" => 0,
				":recipient" => 0,
				":move_id" => $next_move_in_log,
				]);
				$next_move_in_log += 1;
			}
		}
			exit(header("Location: index.php?s=thehunt&a=play&gm=$game_id"));
			refreshOrder();
	}
	// End processing a move
}
// End active game section

/*
Moves (for history)
Messages (separate message table?)

Actor locations (players?)
Belongings/Card decks (actor decks, and also the main deck) (players?)
Link states (if any are turned off) ()
say you play a close airports card -
updates an array? and the array is pulled, and in game state
then a function or something determines if certain links are good
Whose turn it is (games?)
How many turns have passed? (games?)
Current player's turns allowed (games?)
Whether someone is active in the game (vs. having resigned) (players?)

*/

// Stores information about mastermind, agents, and players

// Global Jump test
if($game_data['game_status'] === "active")
{
//	var_see($bad_links, "Bad links");
// var_dump($player_data[$act_player-1]['location']);
// if($player_data[$act_player-1]['location'] === 1){ $jumptest = 2;} else {$jumptest = 1;}
// $maxdist = Glob_Calculator($player_data[$act_player-1]['location'], count($Territories));
// var_see($maxdist, "Max dist");
}


// Change color palette depending on the map. Use ones that match the "Colorblind Palette".
$color_palette = array(
	array( // black
		"r" => 0,
		"g" => 0,
		"b" => 0,
	),

	array( // navy
		"r" => 0,
		"g" => 0,
		"b" => 59,
	),

	array( // dark teal
		"r" => 0,
		"g" => 49,
		"b" => 49,
	),

	array( // dark green
		"r" => 0,
		"g" => 81,
		"b" => 0,
	),

	array( // brown
		"r" => 127,
		"g" => 63,
		"b" => 0,
	),

	array( // red
		"r" => 192,
		"g" => 0,
		"b" => 0,
	),

	array( // purple
		"r" => 173,
		"g" => 0,
		"b" => 173,
	),

	array( // cyan
		"r" => 0,
		"g" => 125,
		"b" => 249,
	),

	array( // light grey
		"r" => 155,
		"g" => 155,
		"b" => 155,
	),

	array( // foam green
		"r" => 0,
		"g" => 226,
		"b" => 113,
	),

	array( // chartreuse
		"r" => 123,
		"g" => 246,
		"b" => 0,
	),

	array( // sweettart purple
		"r" => 232,
		"g" => 208,
		"b" => 255,
	),

	array( // light yellow
		"r" => 255,
		"g" => 255,
		"b" => 153,
	),

	array( // white
		"r" => 255,
		"g" => 255,
		"b" => 255,
	)
);

/* A sample instruction array, used to handle maps. */
$image_instructions = array();
$image_instructions['board_image_array'] = $board_image_array;
if($_SESSION['theme'] == "dark"){ $board_image_array['image_name'] .= "_dark";}
$image_instructions['board_dir'] = "{$board_image_array['image_name']}.png";

/* Example color steps - recolor "A" on AlphabetSoup, and the A > B link, respectively
$image_instructions['color_step'][0]['r'] = 128;
$image_instructions['color_step'][0]['g'] = 128;
$image_instructions['color_step'][0]['b'] = 128;
$image_instructions['color_step'][0]['x'] = 60;
$image_instructions['color_step'][0]['y'] = 43;
$image_instructions['color_step'][1]['r'] = 255;
$image_instructions['color_step'][1]['g'] = 128;
$image_instructions['color_step'][1]['b'] = 128;
$image_instructions['color_step'][1]['x'] = 40;
$image_instructions['color_step'][1]['y'] = 40;
*/

$image_instructions['circle_step'] = array();
if($game_data['game_status'] !== "open")
{
	$i = 0;
//	var_see($board_image_array, "Board image");
	foreach($player_data as $player_loc)
		{
			$v = $board_image_array['players'][$i];
			$image_instructions['circle_step'][$i]['r'] = $color_palette[$v]['r'];	
			$image_instructions['circle_step'][$i]['g'] = $color_palette[$v]['g'];	
			$image_instructions['circle_step'][$i]['b'] = $color_palette[$v]['b'];	
			$image_instructions['circle_step'][$i]['x'] = $territory_data[$player_loc['location']-1]['x'];
			$image_instructions['circle_step'][$i]['y'] = $territory_data[$player_loc['location']-1]['y'];
			$i += 1;
		}
}
unset($i);
$_SESSION['instructions'] = $image_instructions;
// var_dump($_SESSION);
// End instruction array

// require("".path."/thehunt/temps/play_tpl.php");
?>

<?php



/* 
	Card types:
	Search/Capture
	Point
	Take Another Turn
	Close Roads
	Close Airports
	Open Roads and Airports
	Place Trap* (not present, or used, in our personal copy)
	Bug Another Agent
	Global Jump
*/
?>
