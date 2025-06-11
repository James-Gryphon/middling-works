<?php
// Someday, soon Lord willing, this will be obsolete
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=middling_main", member, passcode);
$pdo_kumquat = new PDO("mysql:host=".host.";dbname=middling_kumquat", member, passcode);

    $sql = "UPDATE `home_accounts` SET `failed_forgets`=0, `failed_logins`=0
";
    $sth = $pdo->query($sql);
    $sth = $pdo_kumquat->query($sql);
    
?>