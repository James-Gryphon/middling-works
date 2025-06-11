<a id="most_specific" class="skip">Content</a>
<?php
$errors = array();

if($action == "lobby"):
$query = $pdo->query("SELECT * from thnt_boards");
$boards_listing = $query->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['create_game']) && (!empty($thnt_username) || $user_id > 0)){
    if(isset($_POST['game_name'])){ $create_game_name = $_POST['game_name'];} else {$create_game_name = "Anonymous Game"; $create_game_name .= time(); }
    if(isset($_POST['maximum_players']) && $_POST['maximum_players'] > 1 && $_POST['maximum_players'] < 13){ $create_game_players = $_POST['maximum_players']; } else {$create_game_players = 4;}
	if(isset($_POST['private_game'])){$create_private_game = 1; } else {$create_private_game = 0;}
	if(isset($_POST['board_name']) && isset($boards_listing[$_POST['board_name']-1])){$create_board_id = $_POST['board_name'];} else {$create_board_id = 1;}
	$access_code = bin2hex(random_bytes(32));
	// add something here to make sure no access code of this type exists already
	$query = $pdo->prepare("INSERT INTO `thnt_games` (`game_name`, `board_id`, `player_count`, `private`, `access_code`, `game_status`) VALUES (:game_name, :board_id, :player_count, :private, :access_code, :game_status)");
	$query->execute([
	":game_name" => $create_game_name,
	":board_id" => $create_board_id,
	":player_count" => $create_game_players,
	":private" => $create_private_game,
	":access_code" => $access_code,
	":game_status" => "open"
	]);
	$game_id = $pdo->lastInsertId();
	$ip = $_SERVER['REMOTE_ADDR'];
	$query = $pdo->prepare("INSERT INTO `thnt_players` (`game_id`, `username`, `user_id`, `ip`, `local_id`, `move_order`) VALUES (:game_id, :username, :user_id, :ip, 1, 0)");
	$query->execute([
	":game_id" => $game_id,
	":username" => $thnt_username,
	":user_id" => $user_id,
	":ip" => $ip
	]);
}

if(isset($_GET['join']) || isset($_POST['join'])){ $join = true;}
if(isset($join) && (!empty($thnt_username) || $user_id > 0) && isset($_GET['gm'])){
// make sure the game exists and is open
$query = "SELECT * from thnt_games WHERE game_id = :game_id AND game_status = 'open'";
$query = $pdo->prepare($query);
$query->execute([
	":game_id" => $_GET['gm']
]);
$game_to_join = $query->fetch(PDO::FETCH_ASSOC);
if(!empty($game_to_join)){
// game exists and is open
if(($game_to_join['private'] == 1 && !empty($_GET['game_code']) && $_POST['game_code'] == $game_to_join['access_code']) || $game_to_join['private'] == 0){
// game is either public, or you have the correct code
$query = "SELECT * from thnt_players WHERE game_id = :game_id AND ";
if(!empty($thnt_username)){$query_var = $thnt_username;} else {$query_var = $user_id;}
if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
$query = $pdo->prepare($query);
$query->execute([
":game_id" => $_GET['gm'],
":query_var" => $query_var
]);
$existing_player = $query->fetch(PDO::FETCH_ASSOC);
if(empty($existing_player)){
	$ip = $_SERVER['REMOTE_ADDR'];
	$query = "SELECT local_id from thnt_players WHERE game_id = ?";
	$query = $pdo->prepare($query);
	$query->execute([
	$_GET['gm']
	]);
	$local_id_list = $query->fetchAll(PDO::FETCH_ASSOC);
	$i = 2;
	while($i > 0){ // potential infinite loop here if not broken
	if(in_array($i, $local_id_list)){$i += 1;} else {$local_id = $i; break;}
	}
	$query = $pdo->prepare("INSERT INTO `thnt_players` (`game_id`, `username`, `user_id`, `ip`, `local_id`, `move_order`) VALUES (:game_id, :username, :user_id, :ip, :local_id, 0)");
	$query->execute([
	":game_id" => $game_to_join['game_id'],
	":username" => $thnt_username,
	":user_id" => $user_id,
	":ip" => $ip,
	":local_id" => $local_id
	]);
}
}
}
}

