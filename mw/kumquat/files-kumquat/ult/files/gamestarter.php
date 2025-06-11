<?php
/*
* This is an important script for ULT that should run every half an hour (perhaps this can be sped up, if the algorithm is improved, or if 
* the server specs are upgraded.)
*/
$prefs = parse_ini_file("kumquat.middlingworks.com/settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db."", member, passcode);

$query = $pdo->prepare("
SELECT `t1`.*, `t2`.`clock` FROM `ult_games` as `t1` 
LEFT JOIN `ult_matches` as `t2` ON `t1`.`match_id` = `t2`.`match_id` 
WHERE ((`host_start` = `guest_start` AND `host_start` != 0 AND `host_start` <= ?) 
OR `clock` = 'none') 
AND `t1`.status = '' AND `t2`.`status` != 'completed' 
GROUP BY `t1`.`match_id` ORDER BY `game_id`
");
/*
$query = $pdo->prepare("
SELECT * from ult_games 
WHERE ((host_start = guest_start AND host_start != 0 AND host_start <= ?) 
OR clock = 'none') 
AND status = '' 
GROUP BY 'match_id' ORDER BY 'game_id'
"); */
$time = new DateTime();
$up_date = date_format($time, "Y-m-d H:i:s");
$query->execute([$up_date]);
$games = $query->fetchAll(PDO::FETCH_ASSOC);
/* The problem with this is, how do we filter out games that aren't active?
 * We could change everything to 'inactive' in the other games... this is simpler
 * But what other games? The other games in the matches that belong to these games.
 * It looks like we have to get a list of matches 
 */

/*
 * So there are two status fields, one for matches and one for games.
 * Match options include "open", "active", and "completed"
 * The match being open means people can join (or leave!) with no consequence
 * Active means that at least one game has been played, but there are more games to go before the series is over
 * Completed means it's been resolved
 * 
 * For game statuses, there are several, which go hand-in-hand with the numeric 'outcome' field. Status is what happened, outcome is who it benefits
 * Inactive: Another game in the match is ongoing 
 * Blank (""): The game hasn't been started yet
 * Active: The game is underway, but there's not yet a result
 * Win: The player in Outcome won outright, by 3br
 * Resign: The player in Outcome won, because the other player resigned
 * MResign: The player in Outcome won the game and the match, because the other player resigned the match
 * Time: The player in Outcome won, because the other player ran out of time
 * Draw: The game is drawn, either by the computer's calculation, or by agreement - in this case, the outcome field should be 0 
 * N: The match is over, and this game wasn't needed
 */
     $query = $pdo->prepare("UPDATE `ult_games` SET status='active' WHERE game_id=:game_id and match_id=:match_id");
     $query2 = $pdo->prepare("UPDATE `ult_games` SET status='inactive' WHERE match_id=:match_id AND status=''");
     $query3 = $pdo->prepare("UPDATE `ult_matches` SET status='active' WHERE match_id=:match_id");

foreach($games as $game)
{
    /*
     * The procedure here:
     * Pull the list of games up, where start times are equal and the match is grouped, with the lowest game id favored
     * Then, for each match, update the first game that isn't active. Because the start_time is set to a later time when the game begins,
     * this shouldn't allow two games to be active simultaneously.
     * SELECT * FROM `ult_games` WHERE `host_start` = `guest_start` GROUP BY `match_id` ORDER BY `game_id`; 
     */
     $query->execute(
     	[
     	":game_id" => $game['game_id'],
     	":match_id" => $game['match_id']
     	]
     );
      $query2->execute(
    [
    ":match_id" => $game['match_id']
    ]
 );
     $query3->execute(
    [
    ":match_id" => $game['match_id']
    ]
 );
}


?>
