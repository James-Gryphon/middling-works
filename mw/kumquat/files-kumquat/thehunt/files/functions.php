<?php
function act_id_getter(){
    global $user_id;
    global $player_data;
    if($user_id > 0)
    {
        foreach($player_data as $key => $player)
        {
            if($user_id == $player['user_id']){ $act_player = $key + 1; break;}
        }
    }
    if(!isset($act_player)){ $act_player = 0; }
    return $act_player;
    }

function join_handler()
{ // This is used to allow players to join games. It may be called on either in the lobby or in the game section.
    // make sure the game exists and is open
    global $pdo;
    global $user_id;
    global $game_id;
    $local_id = "";
    $query = "SELECT * from thnt_games WHERE game_id = :game_id AND game_status = 'open'";
    $query = $pdo->prepare($query);
    $query->execute([
        ":game_id" => $game_id
    ]);
    $game_to_join = $query->fetch(PDO::FETCH_ASSOC);
    if(!empty($game_to_join)){
        // game exists and is open
        if(($game_to_join['private'] == 1 && !empty($_GET['game_code']) && $_GET['game_code'] == $game_to_join['access_code']) || $game_to_join['private'] == 0)
        {
            // game is either public, or you have the correct code
            $query = "SELECT * from thnt_players WHERE game_id = :game_id AND user_id = :user_id";
            $query = $pdo->prepare($query);
            $query->execute([
            ":game_id" => $game_id,
            ":user_id" => $user_id
            ]);
            $existing_player = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($existing_player))
            {
                $ip = $_SERVER['REMOTE_ADDR'];
                $query = "SELECT local_id from thnt_players WHERE game_id = ?";
                $query = $pdo->prepare($query);
                $query->execute([
                $game_id
                ]);
                $local_id_list = $query->fetchAll(PDO::FETCH_ASSOC);
                $i = 2;
                while($i > 0){ // potential infinite loop here if not broken
                if(in_array($i, $local_id_list)){$i += 1;} else {$local_id = $i; break;}
                }
                $query = $pdo->prepare("INSERT INTO `thnt_players` (`game_id`, `user_id`, `ip`, `local_id`, `move_order`) VALUES (:game_id, :user_id, :ip, :local_id, 0)");
                $query->execute([
                ":game_id" => $game_to_join['game_id'],
                ":user_id" => $user_id,
                ":ip" => $ip,
                ":local_id" => $local_id
                ]);
                return 1;
            } // player does exist
            return -1;
        } // game is either private, or you don't have the correct code
        return -2;
    } // game doesn't exist
    return -3;
}