if(isset($_GET['leave']) && (!empty($thnt_username) || $user_id > 0) && isset($_GET['gm'])){
	echo "Leave to go...<br>";
	$query = "SELECT * from thnt_games WHERE game_id = :game_id";
	$query = $pdo->prepare($query);
	$query->execute([
	":game_id" => $_GET['gm']
	]);
	$game_to_leave = $query->fetch(PDO::FETCH_ASSOC);
	if(!empty($game_to_leave)){
	echo "Selecting player right.<br>";
		$query = "SELECT * from thnt_players WHERE game_id = :game_id AND ";
		if(!empty($thnt_username)){$query_var = $thnt_username;} else {$query_var = $user_id;}
		if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
		$query = $pdo->prepare($query);
		$query->execute([
		":game_id" => $_GET['gm'],
		":query_var" => $query_var
		]);
		$existing_player = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($existing_player)){
		echo "Player exists...<br>";
		/* Now, there are several factors to consider. 
		If the game has started, then leaving shouldn't remove you from the game or even from the move order. Instead, it should register as a new move.
		If the game hasn't started, then leaving should remove you from the game altogether, unless you are the host. If you are, the game should be cancelled.
		If the game has finished, then it's too late to leave; ignore the request.
		*/
		if($game_to_leave['game_status'] == "open"){
		if($existing_player['local_id'] == 1){
		echo "Nuking game.<br>";
		// You are the host, so nuke the game.
		$query = "DELETE from thnt_games WHERE game_id = :game_id";
		$query = $pdo->prepare($query);
		$query->execute([":game_id" => $game_to_leave['game_id']]);
		$query = "DELETE from thnt_players WHERE game_id = :game_id";
		$query = $pdo->prepare($query);
		$query->execute([":game_id" => $game_to_leave['game_id']]);
		} else{
		echo "Leaving game.<br>";
		// You aren't the host, so just delete your own entry.
		$query = "DELETE from thnt_players WHERE game_id = :game_id AND local_id = :local_id";
		$query = $pdo->prepare($query);
		$query->execute([":game_id" => $game_to_leave['game_id'], ":local_id" => $existing_player['local_id']]);
		}
		}
		elseif($game_to_leave['game_status'] == "active"){
		echo "oh noes i just cant win its time to give up!!111<br>";
		$query = "SELECT `move_id` from `thnt_move_log` WHERE game_id = :game_id AND action = :action AND local_id = :local_id";
		$query = $pdo->prepare($query);
		$query->execute([
		":game_id" => $game_to_leave['game_id'],
		":action" => "resign",
		":local_id" => $existing_player['local_id'],
		]);
		$resigned_already = $query->fetch($PDO::FETCH_ASSOC);
		if(empty($resigned_already)){
		$query = "SELECT `move_id` from `thnt_move_log` WHERE game_id = ? ORDER BY `move_id` DESC LIMIT 1";
		$query = $pdo->prepare($query);
		$query->execute([$game_to_leave['game_id']]);
		$last_move_id = $query->fetch($PDO::FETCH_ASSOC);
		$query = "INSERT INTO `thnt_move_log` (`game_id`, `local_id`, `move_id`, `action`)";
		$query = $pdo->prepare($query);
		$query->execute([
		":game_id" => $game_to_leave['game_id'],
		":local_id" => $existing_player['local_id'],
		":move_id" => $last_move_id['move_id'],
		":action" => "resign"
		]);
		} // end resign
		} // end game active
		} // end isset existing player
	} // end not-empty "game to leave"
} // end leave block

$query = "SELECT * from thnt_games WHERE game_status = 'open' AND private = 0; SELECT game_id from thnt_players WHERE ";
if(!empty($thnt_username)){$query_var = $thnt_username;} else {$query_var = $user_id;}
if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
$query .= " AND local_id > 1; ";
$query .= "SELECT game_id from thnt_players WHERE ";
if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
$query .= " AND local_id = 1;";
$query = $pdo->prepare($query);

