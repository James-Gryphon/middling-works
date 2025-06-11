<?php
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=middling_main", member, passcode);

$lastweek = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-7,   date("Y"));
$updated = date("Y-m-d H:i:s", $lastweek);

    $sql = "SELECT game_id FROM `thnt_games` WHERE `updated` < ?
";
    $sth = $pdo->prepare($sql);
    $sth->execute([$updated]);
    $obsolete_games = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($obsolete_games as $old_game){
    $sql = $pdo->prepare("
DELETE `thnt_games`, `thnt_players` FROM `thnt_games` INNER JOIN `thnt_players`
WHERE `thnt_games`.game_id = :game_id AND `thnt_games`.game_id=`thnt_players`.game_id
    ");
    $sql->execute([
        ':game_id' => $old_game['game_id']
        ]);
            $sql = $pdo->prepare("
DELETE * FROM `thnt_messages` WHERE `thnt_messages`.game_id = :game_id
    ");
    $sql->execute([
        ':game_id' => $old_game['game_id']
        ]);
    }

    
?>