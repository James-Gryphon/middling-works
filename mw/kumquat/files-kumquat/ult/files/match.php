<?php
$errors = array();
if(isset($_GET['match_code'])){ $match_code = $_GET['match_code'];} else {$match_code = "";}
if(isset($_GET['match']) && is_numeric($_GET['match'])){ $match_id = $_GET['match'];}
elseif(isset($_GET['match_code'])){$match_id = 0;}
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
$players = [$match_data['host'], $match_data['guest']];
$hostname = get_username($match_data['host'], "ult");
if(!empty($match_data['guest'])){$guestname = get_username($match_data['guest'], "ult");} else{$guestname = [0 => "(none yet)", 1 => "(none yet)"];}
$in_match = -1;

if($user_id === $match_data['host'])
{
	$in_match = "host";
    $your_name = $hostname;
    $out_match = "guest";
    // a silly lil var that will be used for time matching
}
elseif($user_id === $match_data['guest']){$in_match = "guest"; $your_name = $guestname; $out_match = "guest";}

if(isset($in_match) && $in_match != "-1" && $match_data['status'] !== 'completed')
{
    $post_array = $_POST;
//    var_see($post_array, "Post array");
    if(isset($post_array['start_status']))
    {
        if(($match_data['start_status'] === 2 && $in_match === "host") || ($match_data['start_status'] === 1 && $in_match === "guest"))
        { // The other player has already agreed, so you have agreed too; try to start the game
            $query = $pdo->prepare("UPDATE `ult_games` SET status='active' WHERE match_id=:match_id and status='' ORDER BY 'game_id' ASC LIMIT 1");
            // my idea, but jooq helped me with the exact wording
            $query->execute(
                [
                ":match_id" => $match_data['match_id']
                ]
            );
            $done = $query->fetch(PDO::FETCH_ASSOC);
            if($done == 1)
            {
                $query2 = $pdo->prepare("UPDATE `ult_games` SET status='inactive' WHERE match_id=:match_id AND status=''");
                $query2->execute(
                    [
                    ":match_id" => $match_data['match_id']
                    ]
                 );                
            }
        }
        elseif(($match_data['start_status'] === 1 && $in_match === "host") || ($match_data['start_status'] === 2 && $in_match === "guest"))
        {
            $query = $pdo->prepare("UPDATE `ult_matches` SET start_status=:start_status WHERE match_id=:match_id");
            $query->execute(
                [
                ":start_status" => 0,
                ":match_id" => $match_data['match_id']
                ]
                );
        }
        elseif($match_data['start_status'] === 0 && $in_match != -1)
        {
            if($in_match === "host"){$new_start_status = 1;}
            else{$new_start_status = 2;}
            $query = $pdo->prepare("UPDATE `ult_matches` SET start_status=:start_status WHERE match_id=:match_id");
            $query->execute(
                [
                ":start_status" => $new_start_status,
                ":match_id" => $match_data['match_id']
                ]
                );
        }
    }

    if(isset($post_array['leave']) && $match_data['status'] === 'open')
    {
        leave_handler();
    }
    elseif(
            isset($post_array['resign']) 
            && 
            isset($post_array['resign1']) 
            && 
            isset($post_array['resign2'])
            &&
            $match_data['status'] === 'active'
            )
            {
        resign_handler();
            }
    if($match_data['clock'] !== 'none')
    {
    $checks_array = array();
    // An alternate technique was described by JvdBerg - "php - finding keys in an array that match a pattern"
    foreach($post_array as $key => $value)
    {
        if(str_starts_with($key, "mod_game_mbox"))
        {
                $check = substr($key, 14);
                $checks_array[$check]['mbox'] = $value;
                unset($post_array[$key]);
        }
        else
        {
            if(str_starts_with($key, "mod_game_date"))
            {
                    $check = substr($key, 14);
                    $checks_array[$check]['date'] = $value;
                    unset($post_array[$key]);
            }
            elseif(str_starts_with($key, "mod_game_time"))
            {
                    $check = substr($key, 14);
                    $checks_array[$check]['time'] = $value;
                    unset($post_array[$key]);
            }
        }
    }

    $fin_date = new DateTime(""); // this is probably a bad practice, but this facilitates comparisons
    $hour = date_format($fin_date, "H");
    $min = date_format($fin_date, "i");
    $add_hour = 0;
    if($min == 0){$min = 0;} 
    elseif($min < 31 && $min > 0){ $min = 30;}
    else { $min = 0; $add_hour = 1;}
    $fin_date = date_time_set($fin_date, $hour, $min, 0, 0);
    if($add_hour == 1)
    {
        $fin_date = date_add($fin_date, new DateInterval('PT1H')); $add_hour = 0;
    }
    $old_date = $fin_date; // temp, until var_see is removed
    foreach($checks_array as $key => $value)
    {
        if(isset($value['mbox']))
        {
            // We just want to agree with what the other person already said, so no need to process things.
            $query = $pdo->prepare(
                "UPDATE `ult_games` SET {$in_match}_start = {$out_match}_start
                WHERE match_id=:match_id and game_id =:game_id"
            );
            $query->execute(
            [
            ':game_id' => $key,
            ':match_id' => $match_id,
            ]
            );
        }
        else
        {
        $temp = $value['date'] . " " . $value['time'];
        $temp = new DateTime($temp);
        // now, a series of corrections for bad data
        // add something to make sure that later games always come chronologically after earlier games
        $old_date = $fin_date;
        $fin_date = $temp;
        $hour = date_format($fin_date, "H");
        $min = date_format($fin_date, "i");
        if($min == 0){ $min = 0;} 
        elseif($min < 31 && $min > 0 ){$min = 30;}
        else { $min = 0; $add_hour = 1;}

        $fin_date = date_time_set($fin_date, $hour, $min, 0, 0);
        if($add_hour == 1)
        {
            $fin_date = date_add($fin_date, new DateInterval('PT1H')); $add_hour = 0;
        }
        if($fin_date < $old_date)
        {
            $fin_date = date_add($old_date, new DateInterval('P1D'));
        }
        $up_date = date_format($fin_date, "Y-m-d H:i:s");
        // upsert
        $query = $pdo->prepare( // replace into won't work because it deletes the entry
            "INSERT INTO `ult_games` (`game_id`, `match_id`, `{$in_match}_start`) VALUES (:game_id, :match_id, :x_start)
            ON DUPLICATE KEY UPDATE {$in_match}_start = :x_start"
        );
        $query->execute(
        [
        ':game_id' => $key,
        ':match_id' => $match_id,
        ':x_start' => $up_date,
        ]
        );
    }
}
    } // end no clock exception
}