/* $query = $pdo->prepare("SELECT * from thnt_games WHERE game_status = 'open' AND private = 0;
SELECT game_id from thnt_players WHERE username = :thnt_username OR user_id = :user_id AND local_id > 1;
SELECT game_id from thnt_players WHERE (username = :thnt_username OR user_id = :user_id) AND local_id = 1
"); */
$query->execute([
":query_var" => $query_var
]);
$public_game_list = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$games_user_joined = $query->fetchAll(PDO::FETCH_COLUMN);
$query->nextRowset();
$games_user_hosting = $query->fetchAll(PDO::FETCH_COLUMN);
$games_user_joined = implode(",", $games_user_joined);
$games_user_hosting = implode(",", $games_user_hosting);
$games_user_in = $games_user_joined;
$games_user_in .= $games_user_hosting;
// we need active games we're in, games we're hosting that haven't started yet, games we've joined that haven't started yet, and finished games
if(!empty($games_user_in)){
// If this is completely empty, then there's no point in checking anything
if(!empty($games_user_joined)){
// idea to use IN provided by Google search results for 'match array' - eduCBA?
$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN ($games_user_joined) AND game_status = 'open'");
$joined_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
}
if(!empty($games_user_hosting)){
$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN ($games_user_hosting) AND game_status = 'open'");
$hosted_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
echo "Hosted Games List:"; var_dump($hosted_games_list); echo "<br>";
}

$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN ($games_user_in) AND game_status = 'active';
SELECT * from thnt_games WHERE game_id IN ($games_user_in) AND game_status = 'completed';
");
$active_games = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$completed_games = $query->fetchAll(PDO::FETCH_ASSOC);
}

