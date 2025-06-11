<?php
require "".path."/ult/links.php";
require "".path."/ult/files/functions.php";
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "lobby";}
if(isset($_SESSION['id'])){$user_id = intval($_SESSION['id']);} else {$user_id = 0;}
?>
<?php
// Quick, simple way to kill unauthorized actions.
if(!isset($_POST['door'])){ $_POST = [];}
$member_auth_level = auth_check("ult");
require_once "".path."/ult/files/$action.php";
$local_path = "/ult/temps/main_tpl.php";
$local_site_meta = "An implementation of an advanced Tic-Tac-Toe-like game."

?>
