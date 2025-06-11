<?php
// We've got to figure out how to do this another way; this is ridiculous.
// Based on The Hunt version
// Technically, this is insecure, because it can reveal information about a private match without ever checking
// to see if you have access to see it. But since the only information is when it was last updated, I think I'm
// willing to let it go at this time.
$prefs = parse_ini_file("../../settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db."", member, passcode);
if(isset($_GET['match']) && is_numeric($_GET['match']) && isset($_GET['gm']) && is_numeric($_GET['gm']) && isset($_GET['tm']) && is_numeric($_GET['tm']))
{
    $query = $pdo->prepare("SELECT UNIX_TIMESTAMP(`performed`) as `timestamp` FROM ult_moves WHERE game_id = ? AND match_id = ? ORDER BY move_id DESC LIMIT 1;");
    $query->execute([$_GET['gm'], $_GET['match']]);
    $game_time = $query->fetch(PDO::FETCH_COLUMN);
    if($game_time == $_GET['tm']){ echo "0";}
    else {echo "1";}
}
?>