if(!empty($thnt_username) || $user_id > 0):
?>
<div class="main_box cbox">
<div class="title_header"><h3>The Hunt</h3></div><br>
<div class="main_box_content">
<div class="mini_box">
<b>Create a Game</b><br>
<form name="create_game" method="post" action='index.php&s=thehunt&a=lobby'>
<input type="hidden" id="create_game" name="create_game" value="1">
<label for="game_name">Game Name</label><br>
<input type="text" id="game_name" name="game_name"><br>
<label for="player_count">Maximum Players</label><br>
<input type="number" id="player_count" name="player_count" value="2"><br>
<label for="board_name">Board</label><br>
<select id="board_name" name="board_name">
<?php
foreach($boards_listing as $board){
echo "<option value='{$board['board_id']}'>{$board['board_name']}</option>";
}
?>
</select><br>
<label for="private">Private?</label>
<input type="checkbox" id="private_game" name="private_game">
<br>
<input type="submit" value="Start Game"><br>
</form>
</div>
<hr>
<div class="mini_box">
<form name="join_game" method="post" action='index.php&s=thehunt&a=lobby'>
<b>Join a Private Game</b><br>
<label for="join_game">Game Code</label><br>
<input type="text" id="game_code"><br>
<input type="submit" value="Join Game"><br>
</form>
</div>
<hr>
</div></div>
<?php endif; ?>
<div class="main_box lbox">
<div class="title_header"><h3>Public Games</h3></div><br>
<div class="main_box_content lobby_box">
<table>
<thead>
<tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($public_game_list)){
foreach($public_game_list as $public_game){
	$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
	$query->execute([
	$public_game['game_id']
	]);
	$public_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
	if(isset($public_game_players[0]['username'])){ $public_game_host = say($public_game_players[0]['username']);}
	else {$public_game_host = fetchPlayerName($public_game_players[0]['user_id']);}
	$player_count = count($public_game_players);
echo
"<tr><td><a href='index.php?s=thehunt&a=play&gm={$public_game['game_id']}'>", say($public_game['game_name']), "</a>";
$in_game = false;
foreach($public_game_players as $player){
if($thnt_username === $player['username'] || $user_id === $player['user_id']){ $in_game = true;}
}
if($in_game === false){ echo " <a href='index.php?s=thehunt&a=lobby&gm={$public_game['game_id']}&join'>(join)</a>";}
echo "</td><td>$public_game_host</td>", "<td>$player_count out of {$public_game['player_count']}</td><td>", $boards_listing[$public_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($public_game['created']), "</td></tr>";
}
}
?>

</tbody></table>
</div>
</div>
<?php
if(!empty($thnt_username) || $user_id > 0):
?>
<div class="main_box lbox">
<div class="title_header"><h3>Your Games</h3></div><br>
<div class="main_box_content your_games">
<b>Active Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($active_games)){
	foreach($active_games as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a></td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>	
</tbody></table><b>Hosted Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($hosted_games_list)){
	foreach($hosted_games_list as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a> <a href='index.php?s=thehunt&a=lobby&gm={$active_game['game_id']}&leave'>(stop)</a></td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>
</tbody></table><b>Joined Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($joined_games_list)){
	foreach($joined_games_list as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), " <a href='index.php?s=thehunt&a=lobby&gm={$active_game['game_id']}&leave'>(leave)</a></a></td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>
</tbody></table><b>Your Completed Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($completed_games)){
	foreach($completed_games as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a></td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>
</tbody></table>
</div>
</div>
<?php
endif;
elseif($action == "play"):
if(isset($_GET['gm']) && is_numeric($_GET['gm'])){ $game_id = $_GET['gm'];}
// include something here for invalid IDs
$query = $pdo->prepare("SELECT * FROM thnt_games WHERE game_id = ?");
$query->execute([$game_id]);

$game_data = $query->fetch(PDO::FETCH_ASSOC);
	$query = $pdo->prepare(
	"SELECT * FROM thnt_boards WHERE board_id = :board_id;
	SELECT * FROM thnt_continents WHERE board_id = :board_id;
	SELECT * FROM thnt_territories WHERE board_id = :board_id;
	SELECT * FROM thnt_territory_links WHERE board_id = :board_id;
	SELECT * FROM thnt_players WHERE game_id = :game_id;
	SELECT * from thnt_move_log WHERE game_id = :game_id;
	SELECT * from thnt_card_types;
	");
	$query->execute(
	[
	":game_id" => $game_data['game_id'],
	":board_id" => $game_data['board_id']
	]
	);
	// Thanks to edmondscommerce: https://stackoverflow.com/questions/21485868/php-pdo-multiple-select-query-consistently-dropping-last-rowset
	$board_data = $query->fetch(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$continent_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$territory_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$territory_link_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$player_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$player_move_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$card_types = $query->fetchAll(PDO::FETCH_ASSOC);

	// Permission/login stuff
	if(!empty($thnt_username) || ($user_id > 0)){
		foreach($player_data as $player){
		if($thnt_username === $player['username'] || $user_id === $player['user_id']){ $act_player = $player['local_id']; break;}
		}}
		if(!isset($act_player)){$act_player = 0;}
	
		echo "Act Player: ", $act_player, "<br>";

	$Continents = array();
	$Territories = array();
	foreach($territory_data as $title => $territory_row){
	$Territories[$territory_row['territory_id']] = $territory_row;
	$Continents[$territory_row['continent_id']][] = $territory_row['territory_id'];
	}
	
	/* Build territory link table - for use in pathfinder */
	foreach($territory_link_data as $territory_link){
	$Territories[$territory_link['territory_one_id']]['links'][] = $territory_link['territory_two_id'];
	if($territory_link['one_way'] == 0){
	$Territories[$territory_link['territory_two_id']]['links'][] = $territory_link['territory_one_id'];
	}
	}

	// Stores information about mastermind, agents, and players
	$actors = array();
	// Keeps track of types of links that aren't available, according to closure cards
	$bad_links = array();

	// now to process the moves
	/* The structure of a move log entry:
	game_id: denotes what game this belongs to
	local_id: who made the move? 1 and up are players; 0 is reserved for automatic game functions.
	move_id: shows the chronological order the moves took place in, although 'acted' would probably suffice.
	acted: a timestamp for when the action was performed.
	secret: whether the move is visible to all players or not. 0 is yes, 1 is visible only to local id, 2 is always hidden. secrets are revealed at end of game. (thus, secret moves with local_id 0 are hidden until the end of the game.)
	op_cost: "turn cost" for an action -
	action: What type of action is going to be performed in this move. There are only a few options, including:
	1. Moving
	2. Playing a card
	3. Drawing a card
	4. Placing the mastermind/agent/player (reserved, of course, to local_id 0)
	5. Flip travel options (allowing or preventing future moves by players) - whether by continents, territories, or path types

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

	/* The thing to remember about this section is that most of these actions are relatively inert; they don't change game state, but rather load it into memory. */
	foreach($player_move_data as $move){
	switch($move['action']){
	case "move":
	$actors[$move['local_id']]['location'] = $move['m_param'];
	break;
	case "draw_card":
	if(isset($actors[$move['local_id']]['cards'][$move['m_param']])){ $actors[$move['local_id']]['cards'][$move['m_param']] += 1; }
	else { $actors[$move['local_id']]['cards'][$move['m_param']] = 1;}
	break;
	case "play_card":
	// shouldn't need to actually do anything but remove a card - the power should be performed as part of another move
	if(isset($actors[$move['local_id']]['cards'][$move['m_param']])){
	$actors[$move['local_id']]['cards'][$move['m_param']] -= 1; 
	if ($actors[$move['local_id']]['cards'][$move['m_param']] === 0){unset($actors[$move['local_id']]['cards'][$move['m_param']]);}
	}
	break;
	case "close_route":
	// Needs to be able to close routes to/from: 1) continents, 2) certain types of links, and 3) individual territories.
	// m_param is type, s_param is the ID or type of thing being closed. It should only apply within a range of moves.
	$bad_links[] = array($move['move_id'], $move['m_param'], $move['s_param']);
	break;
	case "place_actor":
		if($move['m_param'] != "player"){
		$actors[$move['m_param']]['location'] = $move['s_param'];
		break;
		}
		else {
		$i = count($player_data);
		$start_point = $move['s_param'];
		while($i >= 0){
		$actors[$i]['location'] = $move['s_param'];
		// do player location assignment here
		$i -= 1;
		}
		}
	}

	}

    ?>
	<div class="main_box full_width">
	<div class="title_header"><h3>The Hunt</h3></div><br>
	<div class="main_box_content">
    <div class="game_box">
	<div class="left_wing">
    <div class="map_box"><img src="demo_map.png" alt="demo"></div>
    <div class="info_box">
	<span class='z6'><b><?=$game_data['game_name']?></b></span><br>
	<?php if(isset($act_player)): ?>
    <span class='z3'>Game Code: <?=$game_data['access_code']?> <?php if($game_data['private'] == 1){ echo "<i>(private game)</i>"; }?></span><br>
	<?php endif; ?>
    <span class='z3'>Created: <?=qdateBuilder($game_data['created'])?></span><br>
    <span class='z3'>Last Activity: <?=qdateBuilder($game_data['updated'])?></span><br>
    <span class='z3'>Board: <?=$board_data['board_name']?></span><br>
    <span class='z3'>Status: <?=$game_data['game_status']?></span>
    </div>
	</div>

	<div class="right_wing">
	<?php
	if($game_data['game_status'] !== "open"):
	?>
	<div class="location_blurb">
	<div class="region_box">
	<?php
	echo "<span class='z2'><b>";
	echo $continent_data[$territory_data[$actors[$act_player]['location']]['continent_id']-1]['continent_name'];
	echo "</b></span><br><span class='z3'>";
	echo $continent_data[$territory_data[$actors[$act_player]['location']]['continent_id']-1]['continent_blurb'];
	echo "</span>";
	?>
	</div>
	<div class="territory_box">
	<?php
	echo "<b>";
	echo $territory_data[$actors[$act_player]['location']-1]['territory_name'];
	echo "</b><br>";
	echo $territory_data[$actors[$act_player]['location']-1]['territory_blurb'];
	?>
	</div>
	</div>
	<?php
	else:
	echo "<div class='location_blurb'><i>The game hasn't started yet.</i></div>";
	endif;
	?>
    <div class="orders_box">
	<div class="card_box">
	<form name="card_player" method="POST">
	<select name="card_chooser" id="card_chooser" multiple="">
	<?php
	if(!empty($actors[$act_player]['cards'])){
	foreach($actors[$act_player]['cards'] as $card_type => $card_count){
	echo "<option value='{$card_type}'>{$card_types[$card_type-1]['card_name']} ({$card_count})</option>";
	if($card_type === 1){
	// "Search for Mastermind" and "Capture Mastermind" are a combo card
	echo "<option value='{$card_type}'>{$card_types[$card_type]['card_name']} ({$card_count})</option>";
	}
	}
	}
	else {echo "<option value='none' disabled>No cards in hand</option>";}
	?>
    </select><br><input type="submit" value="Play Card"></form></div><div class="move_box">
    <form name="loc_chooser" method="GET">
    <select name="loc_chooser" id="loc_chooser" multiple="">
    <?php
    // insert foreach option here
	if($game_data['game_status'] !== "open"):
	foreach($Territories[$actors[$act_player]['location']]['links'] as $id => $link){
	echo "<option value='$link'>{$Territories[$link]['territory_name']}</option>";
	}
endif;
    ?>
    </select><br><input type="submit" value="Move"></form></div>
	<?php
	/* Pass should only be available when it is your turn
	Start is only available to the host, when the game is 'open'
	Join is only available to non-hosts, when the game is 'open'
	Leave game is available to non-hosts when the game is open, or to anyone when the game is active
	Stop game is only available to the host, when the game is 'open'
	*/
	?>
	<div class="pass_box"><form name="order_form" method="POST"><input type="submit">
	<label for="pass_turn" class="">Pass</label><input type="radio" name="order_box" id="pass_turn" value="pass_turn">
	<label for="start_game" class="">Start</label><input type="radio" name="order_box" id="start_game" value="start_game"
	<?php if($act_player !== 1){ echo "disabled"; } ?>
	>
	<label for="join_game" class="">Join</label><input type="radio" name="order_box" id="join_game" value="join_game"
	<?php if($act_player === 1){ echo "disabled"; } ?>
	>
	<label for="leave_game" class="">Leave</label><input type="radio" name="order_box" id="leave_game" value="leave_game">
	<label for="stop_game" class="">Stop</label><input type="radio" name="order_box" id="stop_game" value="stop_game"
	<?php if($act_player !== 1){ echo "disabled"; } ?>
	></form>
	</div>
    </div>
    <div class="log_box">
	<div class="move_info"><z5><b>Move Log</b></z5><br>
	<div class="move_log">
<?php
foreach($player_move_data as $move){
	// If not secret, or if local id is you, or if game is over, show action
	if(
		$move['secret'] == 0 ||
		((($move['local_id'] == $act_player && $act_player != 0) || $game_data['game_status'] == "complete") && $move['secret'] == 1)
		){
	switch($move['action']){
		case "move":
		echo fetchPlayerName($move['local_id']), " moves to <b>", $Territories[$actors[$move['local_id']]['location']]['territory_name'], "</b>";
		break;
		case "message":
		if(!empty($move['sec_message']) && $move['local_id'] === $act_player || $game_data['game_status'] === "complete"){echo "<i>", say($move['sec_message']), "</i>";}
		else {echo say($move['message']);}
		break;
		case "play_card":
		echo fetchPlayerName($move['local_id']), " plays <b><span class='z3'>", $card_types[$move['m_param']-1]['card_name'], "</span></b>";
		break;
		case "place_actor":
		if($move['m_param'] == "player"){ echo "Players start at <b>", $Territories[$move['s_param']]['territory_name'], "</b>";}
		break;
	}
echo "<br>";
}
}
?>
</div>
</div>
</div>

<div class="roster_box">
<div class="game_info"><z5><b>Agents</b></z5><br>
<?php
foreach($player_data as $player){
if(!empty($player['username'])){say($player['username']);}
else {echo "<u>"; $temp = get_username($player['user_id']); echo $temp[1]; echo "</u>";}
echo "{$player['username']}";
if($game_data['game_status'] !== "open"){
	 echo "- ", $Territories[$actors[$player['local_id']]['location']]['territory_name'], " <i>(cards: ";
	 if(!empty($actors[$player['local_id']]['cards'])){ echo count($actors[$player['local_id']]['cards']); } else {echo "0";}
	 echo ")</i>";
	}
echo "<br>";
}
?>
</div>
</div>
</div>
</div>

<?php
endif;
	// going to need to check on the div closures and line break up above.
function fetchPlayerName($local_id){
global $player_data;
$adj_id = $local_id - 1;
if(!empty($player_data[$adj_id]['username'])){ $username = say($player_data[$adj_id]['username']);}
else {$username = "<i>"; $temp = get_username($player_data[$adj_id]['user_id']); $username .= $temp[0]; $username .= "</i>";}
return $username;
}
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
<footer class="thehunt">
<table class="footer_table"><tbody><tr><td><a href="index.php?s=thehunt&a=lobby">Game Lobby</a></td><td class="version">The Hunt - v1.0.0</td><td class="login"><?=$acc_var?></td></tr></tbody></table>
</footer>