function leave_handler()
{
    global $pdo;
    global $user_id;
    global $game_id;
    // echo "Leave to go...<br>";
    $query = "SELECT * from thnt_games WHERE game_id = :game_id";
    $query = $pdo->prepare($query);
    $query->execute([
    ":game_id" => $game_id
    ]);
    $game_to_leave = $query->fetch(PDO::FETCH_ASSOC);
    if(!empty($game_to_leave)){
        // echo "Selecting player right.<br>";
        $query = "SELECT * from thnt_players WHERE game_id = :game_id AND user_id = :user_id";
        $query = $pdo->prepare($query);
        $query->execute([
        ":game_id" => $game_id,
        ":user_id" => $user_id
        ]);
        $existing_player = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($existing_player))
        {
            // echo "Player exists...<br>";
            /* Now, there are several factors to consider. 
            If the game has started, then leaving shouldn't remove you from the game or even from the move order. Instead, it should register as a new move.
            If the game hasn't started, then leaving should remove you from the game altogether, unless you are the host. If you are, the game should be cancelled.
            If the game has finished, then it's too late to leave; ignore the request.
            */
            if($game_to_leave['game_status'] == "open")
            {
                if($existing_player['local_id'] == 1)
                {
                // echo "Nuking game.<br>";
                // You are the host, so nuke the game.
                $query = "DELETE from thnt_games WHERE game_id = :game_id";
                $query = $pdo->prepare($query);
                $query->execute([":game_id" => $game_to_leave['game_id']]);
                $query = "DELETE from thnt_players WHERE game_id = :game_id";
                $query = $pdo->prepare($query);
                $query->execute([":game_id" => $game_to_leave['game_id']]);
                return "host";
                }
                else
                {
                 //   echo "Leaving game.<br>";
                    // You aren't the host, so just delete your own entry.
                    $query = "DELETE from thnt_players WHERE game_id = :game_id AND local_id = :local_id";
                    $query = $pdo->prepare($query);
                    $query->execute([":game_id" => $game_to_leave['game_id'], ":local_id" => $existing_player['local_id']]);
                    return "guest";
                }
            }
            elseif($game_to_leave['game_status'] == "active")
            { // You can only leave a game within the game
                // echo "oh noes i just cant win its time to give up!!111<br>";
                // Rewrite this!!!

                /*
                $query = "SELECT `move_id` from `thnt_move_log` WHERE game_id = :game_id AND action = :action AND local_id = :local_id";
                $query = $pdo->prepare($query);
                $query->execute([
                ":game_id" => $game_to_leave['game_id'],
                ":action" => "resign",
                ":local_id" => $existing_player['local_id'],
                ]);
                $resigned_already = $query->fetch($PDO::FETCH_ASSOC);
                if(empty($resigned_already))
                {
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
                */
            } // end game active
        } // end isset existing player
    } // end not-empty "game to leave"
} // end leave block

