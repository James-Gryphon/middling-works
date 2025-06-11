<?php
function act_id_getter()
{
    global $user_id;
    global $match_data;
    $act_player = 0;
    if($user_id > 0)
    {
        if($user_id == $match_data['host'])
        {
            $act_player = $match_data['host'];    
        }
            elseif($user_id == $match_data['guest'])
        {
            $act_player = $match_data['guest'];
        }
    return $act_player;
    }
}

function join_handler()
{ // This is used to allow players to join games. It may be called on either in the lobby or in the game section.
    // make sure the game exists and is open
    // Hevly adapted from The Hunt's version. In particular, the accommodations for mult players are gone.
    global $pdo;
    global $user_id;
    global $match_id;
    $local_id = "";
    $query = "SELECT * from ult_matches WHERE match_id = :match_id AND status = 'open' AND `guest` IS NULL";
    $query = $pdo->prepare($query);
    $query->execute([
        ":match_id" => $match_id
    ]);
    $game_to_join = $query->fetch(PDO::FETCH_ASSOC);
    if(!empty($game_to_join))
    {
        // game exists and is open
        if(($game_to_join['private'] == 1 && !empty($_GET['match_code']) && $_GET['match_code'] == $game_to_join['access_code']) || $game_to_join['private'] == 0)
        {
            // game is either public, or you have the correct code
            $sth = $pdo->prepare("UPDATE `ult_matches` SET guest = :guest WHERE `match_id` = :match_id");
            $sth->execute([
            ':guest' => $user_id,
            ':match_id' => $match_id
            ]);
            exit(header('Location: index.php?s=ult&a=match&match=$match_id'));
        }
     }
}

