<?php
$errors = array();
$cr = []; // 'current'; used to store repeated variables that relate to the game and are wanted in functions
if(isset($_GET['match_code'])){ $match_code = $_GET['match_code'];} else {$match_code = "";}
if(isset($_GET['match']) && is_numeric($_GET['match'])){ $match_id = $_GET['match'];} else{$match_id = 0;}
if(!empty($_GET['match_code'])){$match_id = 0;}
if(isset($_GET['gm']) && is_numeric($_GET['gm'])){ $game_id = $_GET['gm'];}
else { exit(header("Location: index.php?s=ult&a=lobby")); }
// include something here for invalid IDs

$query = $pdo->prepare("SELECT *, UNIX_TIMESTAMP(`updated`) as `timestamp` FROM ult_matches WHERE match_id = ? OR access_code = ?");
$query->execute([$match_id, $match_code]);
$match_data = $query->fetch(PDO::FETCH_ASSOC);
// include something here for empty/nonexistent games
if(empty($match_data))
{
	exit(header("Location: index.php?s=ult&a=lobby"));
}
elseif(empty($match_id))
{ // The access code is, necessarily, good
	$match_id = $match_data['match_id'];
}

$query = $pdo->prepare("SELECT *, UNIX_TIMESTAMP(`updated`) as `timestamp`, UNIX_TIMESTAMP(`host_start`) as `host_time`, UNIX_TIMESTAMP(`guest_start`) as `guest_time`, UNIX_TIMESTAMP(`created`) as `created_start` FROM ult_games WHERE (game_id = ? OR game_id = ?) AND match_id = ?");
$next_game = $game_id + 1; // a bit of a hack, for the "next game" functionality
$query->execute([$game_id, $next_game, $match_id]);

$game_data = $query->fetchAll(PDO::FETCH_ASSOC);
// include something here for empty/nonexistent games
if(empty($game_data[0]))
{
	exit(header("Location: index.php?s=ult&a=match&match={$match_id}"));
}
unset($next_game);
if(isset($game_data[1])){$next_game['id'] = $game_data[1]['game_id']; $next_game['status'] = $game_data[1]['status'];}
$game_data = $game_data[0];

// move: numeric codes - 11 thru 99
// or else, start, win, draw, or resign

$query = $pdo->prepare(
"SELECT *, UNIX_TIMESTAMP(`performed`) as `move_time` from ult_moves WHERE match_id = :match_id AND game_id = :game_id"
);
$query->execute(
	[
	":match_id" => $match_id,
        ":game_id" => $game_id,
	]
);
// Thanks to edmondscommerce: https://stackoverflow.com/questions/21485868/php-pdo-multiple-select-query-consistently-dropping-last-rowset
// do this first so we can kick you to the lobby if you don't belong here
$move_data = $query->fetchAll(PDO::FETCH_ASSOC);

$act_player = act_id_getter();
if($match_data['private'] == 1 && ($act_player === 0 && $game_code != $game_data['access_code']))
{ 
	exit(header("Location: index.php?s=ult&a=lobby"));
}

$hostname = get_username($match_data['host'], "ult");
if(!empty($match_data['guest'])){$guestname = get_username($match_data['guest'], "ult");} else{$guestname = "(none yet)";}

$players = [$match_data['host'], $match_data['guest']];
$first_mover_role = FirstMoverFinder($game_id, $players, $match_data);
$you = $act_player;
$host_display_score = $game_data['host_score'];
$guest_display_score = $game_data['guest_score'];
if($first_mover_role == $players[0])
{
    $first_mover = $players[0];
    $second_mover = $players[1];
}
else {$first_mover = $players[1]; $second_mover = $players[0];}

if($match_data['clock'] == "30min"){$time_pool_host = 1800; $time_pool_guest = 1800; $orig_pool = 1800;}
elseif($match_data['clock'] == "15min"){$time_pool_host = 900; $time_pool_guest = 900; $orig_pool = 900;}
else{$time_pool_host = 31557600; $time_pool_guest = 31557600;  $orig_pool = 31557600; // not actually indefinite, but a year
    }
