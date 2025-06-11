<?php
// require_once "srms/files/functions.php";

$valids = array("system", "about");
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "system";}

require_once "".path."/sm/files/$action.php";
$local_path = "/sm/temps/main_tpl.php";
$local_site_meta = "Share messages with people that are only revealed when you have both sent them."

?>