$match_length = $match_data['match_length'];
$prog_host_score = 0;
$prog_guest_score = 0;
$host_wins = 0;
$guest_wins = 0;
$draws = 0;
$query = $pdo->prepare("SELECT *, UNIX_TIMESTAMP(`host_start`) as `host_time`, UNIX_TIMESTAMP(`guest_start`) as `guest_time` from ult_games WHERE match_id = ?");
$query->execute([$match_id]);
$outcomes = $query->fetchAll(PDO::FETCH_ASSOC);
$game = [];
$i = 1;
while($i <= $match_length || isset($outcomes[$i-1]))
{
	$game[$i]['date'] = ""; // date("M/D", strtotime($match['performed']));
	$game[$i]['leader'] = ""; // depends on start
	$game[$i]['outcome'] = "";
    $game[$i]['status'] = "";
	$game[$i]['score'] = "";
    // These aren't being used yet, but I hope to get rid of some of the presentation stuff later
	$game[$i]['host_time'] = 0;
	$game[$i]['guest_time'] = 0;
    $game[$i]['host_score'] = 0;
    $game[$i]['guest_score'] = 0;
    $game[$i]['outcome_username'] = "";
	++$i;
}
    $debug['game_count'] = count($game);
    $debug['outcome_count'] = count($outcomes);
    $i = 1;
    $query = $pdo->query("SELECT `game_id`, `host_score`, `guest_score`, `status`, UNIX_TIMESTAMP(`host_start`) as `host_time`, UNIX_TIMESTAMP(`guest_start`) as `guest_time`,
    (SELECT count(`game_id`) from `ult_games` WHERE `match_id` = {$match_id}) as `count`
     FROM `ult_games` WHERE `match_id` = {$match_id} and (`status` = 'active' OR status = '')
     ORDER BY `game_id` ASC LIMIT 1"
     );

    // This doesn't seem very efficient; oh well...
    $cur_game = $query->fetch(PDO::FETCH_ASSOC);