function resign_handler($failed_capture = false)
{ // This is used for resignations and for failed captures
    // We're going to town with globals again
    // It seems easier to do this for now; time will tell whether we'll regret it
    global $pdo;
    global $player_data;
    global $game_id;
    global $act_player;
    global $next_move_in_log;
    global $resign;
    global $energy_return;
    global $end_game;
    $sth = $pdo->prepare("UPDATE `thnt_players` SET status = :status WHERE `game_id` = :game_id AND move_order = :move_order");
    $sth->execute([
        ':status' => "resigned",
        ':game_id' => $game_id,
        ':move_order' => $act_player
        ]);
    $player_data[$act_player-1]['status'] = "resigned";
    $resign = 1;

    // At this point, we need to check the player list and see who all has resigned. If it's everyone, then the game is over.
    $end_game = 0;
    while($end_game === 0)
    {
        foreach($player_data as $player)
        {
            if($player['status'] != "resigned")
            {
                break 2;
            }
        }
        // We made it through the whole loop; time to end the game.
        $sth = $pdo->prepare("UPDATE `thnt_games` SET game_status = :game_status, current_player = :current_player WHERE `game_id` = :game_id");
        $sth->execute([
            ':game_status' => "completed",
            ':current_player' => 0,
            ':game_id' => $game_id,
            ]);
            $end_game = 1;
    }
    if($end_game !== 1){ $energy_return = energyProcess(true); }
    if($failed_capture === false)
    {
        // This is only for resignations; captures have their own message
        $msg_player_name = say($player_data[$act_player-1]['username']);
        $message = "";
        $message .= "<b>{$msg_player_name}</b>";
        $message .= " has given up the search.<br>";
        $query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
        $query->execute([
        ":game_id" => $game_id,
        ":message" => $message,
        ":secret" => 0,
        ":recipient" => 0,
        ":move_id" => $next_move_in_log,
        ]);	
    }
    $next_move_in_log += 1;
    if(isset($end_game) && $end_game === 1)
    {
        $message = "All players are out of the hunt. The Mole wins!<br>";
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

function capture_handler(){
    // Look at all the globals!
    global $player_data;
    global $game_data;
    global $pdo;
    global $board_data;
    global $game_id;
    global $next_move_in_log;
    global $end_game;
    // Used to handle wins for both the button, and special card
    $end_game = 1;
    $end_message = "";
    $end_message_type = array
    (
        "After an exhaustive search, ",
        "Numerous security teams led by ",
        "In a climatic confrontation, ",
        "",
        ""
    );
    $end_message .= $end_message_type[random_int(0,count($end_message_type)-1)];
    $end_message .= "<b>";
    $end_message .= say($player_data[$game_data['current_player']-1]['username']);
    $end_message .= "</b> ";
    $end_message_type = array
    (
        "stormed a secret location and subdued the ",
        "apprehended the ",
        "arrested the ",
        "took into custody the ",
        "discovered and captured the " ,
    );
    $end_message .= $end_message_type[random_int(0,count($end_message_type)-1)];
    $end_message_type = array
    (
        "mysterious",
        "nefarious",
        "elusive",
        "scoundrelous",
        ""
    );
    $end_message .= $end_message_type[random_int(0,count($end_message_type)-1)];
    $end_message .= " Mole! The ";
    $end_message .= $board_data['locale'];
    $end_message_type = array
    (
        " is safe",
        " is secure",
        " is out of danger",
        " is safe and sound",
        " is at rest",
    );
    $end_message .= $end_message_type[random_int(0,count($end_message_type)-1)];
    $end_message .= "... for now!<br>";
    // Include the location that was investigated below
    $query = $pdo->prepare("INSERT INTO `thnt_messages` (`game_id`, `message`, `secret`, `recipient`, `move_id`) VALUES (:game_id, :message, :secret, :recipient, :move_id)");
    $query->execute([
    ":game_id" => $game_id,
    ":message" => $end_message,
    ":secret" => 0,
    ":recipient" => 0,
    ":move_id" => $next_move_in_log,
    ]);
    $next_move_in_log += 1;
    $sth = $pdo->prepare("UPDATE `thnt_games` SET current_player = :current_player, game_status = :game_status WHERE `game_id` = :game_id");
    $sth->execute([
        ':current_player' => "0",
        ':game_status' => "completed",
        ':game_id' => $game_id,
        ]);	
}


function GetLocs($location, $forbids){
global $Board;
$list = $Board[$location];
// var_see($list, "GetLocs -> List");
// var_see($forbids, "GetLocs -> Forbids");
if(is_array($list)){
$list = array_diff($list, $forbids);}
else if (in_array($list, $forbids)){return array();}
return $list;
}

function BuildPath($from, $main_array){
$beginning = -1;
$i = 20;
$path_stack = array($from);
while ($beginning == -1 && $i > 0){
$from = $main_array[$from]['From'];
array_push($path_stack, $from);
if($from == "Start"){
$beginning = 1;
}
$i -= 1;
}
$path_stack = array_reverse($path_stack);
// var_see($path_stack, "BuildPath -> Path stack");
return $path_stack;
}
    
function cr_shuffle($array){
$new_array = array();
$c = count($array);
while($c > 0){
$rand = random_int(0, $c);
$new_array[] = array_slice($array, $rand, 1, true);
array_splice($array, $rand, 1);
$c -= 1;
}
return $new_array;
}

function scan_include($file, $fallback, $path){
    // reads file in text folder and returns file + mtime
    if(isset($file) && is_readable("/{$path}/{$file}")){
    $dir = "/{$path}/{$file}";
    return "$dir";
    }
    $dir = "/{$path}/{$fallback}";
    return $dir;
    }

function refreshOrder($notstartgame = true)
{
    // Can be used as a 'hard reset' to get all new information after doing updates to the game's database entries
    global $pdo;
    global $game_data;
    global $player_data;
    global $game_messages;
    global $agents_array;
    global $bad_links;
    global $triggers;
    global $act_player;
    $query = $pdo->prepare("SELECT *, UNIX_TIMESTAMP(`updated`) as `timestamp` FROM thnt_games WHERE game_id = :game_id;
    SELECT thnt_players.*, home_accounts.username FROM thnt_players LEFT JOIN home_accounts ON (thnt_players.user_id = home_accounts.id) WHERE game_id = :game_id ORDER BY move_order, local_id;
    SELECT * from thnt_messages WHERE game_id = :game_id ORDER BY `move_id` DESC;");
    $query->execute(
        [
            ":game_id" => $game_data['game_id']
        ]
    );
    $game_data = $query->fetch(PDO::FETCH_ASSOC);
    $query->nextRowset();
    $player_data = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->nextRowset();
    $game_messages = $query->fetchAll(PDO::FETCH_ASSOC);

    // Player inventories
    foreach($player_data as $key => $value)
    {
    $player_data[$key]['inventory'] = json_decode($player_data[$key]['inventory'], true);
    }

    $agents_array = json_decode($game_data['agents'], true);
    if($notstartgame === true){ $bad_links = BadLinksBuilder($triggers); } else
    {
        $act_player = act_id_getter();
    }
    // end refresh
}

function energyProcess($end_turn = false){
	// I like global variables
	global $pdo;
	global $game_data;
	global $player_data;
	global $game_id;
    global $triggers;
    if($end_turn == true){$current_energy = 1; $total_energy = 1;}
    else
    {
    $current_energy = $game_data['current_energy'] % 2;
    $total_energy = $game_data['current_energy'];
    }

	if($current_energy === 1) // if number is odd
	{
        if($total_energy === 1) // if actual energy is 1, then this move is going to switch to the next player
        {
            $new_round = $game_data['round'] + 1;
            $new_player = $game_data['current_player'];
            while(true)
            {
                if(!isset($player_data[$new_player])){ $new_player = 0; continue;}
                if($player_data[$new_player]['status'] == "resigned"){ $new_player += 1; continue;}
                break;
            }
            $upload_player = $new_player + 1;
            // Update triggers here
            if(!empty($triggers))
            {
				$first = array_key_first($triggers);
                // var_see($first, "First");
                // var_see($triggers, "Triggers");
                while(!empty($triggers) && $triggers[$first]['player'] === $upload_player)
                {
                //    echo "Unsetting trigger...<br>";
                    unset($triggers[$first]);
                    $first = array_key_first($triggers);
                }
            }
                $new_triggers = json_encode($triggers, true);
                $sth = $pdo->prepare("UPDATE `thnt_games` SET round = :round, current_energy = :current_energy, current_player = :current_player, triggers = :triggers WHERE `game_id` = :game_id");
                $sth->execute([
                    ':round' => $new_round,
                    ':current_energy' => "2",
                    ':current_player' => $upload_player,
                    ':triggers' => $new_triggers,
                    ':game_id' => $game_id
                ]);
                return $upload_player;
        }
        else
        {
            $new_energy = $total_energy - 1;
            $sth = $pdo->prepare("UPDATE `thnt_games` SET current_energy = :current_energy WHERE `game_id` = :game_id");
            $sth->execute([
                ':current_energy' => $new_energy,
                ':game_id' => $game_id
            ]);
            return $game_data['current_player'];
        }
        // This lets the caller know that a new turn is beginning, so they can take appropriate action

	}
	else // if number is even
	{
        $new_energy = $total_energy - 1;
		$sth = $pdo->prepare("UPDATE `thnt_games` SET current_energy = :current_energy WHERE `game_id` = :game_id");
		$sth->execute([
			':current_energy' => $new_energy,
			':game_id' => $game_id
		]);
        // No new turn yet
        return false;
	}
}

function StartDeck()
{
    global $board_data;
    $card_array = json_decode($board_data['board_deck']);
    $card_deck = array();
    foreach($card_array as $key => $value)
    {
        for($i = $value; $i > 0; $i--) 
        {
            $card_deck[] = $key;
        }
    }
return $card_deck;
}

function UpdateInventory()
{
    global $pdo;
    global $player_data;
    global $game_id;
    global $game_data;
    global $act_player;
    $json_inventory = json_encode($player_data[$act_player-1]['inventory']);
    // var_see($json_inventory, "JSON inventory");
    $sth = $pdo->prepare("UPDATE `thnt_players` SET inventory = :inventory WHERE `game_id` = :game_id AND move_order = :move_order");
    $sth->execute([
        ':inventory' => $json_inventory,
        ':game_id' => $game_id,
        ':move_order' => $game_data['current_player']
        ]);	
}

function DrawCards($c)
{
    global $pdo;
    global $game_id;
    global $act_player;
    global $player_data;
    global $card_decks;
    
    // add check to make sure that there are sufficient cards in the deck - if there aren't, give some, shuffle the discards back in
    // if there still aren't, then give whatever is left and note that the deck was too small to give all the cards
    if(!isset($card_decks['main'])) { $card_deck_count = 0;} else {
    $card_deck_count = count($card_decks['main']); }
    $cards_drawn = 0;
    if($c > $card_deck_count)
    {
        /*
        We want to draw all the cards left in the deck, and THEN shuffle the discards back in, and then resume drawing.
        If there are no cards left in the deck, then skip the first draw, of course.
        After the first draw (skipped or otherwise), check how many cards are in.
        If they're equal to or greater than the requirement, then proceed as normal.
        If they're not, then the number of cards drawn should be lowered to the number available, and
        there needs to be a special message to indicate the player only drew the cards he could.
        */
        $b = $card_deck_count; // this will be used as a prelude to b
        $c -= $b; // the cards needed to be drawn after the b loop

        while($b > 0)
        {
            // the card drawing loop; this could be made into a function?
            $card = array_pop($card_decks['main']);
            if(isset($player_data[$act_player-1]['inventory']['cards'][$card]))
            {
                $player_data[$act_player-1]['inventory']['cards'][$card] += 1;
            }
            else
            {
                $player_data[$act_player-1]['inventory']['cards'][$card] = 1;
            }
            $cards_drawn += 1;
            $b -= 1;
        }
        // Now, the main deck should be empty.
        if(!empty($card_decks['discards']))
        {
            $card_decks['main'] = rand_shuffle($card_decks['discards']);
            $card_decks['discards'] = array();
        }
        // The main deck has been populated now, and discards are empty.
        $card_deck_count = count($card_decks['main']);
        if($c > $card_deck_count)
        {
            $c = $card_deck_count;
        }
    }

    while($c > 0)
    {
        $card = array_pop($card_decks['main']);
        if(isset($player_data[$act_player-1]['inventory']['cards'][$card]))
        {
            $player_data[$act_player-1]['inventory']['cards'][$card] += 1;
        }
        else
        {
            $player_data[$act_player-1]['inventory']['cards'][$card] = 1;
        }
        $cards_drawn += 1;
        $c -= 1;
    }
    $json_cards = json_encode($card_decks);
    $sth = $pdo->prepare("UPDATE `thnt_games` SET card_decks = :card_decks WHERE `game_id` = :game_id");
    $sth->execute(
    [
    ':card_decks' => $json_cards,
    ':game_id' => $game_id,
    ]);
    return $cards_drawn;
}

function PathFinder($player, $dest, $search = 1)
{
    /* This is one of the most important functions, because it is used to provide the shortest-distance numbers that the basic
    "Search for Mastermind" card uses, as well as the thing that is run at the end of every move and tells you how close you are to a local agent.
    The "Point to" card is similar and should probably be based on this.
    */
    /*
    There are three search modes.
    1 is the default. If it is active, then it is a standard "Search for Mastermind" which returns the distance, in territories, between you and the MM.
    2 is "Point to" and gives the territory adjacent to you. The difference is when the search object is found.
    3 is the "Global Jump" functionality, and attempts to return a random territory that is as far from the player as possible.
    The difference here is that "3" attempts not to find the search object at all. This is a bit of a hack.
    */

    // Is the destination the same as the location? If so, skip the search.
    $player = intval($player);
    if($player === $dest){ return 0;}
    else 
    {
        // "Forbids" in this case doesn't have anything to do with card powers, but rather not going to places that you've already visited.
        $forbids = array($player);
        $forbid_stack = array();
        $found = -1; // Used in the upcoming loop
        $found_array = array(); // Used to store the destination when we've found it
        $main_array = array(0 => array(
        // step number
        "Name" => $player, // name of state
        "Step" => 0,
        "Forbids" => $forbids,
        "Links" => GetLocs($player, $forbids),
        "From" => "Start",
        )
        );
//        var_see($main_array, "PathFinder -> Initial Main Array");
//        var_see($forbids, "PathFinder -> Initial Forbids");
        $forbids = array_merge($forbids, $main_array[0]['Links']);
        $i = 0;
        while($found == -1)
        { // continue loop until dest is found
            $counter = count($main_array);
            foreach($main_array as $key => $array)
            {
            if($array['Step'] == $i)
                { // only process items where step is the step we're on
                    if($array['Name'] == $dest){ // echo "TARGET ACQUIRED!!!"; 
                        $found_array[] = $array;} else if(empty($found_array))
                    {
                        foreach($array['Links'] as $Link)
                        {
                            $temp_forbids = $forbids; $temp_forbids[] = $Link;
                            $main_array[] = array(
                            "Name" => $Link,
                            "Step" => $i+1,
                            "Forbids" => $temp_forbids,
                            "Links" => GetLocs($Link, $temp_forbids),
                            "From" => $key,
                                );
                        }
                        $forbid_stack = array_merge($forbid_stack, $temp_forbids);
                    }
                }
            }
            $forbids = array_merge($forbids, $forbid_stack);
            $forbids = array_unique($forbids);
            $i += 1;
//            var_see($i, "PathFinder -> Step $i");
//            var_see($main_array, "PathFinder -> Main Array");
//            var_see($forbids, "PathFinder -> Forbids");
            if(!empty($found_array))
            {
//                 echo "PathFinder -> Targets found.<br><br><br>";
//                 var_see($found_array, "PathFinder -> Found Array");
                $paths = array();
                foreach($found_array as $possible_path){
                array_push($paths, BuildPath($possible_path['From'], $main_array));
                }
//              var_see($paths, "PathFinder -> Paths");
               if($search == 1){
                return (count($paths[0]) - 1); }
                else 
                { // "Point to" functionality; a bit hacky but should work
                    $found_count = count($paths);
                    if($found_count > 1)
                    { // Multiple paths; pick one at random
                        $found_count -= 1;
                        $rand_int = random_int(0, $found_count);
                    }
                    else {$rand_int = 0; }
                    next($paths[$rand_int]); // This advances from "Start" to the one the player starts on
                    $current = current($paths[$rand_int]);
//                    var_see($current, "PathFinder -> Current");
                    if($current === $dest)
                    {   // The current territory is it.
                        return 0;
                    }
                    else 
                    {
                        next($paths[$rand_int]); // This should be the territory being pointed to.
                        $point = current($paths[$rand_int]);
//                        var_see($point, "Point");
                        if($point === false)
                        {
                            return $dest;
                        }
                        else 
                        {
                        return $main_array[$point]['Name'];
                        }
                    }
                }
                    $found = 1;
            }
            else
            // it should always be possible to eventually find the destination, but just in case...
            if(count($main_array) == $counter)
            { 
//                var_see($forbids, "PathFinder -> Closing Forbids Array");
                return -1; 
            }
        }
    }
}

function Glob_Calculator($player, $territory_count)
{
    /* This is a child of the PathFinder. It is intended to get the shortest distance to each territory, then return the longest one.
    This seems preferable to repeatedly using the PathFinder routine on every single territory in the game.
    Whether it is better than a 'distance table', we shall see.
    */
        // "Forbids" in this case doesn't have anything to do with card powers, but rather not going to places that you've already visited.
        $forbids = array($player);
        $main_array = array(0 => array(
        // step number
        "Name" => $player, // name of state
        "Step" => 0,
        "Forbids" => $forbids,
        "Links" => GetLocs($player, $forbids),
        "From" => "Start",
        )
        );
        $forbids = array_merge($forbids, $main_array[0]['Links']);
        $i = 0;
        $forbids_count = count($forbids);
        $old_forbids = array();
        while($forbids_count < $territory_count)
        { // continue loop until dest is found
            foreach($main_array as $key => $array)
            {
                if($array['Step'] == $i)
                { // only process items where step is the step we're on
                    foreach($array['Links'] as $Link)
                    {
                        $old_forbids[] = array($Link => $i + 1);
                        $forbids[] = $Link;
                        $main_array[] = array(
                        "Name" => $Link,
                        "Step" => $i+1,
                        "Forbids" => $forbids,
                        "Links" => GetLocs($Link, $forbids),
                        "From" => $key,
                            );
                    }
                    $forbids = array_unique($forbids);
                }
            }
            $forbids_count = count($forbids);
            $i += 1;
        }
        $new_forbids = array();
        foreach($old_forbids as $LinkStep)
        {
            $key = key($LinkStep);
            if(!isset($new_forbids[$key]))
            {
            $new_forbids[$key] = $LinkStep;
            }
        }
        $final_array = array();
        $new_forbids = array_reverse($new_forbids, true);
        $final_step = array_key_first($new_forbids);
        $final_step = $new_forbids[$final_step][$final_step];
        foreach($new_forbids as $key => $value)
        {
            if($value[$key] === $final_step){$final_array[] = $key;}
            else {break;}
        }
        $count = count($final_array);
        if($count === 1){$final_number = $final_array[0];}
        else
        {
        $final_number = random_int(0, $count-1);
        $final_number = $final_array[$final_number];
        }
        return $final_number;
}

function BadLinksBuilder($triggers)
{
    global $Territories;
    global $player_data;
    global $act_player;
    $bad_links = array();
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
                    if(($trigger_link[0] === $current_link['dest'] 
                    && $trigger_link[1] === $Territories[$player_data[$act_player-1]['location']]['territory_id']) 
                    || ($trigger_link[1] === $current_link['dest'] 
                    && $trigger_link[0] === $Territories[$player_data[$act_player-1]['location']]['territory_id']))
                    { 
                        $bad_links[$current_link['dest']] = $current_link['dest']; break;
                    }
                }
            }
        }
    }
    return $bad_links;
}

