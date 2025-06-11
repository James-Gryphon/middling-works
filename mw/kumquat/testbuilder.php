<?php

$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=middling_data;charset=utf8mb4;", member, passcode);

$i = 1;

$query = $pdo->prepare("SELECT id, status from test_matches");
$query->execute([]);
$matches = $query->fetchAll(PDO::FETCH_ASSOC);

$start1 = hrtime(true);
$query = $pdo->prepare("UPDATE `test_games` SET game_status=:status WHERE game_id =:game_id AND match_id =:match_id");
while($i == 1){
foreach($matches as $key => $value)
{
    $key += 1;
//    $query = $pdo->prepare("INSERT INTO `test_games` (`game_id`, `match_id`) VALUES (?, ?)");
    $query->execute(
            [
                ':status' => 1,
                ':game_id' => $i,
                ':match_id' => $key
            ]);
}
++$i;
}
$end1 = hrtime(true);
$time1 = $end1 - $start1;
    echo "Test: Total time is ", round($time1/1000000,3), "ms<br>";

?>