foreach($outcomes as $outcome)
{ // This whole section mixes presentation with logic
    if($outcome['first_mover'] === "host")
    {
        $game[$i]['leader'] = $hostname[0];
        $other = $guestname[0];
    }
    else
    {
        $game[$i]['leader'] = $guestname[0];
        $other = $hostname[0];
    }
    $game[$i]['score'] = "{$outcome['host_score']}-{$outcome['guest_score']}";
	if($outcome['outcome'] == $players[0]){$username = $hostname[0]; $altname = $guestname[0];}
	elseif($outcome['outcome'] == $players[1]){$username = $guestname[0]; $altname = $hostname[0];}
	else {$username = $outcome['outcome'];}
	if($outcome['status'] == "active")
	{
		$game[$outcome['game_id']]['date'] = date("M/D", strtotime($outcome['updated']));
	//	$game[$outcome['game_id']]['leader'] = $username;
		$game[$i]['outcome'] = "<a href='index.php?s=ult&a=play&match={$match_id}&gm={$outcome['game_id']}#lma'>In progress...";
        if($in_match !== -1)
        { // So you can show if it's your active move
            $query = $pdo->query("SELECT move_id from `ult_moves` WHERE `match_id` = {$match_id} AND `game_id` = {$cur_game['game_id']} ORDER BY `move_id` DESC LIMIT 1");
            $cur_move = $query->fetch(PDO::FETCH_ASSOC);
            if(empty($cur_move)){$cur_move = 0;} else {$cur_move = $cur_move['move_id'];}
            $temp = $cur_move % 2;
            if(($temp === 0 && $game[$outcome['game_id']]['leader'] !== $your_name) || ($temp === 1 && $game[$outcome['game_id']]['leader'] === $your_name))
            { 
                $game[$i]['outcome'] .= " (move!)";
            }
        }
        $game[$i]['outcome'] .= "</a>";
	}
	elseif($outcome['status'] == "win")
	{
            if($outcome['outcome'] == $players[0]){$host_wins += 1;}
            else{$guest_wins += 1;}
            $game[$i]['outcome'] = "<a href='index.php?s=ult&a=play&match={$match_id}&gm={$outcome['game_id']}'>$username won (#)</a>";
    }
    elseif($outcome['status'] == "time")
	{
            if($outcome['outcome'] == $players[0]){$host_wins += 1;}
            else{$guest_wins += 1;}
            $game[$i]['outcome'] = "<a href='index.php?s=ult&a=play&match={$match_id}&gm={$outcome['game_id']}'>$username won (...)</a>";
    }
	elseif($outcome['status'] == "resign")
	{
		if($outcome['outcome'] == $players[1]){$guest_wins += 1;}
		else{$host_wins += 1;}
		$game[$i]['outcome'] = "<a href='index.php?s=ult&a=play&match={$match_id}&gm={$outcome['game_id']}'>$username won (--)</a>";
	}
        elseif($outcome['status'] == "draw")
        {
            $draws += 1; $game[$i]['outcome'] = "<a href='index.php?s=ult&a=play&match={$match_id}&gm={$outcome['game_id']}'>Draw (=)</a>";
        }
	else {$game[$i]['outcome'] = "Not played"; $game[$i]['score'] = "-";}
	$game[$i]['host_time'] = $outcome['host_time'];
	$game[$i]['guest_time'] = $outcome['guest_time'];
    $game[$i]['status'] = $outcome['status'];
        ++$i;
}
$debug['in_match'] = $in_match;


?>