$obj_time = time();
$host_time_spent = 0;
$guest_time_spent = 0;
$board_array = [];
if($first_mover_role == "host"){$mover = 1;} else{$mover = 2;}
if(empty($game_data['host_time']))
{ // in case of a non-clock game, which will start without having a time - a bit of a hack
$game_data['host_time'] = $game_data['created_start'];
}
$new_move_time = $obj_time - $game_data['host_time']; // Initialization, using timestamp
$cur_move_time = 0;
foreach($move_data as $key => $move)
{
    /*
    Rewrite this section to get rid of the cross_time_var; instead of that, use the host-guest
    time spents as a guide
    */
	$temp = str_split($move['move']);
    if(is_numeric($temp[0])){ // this prevents draws and resignations from contaminating things
	$board_array[$temp[0]][$temp[1]] = $move['piece'];}
    $new_move_time = $move['move_time'] - $guest_time_spent - $host_time_spent - $game_data['host_time'];
        if($mover === 2)
        {
            $time_pool_guest -= $new_move_time;
            $guest_time_spent += $new_move_time;
            $mover = 1;
        }
        else
        {
            $time_pool_host -= $new_move_time;
            $host_time_spent += $new_move_time;
            $mover = 2;
        }
        if($mover === 1)
        {
            $cur_move_time = $host_time_spent;
        } 
        else 
        {
            $cur_move_time = $guest_time_spent;
        }
}
$host_clock = $time_pool_host;
$guest_clock = $time_pool_guest;

$debug['obj_time'] = $obj_time;
$debug['orig_cur_time'] = $cur_move_time;
$debug['guest_time_spent'] = $guest_time_spent;
$debug['host_time_spent'] = $host_time_spent;
$debug['host_time_orig'] = $game_data['host_time'];
$cur_move_time = $obj_time + $cur_move_time - $guest_time_spent - $host_time_spent - $game_data['host_time']; // Reinitialization, using timestamp
$running_time = $orig_pool - $cur_move_time;
$debug['cur_move_time'] = $cur_move_time;
$debug['running_time'] = $running_time;
$debug['mover'] = $mover;
if($game_data['status'] == "active")
    {
    if($mover === 1)
    {
        $host_clock = $running_time;
        $cur_mover_id = $players[0];
    }
    else
    {
        $guest_clock = $running_time;
        $cur_mover_id = $players[1];
    }
    // Add section here to end games that have timed out
    if($running_time <= 0)
    {
        $loss_on_time = true;
    }
}
$last_move = lastMoveBuilder();
if($match_data['clock'] != "none")
{
    $host_clock = gmp_div_qr($host_clock, 60);
    $host_clock[1] = str_pad($host_clock[1], 2, "0", STR_PAD_LEFT);
    $guest_clock = gmp_div_qr($guest_clock, 60);
    $guest_clock[1] = str_pad($guest_clock[1], 2, "0", STR_PAD_LEFT);
    // This mixes presentation with logic, which isn't good; it really ought to be different
    $host_clock_display = "<span id='host_minutes'>{$host_clock[0]}</span>:<span id='host_seconds'>{$host_clock[1]}</span>";
    $guest_clock_display = "<span id='guest_minutes'>{$guest_clock[0]}</span>:<span id='guest_seconds'>{$guest_clock[1]}</span>";
}
else {$host_clock_display = "-"; $guest_clock_display = "-";}

$neutralcolors = true;
if($game_data['status'] == "win" || $game_data['status'] == "resign" || $game_data['status'] == "time" || $game_data['status'] == "mresign")
{
if($game_data['outcome'] == $players[0])
{
    $host_display_score -= 2;
}
else {$guest_display_score -= 2;}
}
elseif($game_data['status'] == "draw")
{
    $host_display_score -= 1;
    $guest_display_score -= 1;
}

/* 
 * Previously, moves were loaded onto the board array. Now this 2D loop fills out the board array,
 * putting an empty value in everything not already moved in. 
 */

$i = 1;
while ($i < 11)
{
	$n = 1;
	while($n < 10)
	{
		if(!isset($board_array[$i][$n]))
		{
			$board_array[$i][$n] = "";
		}
		++$n;
	}	
	++$i;
}

// Check for won boards here
foreach($board_array as $id => $s_board)
{
    if($id == 10){$win_state = WCBoardWon($s_board);}
    else
    {
        $board_array[10][$id] = WCBoardWon($s_board);
    }
}

// valids are either 1) nowhere (if it isn't your turn)
// 2) in the board you were just linked to
// 3) everywhere (if there is no last move, or the board you just were linked to was won)

$valid_moves = [];
validMoveBuilder();

/* Now, let's check for $_POST data, to see if the player sent a move in.
 */