function PathFinder2($player, $dest, $type = "distance"){
    global $Territories;
    // Mark all nodes unvisited and create an unvisited set
    // $player, $dest

    /*
    To facilitate sorting, we're going to use twin 'tables'

    several arrays:
    1: $Territories, of course, from which we get the IDs and links
    2: unvisited_set - contains all unvisited territory IDs, with keys sorted in numeric order; 
    we're looking for the values here
    3: territory values - contains all unvisited territory IDs and values

    */
    // var_dump($player);
    // var_dump($dest);
    if($player == $dest){return 0;}
    $unvisited_set = array();
    foreach($Territories as $territory)
    {
        $unvisited_set[$territory['territory_id']] = 99;
    }
    $territory_prevs = array();
    $current = $player;
    $current_val = 0;

    $finished = 0;
    $found = 0;
    $step = 0;
    while($finished === 0)
    {
        $p = 1;
        foreach($Territories[$current]['links'] as $link)
        {
        if($link['dest'] === $dest){$found = 1; /* echo "Found set.<br>"; */}
            if(isset($unvisited_set[$link['dest']]) && $current_val + 1 <= $unvisited_set[$link['dest']])
            {
                $unvisited_set[$link['dest']] = $current_val + 1;
                $territory_prevs[$link['dest']][] = $current;
            }
            $p += 1;
//            echo "Territory Prevs dests of {$link['dest']}: ", var_dump($territory_prevs[$link['dest']]), "<br>";
        }
        $previous = $current;
        $previous_val = $unvisited_set[$current];
        unset($unvisited_set[$current]);
        asort($unvisited_set);
        reset($unvisited_set);

        $current = key($unvisited_set);
        $current_val = current($unvisited_set);
        if($current_val > $previous_val || $previous === $dest && empty($unvisited_set)) 
        // the second clause is to deal with the case that the territory is literally as far away as you can get
        { $step += 1; // var_see($step, "Escalating step");
            if($found === 1)
            {
                // echo "Found!<br>";
                $finished = 1;
            }
        }
        if($previous === $player && $found === 1)
        { // this is to deal with finding it on the first round
            // echo "Found!<br>";
            $finished = 1;
        }
        if(empty($unvisited_set) && $finished === 0){$finished = -1;}
        // echo "<hr>";
    }
    if($finished === 1)
    {
        if($type === "distance")
        {
            return $step + 1; // because the results come from before the step is incremented
        }
        elseif ($type === "point")
        {   // First, make sure you're not one link away
            $select = $territory_prevs[$dest];
            if($select[0] === $player){ return $dest;}
            while(true)
            {
                $rand_val = count($select);
                $previous_link = $select;
                $rand_val = random_int(0, $rand_val - 1);
                $select = $territory_prevs[$select[$rand_val]];
                if($select[0] === $player){$previous_link = $previous_link[$rand_val]; return $previous_link;}
            }
            return 99;
        }
    }
    else { return 99; }
}

