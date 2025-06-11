<?php
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "game";}
if(!isset($_POST['door'])){ $_POST = [];}
require_once "".path."/fitb/files/$action.php";
if(isset($cli_state)){$local_path = "/fitb/temps/main_cli_tpl.php";}
else {$local_path = "/fitb/temps/main_tpl.php";}
$local_site_meta = "A daily puzzle game where you guess letters to reveal a mystery word, phrase or sentence."
?>
