<?php
require "".path."/thehunt/links.php";
require "".path."/thehunt/files/functions.php";
require "".path."/thehunt/files/classes.php";
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "lobby";}
if(isset($_SESSION['id'])){$user_id = intval($_SESSION['id']);} else {$user_id = 0;}
?>
<?php
// Quick, simple way to kill unauthorized actions.
if(!isset($_POST['door'])){ $_POST = [];}
$member_auth_level = auth_check("thehunt");
require_once "".path."/thehunt/files/$action.php";
$local_path = "/thehunt/temps/main_tpl.php";
$local_site_meta = "An web 'board game' where you search a continent, planet, city, or other location for the mysterious Mole. "

?>
