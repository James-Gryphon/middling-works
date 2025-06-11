<?php
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=middling_main", member, passcode);

$lastweek = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-7,   date("Y"));
$updated = date("Y-m-d H:i:s", $lastweek);

    $sql = "SELECT batch FROM `sm_batches` WHERE `modified` < ?
";
    $sth = $pdo->prepare($sql);
    $sth->execute([$updated]);
    $obsolete_batches = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($obsolete_batches as $old_batch){
    $sql = $pdo->prepare("
DELETE `sm_batches`, `sm_msgs`, `sm_users` FROM `sm_batches` INNER JOIN `sm_msgs` INNER JOIN `sm_users`
WHERE `sm_batches`.batch = :batch AND
`sm_batches`.batch=`sm_msgs`.batch AND `sm_msgs`.batch=`sm_users`.batch
    ");
    $sql->execute([
        ':batch' => $old_batch['batch']
        ]);
    }

    
?>
