<?php
require_once "".path."/dicegames/files/functions.php";

$valids = array("game", "about", "roster");
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "game";}
if(isset($_GET['r']) && file_exists("".path."/dicegames/files/rostern{$_GET['r']}.php")) {
$_SESSION['dg_roster'] = $_GET['r'];
}
if(!isset($_SESSION['dg_roster'])){ $_SESSION['dg_roster'] = 1;}
require_once("".path."/dicegames/files/rostern{$_SESSION['dg_roster']}.php");

require_once("".path."/dicegames/files/$action.php");
$local_path = "/dicegames/temps/main_tpl.php";