$debug['actp'] = $act_player;
$debug['lm'] = $last_move;
$cur_mover = $act_player != 0 && $last_move['player'] != $act_player && $game_data['status'] == "active";
$debug['cur'] = $cur_mover;
if((!empty($_POST) && $cur_mover) || isset($loss_on_time))
{ // if you sent a move, and you are a player in the game, and you aren't the player who last moved...
  // ...then process the move! This section is extremely important, the engine behind the game.
    $debug['m0'] = true;
    $outcome = 0;
    $draw_state = 0;
    $host_score = $game_data['host_score'];
    $guest_score = $game_data['guest_score'];
    $status = "active";
    $piece = "";
    
    /* So this is the broad structure behind the following sections:
     * 1. We check for a draw accept. If yes, then we make a new move showing the draw, update the game
     * to show that the draw was accepted, and set the state for the match update process.
     * 2. If not, then we check for a move. We begin to prepare a new move showing the move.
     * 2a. We check for a win. If the game was won, then we update the game, and set state for the match update.
     * 2b. We check for a draw offer, assuming the game wasn't won, and set state for the match update.
     * 3. We check for a resignation. If the game was resigned, then we make a new move showing the resignation,
     * update the game, and set state for the match update.
     * 4. If the state is set for the match update, we begin the process. We see if the match was won or drawn.
     * This is complicated by the existence of OT rules. If so, then we set the match to complete, set winner
     *
     * 5. We do updates on the remaining games. If the match was won, they all need to be set to N. If not, then
     * we check and see what time is set for the next game. If it's before the current time, then we start the game
     * right away! The process used is the same as in game_starter.
     * 
     */
    // 0. Loss on time trumps everything else
    if(isset($loss_on_time))
    {
        $new_move_id = $last_move['move_id'] + 1;
        $fin_move = "...";
        if($cur_mover_id === $players[1]){$host_score += 2; $outcome = $players[0]; }
        else{$guest_score += 2; $outcome = $players[1]; }
        $game_change = true;
        $move_made = true;
        $status = "time";
    }
    else {
    // 1. First, look for a draw accept.
    if((isset($_POST['draw_offer_box']) || $_POST['ult_move'] == "=") && $game_data['draw_offer'] > 0 && $game_data['draw_offer'] < 3)
    { // This must be valid, because we reject moves from the player who just moved last.
      // We do all the same things we do for a regular move (surely this process could be broken into functions?)
      // ...but without the actual move
        $new_move_id = $last_move['move_id'] + 1;
        $fin_move = "=";
        $status = "draw";
        $draw_state = 3;
        $host_score += 1;
        $guest_score += 1;
        $game_change = true;
        $move_made = true;
    } // end draw handler
    // 2. Check for a 'real' move
    if( !empty($_POST['ult_move']))
    {
            $ult_move = str_split($_POST['ult_move']);
    } else 
    {
        $ult_move = [0, 0]; // this is to cause it to always be invalid
    }
    $debug['m1'] = $ult_move;

    if(!isset($move_made) && isset($valid_moves[$ult_move[0]][$ult_move[1]]) && empty($valid_moves[$ult_move[0]][$ult_move[1]]) && $ult_move[0] !== "-")
    { // The move is a possible move!
        $debug['m2'] = true;
        $new_move_id = $last_move['move_id'] + 1;
        $fin_move = "{$ult_move[0]}{$ult_move[1]}";
        if($last_move['piece'] == "X"){$piece = "O";} else {$piece = "X";}
        $board_array[$ult_move[0]][$ult_move[1]] = $piece;
        $debug['move'] = $ult_move;
        $debug['winboard'] = $board_array[10];
        $move_made = true;
        $board_array[10][$ult_move[0]] = WCBoardWon($board_array[$ult_move[0]]);
        // 2a.
        $win_state = WCBoardWon($board_array[10]);
        if($win_state != "")
        {
            $fin_move .= "#";
            if($act_player == $players[0]){$outcome = $players[0]; $host_score = $game_data['host_score'] + 2; $guest_score = $game_data['guest_score'];}
            else{$outcome = $players[1]; $guest_score = $game_data['guest_score'] + 2; $host_score = $game_data['host_score'];}
            // We'll get back here soon, but there are procedural reasons why we can't handle this just yet
            $status = "win";
            $game_change = true;
        }
        else 
        {
            $draw_state = 0;
            // 2z.
            $draw_state = IsBoardDrawn($board_array[10]);
            if($draw_state === 8)
            {
                $new_move_id = $last_move['move_id'] + 1;
                $fin_move = "==";
                $status = "draw";
                $draw_state = 3;
                $host_score += 1;
                $guest_score += 1;
                $game_change = true;
                $move_made = true;
            }
            // 2b.
            elseif((!empty($_POST['draw_offer_box']) && !empty($_POST['draw_offer']) || $ult_move[2] == "=") && $game_data['draw_offer'] == 0 && $win_state == "")
            {   /* Draw offers are allowed after a move, and cannot be retracted. They are ended after another move.
                * This handles someone submitting a draw offer, not what happens if the box is unchecked.
                * Several states:
                * 0 means no draw has been offered
                * 1 means that the host has offered a draw
                * 2 means that the guest has offered a draw
                * 3 means that the draw has been agreed to by both parties
                */
                if($act_player == $players[0]){$draw_state = 1;}
                else{$draw_state = 2;}
                $fin_move .= "=";
                $game_change = true;
            } // end draw offer box when moving
            elseif(empty($_POST['draw_offer_box']) && ($game_data['draw_offer'] == 1 || $game_data['draw_offer'] == 2) && $win_state == "")
            {
                $game_change = true; // This is needful, to change the draw state to 0
            }
        }
    } // end move handler
    // 3. resign game
    if(!isset($move_made) && (isset($_POST['resign1']) && isset($_POST['resign2']) && isset($_POST['resign'])) || $ult_move[0] === "-" && $ult_move[1] === "-")
    { // This must be valid, because we reject moves from the player who just moved last.
      // We do all the same things we do for a regular move (surely this process could be broken into functions?)
      // ...but without the actual move
      resign_goto:
        $new_move_id = $last_move['move_id'] + 1;
        $fin_move = "--";
        if($act_player == $players[1]){$host_score += 2; $outcome = $players[0]; }
        else{$guest_score += 2; $outcome = $players[1]; }
        $game_change = true;
        $move_made = true;
        $status = "resign";
    } // end resign handler
} // end non-timeouts
        if(isset($move_made))
        {
        // 4. Make the new move - this is done in all of the previous main paths
        $time_string = time_stringer($new_move_id, $fin_move, $cur_move_time);
        $query = $pdo->prepare("INSERT INTO `ult_moves` (`match_id`, `move_id`, `game_id`, `move`, `player`, `piece`, `performed_string`) VALUES (:match_id, :move_id, :game_id, :move, :player, :piece, :performed_string)");
        $query->execute([
        ":match_id" => $match_id,
        ":move_id" => $new_move_id,
        ":game_id" => $game_id,
        ":move" => $fin_move,
        ":player" => $act_player,
        ":piece" => $piece,
        ":performed_string" => $time_string
        ]);

        // Update the valids board
        $last_move['board'] = $ult_move[0];
        $last_move['cell'] = $ult_move[1];
        $last_move['player'] = $act_player;
        $last_move['piece'] = $piece;
        $last_move['performed_string'] = $time_string;
        $last_move['move'] = $fin_move;
        $last_move['move_id'] = $new_move_id;
        $last_move['move_time'] = $obj_time;
        $cur_mover = $act_player != 0 && $last_move['player'] != $act_player && $game_data['status'] == "active";
        if($mover === 1){$mover = 2;}
        elseif($mover === 2){$mover = 1;}
        $move_data[] = $last_move;
        $last_move = lastMoveBuilder();
        unset($valid_moves);
        validMoveBuilder();
    }
     // 5. 
     // Now, to work on the game and match outcomes
        if(isset($game_change))
    {
             $query = $pdo->prepare("UPDATE `ult_games` SET draw_offer=:draw_offer, outcome=:outcome, status=:status, host_score=:host_score, guest_score=:guest_score WHERE game_id=:game_id and match_id=:match_id");
     $query->execute(
             [
                 ":game_id" => $game_id,
                 ":match_id" => $match_id,
                 ":draw_offer" => $draw_state,
                 ":outcome" => $outcome,
                 ":status" => $status,
                 ":host_score" => $host_score,
                 ":guest_score" => $guest_score
             ]);
             if($status != "active"){ // this is needed, cuz the game updates on a draw offer, not only ends
     /* Now to check to see if the match is won
     * There are as many as 4 sets of rules for this case:
     * 1) The regular ruleset, best of x. If the score is equal at the end of x games, the match ends in a draw.
     * 2) The OT set. If the score is equal at the end of x games, the match continues. 2 new games are created,
     * at first, and then new games are created as needed when there aren't enough games to resolve the series.
     * The winner needs to win by 2 games in an OT series.
     * 3 & 4) The short OT sets. Lead-by-2 is preferred for any match length over 2, but it doesn't make sense as a
     * tiebreaker for single games, or for 2-game matches either, because it's a higher bar than the original.
     * For these situations, it takes a 2-pt lead for singletons, and technically, a 3-pt for duos.
     * 
     * Possible states:
     * 3. same as match length; match ends with win (2)
     * 4. same as; match ends with draw (1)
     * a: outcome, player
     * 
     * 1. fewer than match length: match ends with win (2)
     * 2. fewer than match length: match continues with win or draw (3)
     * b: needed points to win match
     * 
     * 5. Same as match length games; match continues with draw (OT) (1)
     * 6. More than match length games; match ends with win (OT) (2)
     * 7. More than match length games; match continues with win (margin not met) (OT) (2)
     * 8. More than match length games; match continues with draw (OT) (1)
     */
        // fetch count of games
        $query = $pdo->prepare("SELECT count(*) as count FROM `ult_games` WHERE match_id = ?");
        $query->execute([$match_id]);
        $game_count = $query->fetch(PDO::FETCH_ASSOC);
        $game_count = $game_count['count'];
        $needed_score = ($match_data['match_length']) + 1;
        if($match_data['ot'] === 1 && $game_count > $match_data['match_length'])
        {
            if($match_data['match_length'] === 1)
            {
                $needed_score = $host_score + 2;
                // This only works because the host and guest scores
                // must be the same in 1-game OT.
            }
            elseif($match_data['match_length'] === 2)
            {
                $needed_score = ($game_count + ($game_count % 2)) + 1;
            }
            elseif($match_data['match_length'] > 2)
            {
                 if($host_score > $guest_score){$needed_score = $host_score + 2;}
                elseif($guest_score > $host_score){$needed_score = $guest_score + 2;}
                else{$needed_score = $host_score + 4;}
            }
            $ot_new_game = ceil(($needed_score - $host_score) / 2);
        }
        $m_outcome = "none";
        if($host_score >= $needed_score){$m_outcome = $players[0]; $status = "completed";}
        elseif($guest_score >= $needed_score){$m_outcome = $players[1]; $status = "completed";}
        elseif(($host_score == $match_data['match_length']) && $guest_score == $host_score && $match_data['ot'] == 0){$m_outcome = 0; $status = "completed";}
        
        if($m_outcome !== "none")
        {
            $query = $pdo->prepare("UPDATE `ult_matches` SET status=:status, outcome=:outcome WHERE match_id=:match_id");
                        $query->execute(
                    [
                 ":match_id" => $match_id,
                 ":status" => $status,
                 ":outcome" => $m_outcome,
                    ]);
             $query = $pdo->prepare("UPDATE `ult_games` SET status='N' WHERE status='inactive' and match_id=:match_id");
     $query->execute(
             [
                 ":match_id" => $match_id
             ]);            
        }
        elseif(!empty($ot_new_game))
        {
            $new_id = $game_id + 1;

           while(!empty($ot_new_game))
           {
            $query = $pdo->prepare("INSERT INTO `ult_games` (`game_id`, `match_id`, `host_score`, `guest_score`) VALUES (:game_id, :match_id, :host_score, :guest_score)");
            $query->execute(
            [
            ":game_id" => $new_id,
            ":match_id" => $match_id,
            ":host_score" => $host_score,
            ":guest_score" => $guest_score
            ]);
            ++$new_id;
            --$ot_new_game;
           }
        }
        // Now, start the new game, if the time is right
        // If it isn't, then just make all the games blank again (instead of inactive)
        $query = $pdo->prepare("SELECT game_id from ult_games WHERE host_start = guest_start AND host_start != 0 AND host_start <= ? AND status = 'inactive' AND match_id = ? ORDER BY game_id LIMIT 1");
        $time = new DateTime();
        $up_date = date_format($time, "Y-m-d H:i:s");
        $query->execute([$up_date, $match_id]);
        $next_game = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($next_game['game_id']))
        {
                 $query = $pdo->prepare("UPDATE `ult_games` SET status='active', host_start=:host_start, guest_start=:guest_start, host_score=:host_score, guest_score=:guest_score WHERE game_id=:game_id and match_id=:match_id");
                      $query->execute(
     	[
     	":game_id" => $next_game['game_id'],
     	":match_id" => $match_id,
        ":host_start" => $up_date,
        ":guest_start" => $up_date,
         ":host_score" => $host_score,
         ":guest_score" => $guest_score
     	]
     );
        }
        else 
        { // blank the games out since there's no game ready for launch
                 $query = $pdo->prepare("UPDATE `ult_games` SET status='', host_score=:host_score, guest_score=:guest_score WHERE match_id=:match_id AND status='inactive'");
                 $query->execute(
                    [
                        ":match_id" => $match_id,
                        ":host_score" => $host_score,
                        ":guest_score" => $guest_score
                    ]
                );
        }
    }
    } // end game_change line        
} // end post_handler

?>
