<?php

/*
[session_manager]
sessionname = "tbase";
sessiondir = "/home/soopergr/sessions_tbase";
sessionlength = 86400
*/

function SessionManager() {
global $pdo;
// thanks to Google searches talking about session management/alt session management, and the PHP manual commentator showing ini_sets
// thanks to Álvaro González for talking about ini_sets again, and session paths
// thanks to mdibbets on PHP manual for commenting on paths
// Thanks to Shea on StackExchange, a sanity-saver
// thanks to tapken of the php manual, and a. dejong of same
// thanks to alienwebguy
session_name(sessionname);
session_save_path(sessiondir);
session_start();
$time = time();
if (isset($_SESSION['last_time']) && ($time - $_SESSION['last_time']) > sessionlength){
    // Destroy everything except for the $_SESSION['loc'], which may be helpful
    $old_loc = $_SESSION['loc'];
    if (isset($_SESSION['temp_post'])) { $temp_post = $_SESSION['temp_post']; }
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['loc'] = $old_loc;
    $_SESSION['temp_post'] = $temp_post;
    unset($old_loc);
    unset($temp_post);
}
$_SESSION['last_time'] = $time;
if(isset($_SESSION['id'])){
$sql = "UPDATE `".acro."_accs` SET seen_time=:seen_time WHERE id=:id";
$sth = $pdo->prepare($sql);
// thanks to Deepu, etc. for informing me about MySQL's odd behavior of timestamps not actually being timestamps
// thanks for php manual for proper timestamp format
// thanks to Code Redirect community for telling me to persist with timestamps

$date = date("Y-m-d H:i:s");
$sth->execute(
    [
        ':seen_time' => $date,
        ':id' => $_SESSION['id']
        ]
);
}
}
?>