function invite_handler()
{ // This is based on the join handler
    // make sure the game exists and is open
    global $pdo;
    global $user_id;
    global $invite_id;
    $local_id = "";
    $query = "SELECT * from ult_invites WHERE match_id = :match_id AND status = 'open' AND `guest` = :user_id";
    $query = $pdo->prepare($query);
    $query->execute([
        ":match_id" => $invite_id,
        ":user_id" => $user_id
    ]);
    $game_to_join = $query->fetch(PDO::FETCH_ASSOC);
    if(!empty($game_to_join))
    {
        // game exists and is open
            $sth = $pdo->prepare("INSERT INTO `ult_matches` 
            SELECT
            `match_name`, `event`, `host`, `guest`, `created`, `match_length`,
            `private`, `access_code`, `mover`, `alternating`, `ot`, `clock`,
            `rules`, `opening`, `start_status`, `outcome`
             FROM `ult_invites` WHERE `match_id` = :match_id");
            $sth->execute([
            ':match_id' => $invite_id
            ]);
            $new_match_id = $pdo->lastInsertId();
            $sth = $pdo->prepare("DELETE FROM `ult_invites` WHERE `match_id` = :match_id");
            $sth->execute([
            ':match_id' => $invite_id
            ]);
            $new_games_array = [];
            $new_games_array['mover'] = $game_to_join['mover'];
            $new_games_array['match_length'] = $game_to_join['match_length'];
            $new_games_array['match_id'] = $new_match_id;        
            game_builder($new_games_array); // game_builder includes an exit, at least for now
            exit(header('Location: index.php?s=ult&a=match&match=$new_match_id'));
     }
}


function leave_handler()
{ // Based only loosely on the TH version
  // This is not the same as resigning (which is handled by the resign_handler)
    global $pdo;
    global $user_id;
    global $match_id;
    // echo "Leave to go...<br>";
    $query = "SELECT `host`, `guest` from ult_matches WHERE match_id = :match_id AND status = 'open'";
    $query = $pdo->prepare($query);
    $query->execute([
        ":match_id" => $match_id
    ]);
    $leave_info = $query->fetch(PDO::FETCH_ASSOC);
    if($user_id == $leave_info['host'])
    { // Nuke the game
        $query = "DELETE from ult_matches WHERE match_id = :match_id";
        $query = $pdo->prepare($query);
        $query->execute([":match_id" => $match_id]);
        $query = "DELETE from ult_games WHERE match_id = :match_id";
        $query = $pdo->prepare($query);
        $query->execute([":match_id" => $match_id]);
        return "host";
    }
    elseif($user_id == $leave_info['guest'])
    {
        $query = "UPDATE ult_matches SET guest='' WHERE match_id=:match_id";
        $sth = $pdo->prepare($query);
        $query->execute([":match_id" => $match_id]);
        $query = "UPDATE ult_games SET guest_start='' WHERE match_id=:match_id";
        $sth = $pdo->prepare($query);
        $query->execute([":match_id" => $match_id]);

    }
    // else - Nothing should work here
} // end leave block

function cancel_handler()
{ // A variant of leave_handler that's used for invites only
    global $pdo;
    global $user_id;
    global $invite_id;
    // echo "Leave to go...<br>";
    $query = "SELECT `host`, `guest` from ult_invites WHERE match_id = :match_id";
    $query = $pdo->prepare($query);
    $query->execute([
        ":match_id" => $invite_id
    ]);
    $leave_info = $query->fetch(PDO::FETCH_ASSOC);
    if($user_id === $leave_info['host'] || $user_id === $leave_info['guest'])
    { // Nuke the match
        $query = "DELETE from ult_invites WHERE match_id = :match_id";
        $query = $pdo->prepare($query);
        $query->execute([":match_id" => $invite_id]);
    }
    // else - Nothing should work here
} // end leave block

function game_builder($match_array = [], $invite = false)
{ // This is used to add games to a match after it is initialized - or after an invite is turned into a match
    global $pdo;
    $values_string = "";
    if($invite === true)
    {
        $query = "SELECT `mover`, `match_length` from ult_invites WHERE match_id = :match_id";
        $query = $pdo->prepare($query);
        $query->execute([
            ":match_id" => $match_id
        ]);
        $match_array = $query->fetch(PDO::FETCH_ASSOC);    
    }
    if(!empty($match_array['match_length']))
    {
        for($total_games = 1; $total_games <= $match_array['match_length']; $total_games++)
        {
            if($match_array['mover'] === "standard")
            {
                if($match_array['match_length'] === 1){$leader = "random";}
                else
                {
                    if($total_games % 2 === 0)
                    {
                        $leader = "host";
                    }
                    else {$leader = "guest";}
                }
            }
            else
            {
                $leader = match($match_array['mover'])
                {
                    "host" => "host",
                    "guest" => "guest",
                    "random" => "random",
                    default => "random", 
                };
            }
            $values_string .= "($total_games,{$match_array['match_id']},\"$leader\"),";
        }
        $values_string = substr($values_string, 0, -1); // I benchmarked Reza Lavarian's samples here and substr was best
        $query = $pdo->query( // replace into won't work because it deletes the entry
            "INSERT INTO `ult_games` (`game_id`, `match_id`, `first_mover`) VALUES $values_string"
        );
    }
    exit(header("Location: index.php?s=ult&a=match&match={$match_array['match_id']}"));
}

function resign_handler($failed_capture = false)
{ // This is inherited from TH and isn't updated at all; it will need to be if it will be used
    // This is used for resignations and for failed captures
    // We're going to town with globals again
    // It seems easier to do this for now; time will tell whether we'll regret it
    global $pdo;
    global $user_id;
    global $match_id;
    /*
     * Two cases for resignations:
     * Mid-game, and between games (or pre-game)
     * If mid-game, needs to resign the match, and the game
     * If not, then just the match
     */
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

function RulesDisplay($match_array)
{ // For the lobby
	$string = "";
	switch($match_array['mover'])
	{
		case "standard": $string .= ""; break;
		case "random": $string .= "Random first"; break;
		case "host": $string .= "Host first"; break;
		default: $string .= "Guest first";
	}
	if(isset($match_array['clock']))
	{
		$temp = "No clock";
		if($string != "")
		{
			$string .= ", ";
			$temp = "no clock";
		}
		if($match_array['clock'] == "30min"){$string .= "30 min/each";}
		elseif($match_array['clock'] == "10min"){$string .= "10 min/each";}
		else { $string .= $temp;}
	}

		switch($match_array['ot'])
	{
		case "1": $string .= ", OT"; break;
		default: ;
	}

    switch($match_array['private'])
    {
        case "0": $string .= ", public"; break;
        default: ;
    }
	return $string;
}

function FirstMoverFinder($game_id, $players, $match_data)
{ // based on code in match
    		if($match_data['mover'] == "host")
		{
			$first_mover = true;
		}
		else
		{
			$first_mover = false;
		}
		if($game_id % 2 == 0 && $match_data['alternating'] == 1){$first_mover = !$first_mover;}
                if($first_mover === true){return $players[0];}
                else {return $players[1];}
}

function ColorChooser($testarea, $testvalue, $testvalue2, $valid = false)
{ // They claim you shouldn't have globals and that you shouldn't have more than one or two vars in a function
  // Well, we're about to break both of those, in a massive way\
  global $you;
  global $players;
  global $neutralcolors; // this might be replaceable if SESSION drives it
  global $first_mover;
  global $debug;
  $debug['first_mover'] = $first_mover;
  $debug['you'] = $you;
  $debug['testarea'] = $testarea;
  $debug['testvalue'] = $testvalue;
  $debug['testvalue2'] = $testvalue2;
  if($valid == true && in_array($you, $players))
  {
      		if(($first_mover == $you && $testarea == $testvalue) ||
		($testarea == $testvalue2 && $first_mover != $you))
		{
			return "green";
		}
  }
	if($neutralcolors == false && in_array($you, $players)) // a special case so players have white squares when they're moving
	{
            return "red";
	}
	else
	{
		if(($first_mover == $players[0] && $testarea == $testvalue) ||
		($testarea == $testvalue2 && $first_mover == $players[1]))
		{
			return "blue";
		}
		else {return "yellow";}
	}
}

function ScoreFinder($match_id, $game = 99)
{
    /* This is needed in various pages, so it seemed to make sense to make a single function to manage it.
     * This retrieves the score for a certain match, up to the game ID listed. The default is to load them all.
    */
}

function ActionExporter($move_data)
{
    $action_string = "";
    foreach($move_data as $key => $value)
    {
        if($value['piece'] === 'X')
        {
          $action_string .= "x";
        }
        elseif($value['piece'] === 'O')
        {
            $action_string .= "o";
        }
/* 
0 = 7,7
80 = 3,3

7 - 6 = 1
8 - 6 = 2
9 = 3

1 - 6 = -5 + 12 = 7
2 - 6 = -4 + 12 = 8
3 - 6 = -3 + 12 = 9

4 - 6 = -2 + 12 = 10 - 6

so, 55 = 5 * 9 + 5 = 49
But it *should* be 40

So...

5, 7
5, 5


*/
        // This will make a board that is mathematically equivalent to the game position, but it'll be rotated, which
        // can only have the effect of confusing players
        // $board_mod = ($move_data['board'] - 1) * 9;
        $split_move = str_split($value['move']);
        if(is_numeric($split_move[0]))
        {
        $board = match (true)
        {
            $split_move[0] > 6 => $split_move[0] - 6,
            $split_move[0] > 3 => $split_move[0],
            $split_move[0] > 0 => $split_move[0] + 6,
            default => 0,
        };
        $cell = match (true) 
        {
            $split_move[1] > 6 => $split_move[1] - 6,
            $split_move[1] > 3 => $split_move[1],
            $split_move[1] > 0 => $split_move[1] + 6,
            default => 0,
        };
        $fin_move = ($board - 1) * 9 + $cell - 1;
        $action_string .= $fin_move;
    }
    }
    return $action_string;
}

function WCBoardDrawn($s_board_array)
{

    foreach($s_board_array[10] as $key => $value)
    {
        if($value === "X" || $value === "O"){continue;}
        $res = IsBoardDrawn($s_board_array[$key]);
        if($res === 8){$s_board_array[10][$key] = "Q";}
    }
    $result = FinaleIsBoardDrawn($s_board_array[10]);
    return $result;
}

function IsBoardDrawn($array)
{ // Checks a board for drawn lines, and returns yes if so
$draws = 0;
if (($array[1] === "X" && ($array[5] === "O" || $array[9] === "O")) ||
	($array[1] === "O" && ($array[5] === "X" || $array[9] === "X")) ||
(($array[5] === "X" && $array[9] === "O") || ($array[5] === "O" && $array[9] === "X")))
{
	$draws = 1;
} else {return $draws;}

if (($array[1] === "X" && ($array[2] === "O" || $array[3] === "O")) ||
	($array[1] === "O" && ($array[2] === "X" || $array[3] === "X")) ||
(($array[2] === "X" && $array[3] === "O") || ($array[2] === "O" && $array[3] === "X")))
{
	$draws = 2;
} else {return $draws;}

if (($array[1] === "X" && ($array[4] === "O" || $array[7] === "O")) ||
	($array[1] === "O" && ($array[4] === "X" || $array[7] === "X")) ||
(($array[4] === "X" && $array[7] === "O") || ($array[4] === "O" && $array[7] === "X")))
{
	$draws = 3;
} else {return $draws;}

if (($array[2] === "X" && ($array[5] === "O" || $array[8] === "O")) ||
	($array[2] === "O" && ($array[5] === "X" || $array[8] === "X")) ||
(($array[5] === "X" && $array[8] === "O") || ($array[5] === "O" && $array[8] === "X")))
{
	$draws = 4;
} else {return $draws;}

if (($array[3] === "X" && ($array[6] === "O" || $array[9] === "O")) ||
	($array[3] === "O" && ($array[6] === "X" || $array[9] === "X")) ||
(($array[6] === "X" && $array[9] === "O") || ($array[6] === "O" && $array[9] === "X")))
{
	$draws = 5;
} else {return $draws;}

if (($array[4] === "X" && ($array[5] === "O" || $array[6] === "O")) ||
	($array[4] === "O" && ($array[5] === "X" || $array[6] === "X")) ||
(($array[5] === "X" && $array[6] === "O") || ($array[5] === "O" && $array[6] === "X")))
{
	$draws = 6;
} else {return $draws;}

if (($array[7] === "X" && ($array[8] === "O" || $array[9] === "O")) ||
	($array[7] === "O" && ($array[8] === "X" || $array[9] === "X")) ||
(($array[8] === "X" && $array[9] === "O") || ($array[8] === "O" && $array[9] === "X")))
{
	$draws = 7;
} else {return $draws;}

if (($array[3] === "X" && ($array[5] === "O" || $array[7] === "O")) ||
	($array[3] === "O" && ($array[5] === "X" || $array[7] === "X")) ||
(($array[5] === "X" && $array[7] === "O") || ($array[5] === "O" && $array[7] === "X")))
{
	$draws = 8;
} else {return $draws;}
return $draws;
}

function FinaleIsBoardDrawn($array)
{ // Checks a board for drawn lines, and returns yes if so
$draws = 0;
if(($array[1] === "Q" || $array[5] === "Q" || $array[9] === "Q") ||
    (
        ($array[1] === "X" && ($array[5] === "O" || $array[9] === "O")) ||
	    ($array[1] === "O" && ($array[5] === "X" || $array[9] === "X")) ||
        (
            ($array[5] === "X" && $array[9] === "O") || ($array[5] === "O" && $array[9] === "X")
        )
    )
)
{
	$draws = 1;
} else {return $draws;}

if(($array[1] === "Q" || $array[2] === "Q" || $array[3] === "Q") ||
    (
        ($array[1] === "X" && ($array[2] === "O" || $array[3] === "O")) ||
	    ($array[1] === "O" && ($array[2] === "X" || $array[3] === "X")) ||
        (
            ($array[2] === "X" && $array[3] === "O") || ($array[2] === "O" && $array[3] === "X")
        )
    )
)
{
	$draws = 2;
} else {return $draws;}

if(($array[1] === "Q" || $array[4] === "Q" || $array[7] === "Q") ||
    (
        ($array[1] === "X" && ($array[4] === "O" || $array[7] === "O")) ||
	    ($array[1] === "O" && ($array[4] === "X" || $array[7] === "X")) ||
        (
            ($array[4] === "X" && $array[7] === "O") || ($array[4] === "O" && $array[7] === "X")
        )
    )
)
{
	$draws = 3;
} else {return $draws;}

if(($array[2] === "Q" || $array[5] === "Q" || $array[8] === "Q") ||
    (
        ($array[2] === "X" && ($array[5] === "O" || $array[8] === "O")) ||
	    ($array[2] === "O" && ($array[5] === "X" || $array[8] === "X")) ||
        (
            ($array[5] === "X" && $array[8] === "O") || ($array[5] === "O" && $array[8] === "X")
        )
    )
)
{
	$draws = 4;
} else {return $draws;}

if(($array[3] === "Q" || $array[6] === "Q" || $array[9] === "Q") ||
    (
        ($array[3] === "X" && ($array[6] === "O" || $array[9] === "O")) ||
	    ($array[3] === "O" && ($array[6] === "X" || $array[9] === "X")) ||
        (
            ($array[6] === "X" && $array[9] === "O") || ($array[6] === "O" && $array[9] === "X")
        )
    )
)
{
	$draws = 5;
} else {return $draws;}

if(($array[4] === "Q" || $array[5] === "Q" || $array[6] === "Q") ||
    (
        ($array[4] === "X" && ($array[5] === "O" || $array[6] === "O")) ||
	    ($array[4] === "O" && ($array[5] === "X" || $array[6] === "X")) ||
        (
            ($array[5] === "X" && $array[6] === "O") || ($array[5] === "O" && $array[6] === "X")
        )
    )
)
{
	$draws = 6;
} else {return $draws;}

if(($array[7] === "Q" || $array[8] === "Q" || $array[9] === "Q") ||
    (
        ($array[7] === "X" && ($array[8] === "O" || $array[9] === "O")) ||
	    ($array[7] === "O" && ($array[8] === "X" || $array[9] === "X")) ||
        (
            ($array[8] === "X" && $array[9] === "O") || ($array[8] === "O" && $array[9] === "X")
        )
    )
)
{
	$draws = 7;
} else {return $draws;}
if(($array[3] === "Q" || $array[5] === "Q" || $array[7] === "Q") ||

    (
        ($array[3] === "X" && ($array[5] === "O" || $array[7] === "O")) ||
	    ($array[3] === "O" && ($array[5] === "X" || $array[7] === "X")) ||
        (
            ($array[5] === "X" && $array[7] === "O") || ($array[5] === "O" && $array[7] === "X")
        )
    )
)
{
	$draws = 8;
} else {return $draws;}
return $draws;
}

function WCBoardWon($s_board_array)
{ // Checks a single board for a win, and returns yes if so - simple and efficient, but only for 3x3 ULT (and TTT).
if(!empty($s_board_array[1]) && $s_board_array[1] === $s_board_array[5] && $s_board_array[1] === $s_board_array[9])
{return $s_board_array[1];} // bottom-left, diag
if(!empty($s_board_array[3]) && $s_board_array[3] === $s_board_array[5] && $s_board_array[3] === $s_board_array[7])
{return $s_board_array[3];} // bottom-right, diag
if(!empty($s_board_array[2]) && $s_board_array[2] === $s_board_array[5] && $s_board_array[2] === $s_board_array[8])
{return $s_board_array[2];} // bottom-mid, vert
if(!empty($s_board_array[4]) && $s_board_array[4] === $s_board_array[5] && $s_board_array[4] === $s_board_array[6])
{return $s_board_array[4];} // center-left, horz
if(!empty($s_board_array[7]) && $s_board_array[7] === $s_board_array[8] && $s_board_array[7] === $s_board_array[9])
{return $s_board_array[7];} // top-left, horz
if(!empty($s_board_array[1]) && $s_board_array[1] === $s_board_array[4] && $s_board_array[1] === $s_board_array[7])
{return $s_board_array[1];} // bottom-left, vert
if(!empty($s_board_array[1]) && $s_board_array[1] === $s_board_array[2] && $s_board_array[1] === $s_board_array[3])
{return $s_board_array[1];} // bottom-left, horz
if(!empty($s_board_array[3]) && $s_board_array[3] === $s_board_array[6] && $s_board_array[3] === $s_board_array[9])
{return $s_board_array[3];} // bottom-right, vert
return "";
}

function BoardWon() // This was intended to be used for checking won boards, but postponed for the hardcoded method
{ // I love globals
    global $board_array;
    global $last_move;
/* Broadly speaking, there seem to be two means of determining ULT wins.
 * 1) Check squares adjacent to the last move square for a 3r. This makes it generally usable
 * for other n-row games.
 * 2) Hardcode a set number of win conditions. We know there are 8 possible wins in ULT, so check all
 * of them, or at least the ones that are relevant to the current move.
 * The latter is potentially more efficient in terms of performance, and it is less complicated to code.
 * I've successfully done it several times before.
 * The former is more flexible for the long run. It can be used for things besides 3r ULT.
 */
    /*
     * Suppose the cell is 54.
     * So, the cells we want to check are 51,54,57, and 54,55,56.
     * If it's 55, then 54,55,56, 52,55,58, 53,55,57, and 51,55,59.
     * horz - always 1
     * left-diag: 3, +1 for each extra column (from 2x2)
     * vert: 2, +1 for each extra column
     * right-diag: 1, +1 for each extra column
     * for 3x3 ULT, the values are 1, 4, 3, 2
     */
    // horz check - need to get the values starting at, say, 101, and before 104
    $temp = ($last_move['cell'] + 1) % 3; // 3 is assumed to be good for 3x3; larger columns need more?
    $first_in_row = $last_move['cell'] - $temp;
    $next_row = $first_in_row + 3; // again, 3 is based on the row columns
    $cell = $first_in_row;
    
    $check_string = "";
    
    while($cell < $next_row)
    {
        $check_string .= $board_array[$last_move['board']][$cell];
        ++$cell;
    }
    $var = strstr($check_string, $last_move['piece']);
    return $var;    
}

function time_stringer($new_move_id, $fin_move, $move_time)
{
    global $debug;
        $debug['time_string'] = $move_time;
        $proc_days = gmp_div_qr($move_time, 86400);
        $proc_hours = gmp_div_qr($proc_days[1], 3600);
        $proc_minutes = gmp_div_qr($proc_hours[1], 60);
        $proc_seconds = gmp_div_qr($proc_minutes[1], 1);
        $time_string = "";
        if($proc_days[0] > 0){ $time_string .= "{$proc_days[0]}d ";}
        if($proc_hours[0] > 0){ $time_string .= "{$proc_hours[0]}h ";}
        if($proc_minutes[0] > 0){ $time_string .= "{$proc_minutes[0]}m ";}
        if($proc_seconds[0] > 0){ $time_string .= "{$proc_seconds[0]}s";}
        return $time_string;
}

function lastMoveBuilder()
{
    global $move_data;
    global $first_mover_role;
    global $match_data;
    global $game_data;

    if(!empty($move_data))
{ // Check if any moves
    $last_move_key = array_key_last($move_data);
    if($move_data[$last_move_key]['move'] == "--" || $move_data[$last_move_key]['move'] == "=")
    { // For resigns and draws, use the last move. If they have index 0, this will make a blank board!
        $last_move_key -= 1;
    }
}
else
{
    $last_move_key = -1;
}
if($last_move_key > -1)
{
    $last_move = $move_data[$last_move_key];
    $temp = str_split($last_move['move']);
    $last_move['board'] = $temp[0];
    $last_move['cell'] = $temp[1];
}
else 
{
    $last_move = ["board" => "", "cell" => "", "piece" => "O", "move_id" => 0, "performed" => $game_data['host_start'], "move_time" => $game_data['host_time']];
    if($first_mover_role == $match_data['host'])
    {
        $last_move['player'] = $match_data['guest'];
    }
    else
    {
        $last_move['player'] = $match_data['host'];
    }
}
return $last_move;
}

function validMoveBuilder()
{
    global $valid_moves;
    global $board_array;
    global $last_move;
    if (empty($last_move['board']) || (!empty($last_move['board']) && !empty($board_array[10][$last_move['cell']]))) // everything is valid
    {
        $valid_moves = $board_array;
    }
    else 
    { // Get the board just linked to
        $valid_moves[$last_move['cell']] = $board_array[$last_move['cell']];
    }
    unset($valid_moves[10]); // the metaboard is never valid
    foreach($board_array[10] as $key => $value)
    { // This way you can't move in a board that has been captured already
    
        if($value != ""){
        unset($valid_moves[$key]);}
    }
    // Get rid of squares that have been moved on. It'd be quicker to just compare your move to the board
    // array, but this feels prettier. The idea might be useful for non-ULT games later, too.
    /* for($i = 1, $c = 1; $i < 10; $i++)
    {
        if(!empty($valid_moves[$i][$c]))
        {
            unset($valid_moves[$i][$c]);
        }
    } */
}

?>
