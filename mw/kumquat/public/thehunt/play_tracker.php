<?php
// We've got to figure out how to do this another way; this is ridiculous.
$prefs = parse_ini_file("../../settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db."", member, passcode);
if(isset($_GET['gm']) && is_numeric($_GET['gm']) && isset($_GET['tm']) && is_numeric($_GET['tm']))
{
    $query = $pdo->prepare("SELECT UNIX_TIMESTAMP(`updated`) as `timestamp` FROM thnt_games WHERE game_id = ?");
    $query->execute([$_GET['gm']]);
    $game_time = $query->fetch(PDO::FETCH_COLUMN);
    if($game_time == $_GET['tm']){ echo "0";}
    else {echo "1";}
}
?>