function AngleFinder($x1, $y1, $x2, $y2, $degree = true)
{
/* 
A trigonometric function that takes two points and calculates the angle of the line between them.
Thanks to Christ Jesus for helping me to understand enough of it to hack it together.
Math.net was a valuable resource.

This is expected to be useful for cards that make calculations based on compass directions.

This consists of several steps.
1. Get the horizontal and vertical distance between points
*/
    $x = ($x1 - $x2);
    $y = ($y1 - $y2);
    if(abs($x) >= abs($y))
    {
        // "x is equal to or greater than y."
        $o = $x; $a = $y;
    } 
    else 
    {
        $a = $x; $o = $y;
        // "y is equal to or greater than x."
    }
    // 2. Now, determine the length of the hypotenuse
    $h = sqrt($a * $a + $o * $o);
    if($degree === false)
    { // If you just want the hypotenuse without the degree
        return $h;
    }
    // 3. Now calculate cos based on adjacent and hypotenuse
    $c = $a / $h;
    $result = acos($c);
    $resultdegs = rad2deg($result);
    /*
    Unfortunately, we're not finished yet.
    We have to make modifications based on what direction things are relative to each other.
    Higher x is east, lower x is west; higher y is south, lower y is north
    If $x is positive and $y is positive, you are going southeast.
    If $x is negative and $y is positive, you are going southwest.
    If $x is positive and $y is negative, you are going northeast.
    If $x is negative and $y is negative, you are going northwest.
    But it also depends on which is adjacent and which is opposed, $x or $y.

    x>y, +, +: r + 90 √
    x>y, +, -: r + 90 √
    x>y, -, + 90 - r √
    x>y, -, - r - 90, 360 - r √
    y>x, +, + 180 - r
    y>x, +, - 180 + r
    y>x, -, + 180 - r
    y>x, -, - 180 + r
    */
    if($x == $a) // if x is the adjacent - that is, if y is greater than x
    {
        if($y < 0)
        { 
            $modresult = 180 + $resultdegs;
        //    echo "First mod: $modresult<br>";
        }
        else 
        { 
            $modresult = 180 - $resultdegs;
        //    echo "Second mod: $modresult<br>";
        }
    }
    else
    {
        if($x > 0)
        { 
            $modresult = $resultdegs + 90;
        //    echo "Third mod: $modresult<br>";
        }
        elseif($y < 0)
        {
            $modresult = 90 - $resultdegs;
        //    echo "Fourth mod: $modresult<br>";
        }
        else 
        { 
            $modresult = 360 - ($resultdegs - 90);
        //    echo "Fifth mod: $modresult<br>";
        }
    }
    if($modresult > 360) { $modresult = $modresult - 360;}
    if($modresult < 0) 
    { 
        $modresult = 360 + $modresult;
    }
    /*
    echo "<br>";
    echo "<u>Result:</u><br>";
    echo "The unaltered result is <b>$resultdegs</b>.<br>";
    echo "The modified result is <b>$modresult</b>.<br>";
    */
    return $modresult;
}



?>
