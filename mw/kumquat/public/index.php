<?php
$time_start = hrtime(true);
$prefs = parse_ini_file("../settings.ini");
date_default_timezone_set('America/Chicago');
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db.";charset=utf8mb4;", member, passcode);
require_once "".path."/files/functions.php";
SessionManager();
// Preload Prepper
require_once "".path."/files/setter.php";
// End Preload Prepper
if (!isset($_GET['z'])) { $_SESSION['loc'] = $_SERVER['QUERY_STRING'];}
// check site
require_once("".path."/files/links.php");
/*
if(isset($_POST['command']))
{
$cli_state = cli_parser($_POST['command']);
// This is a dramatic moment - cli overrides the rest of the site!!!	
}
*/
if(isset($_GET['s']) && array_key_exists($_GET['s'], $button_array))
{
	$site_key = $_GET['s'];} else {
	$site_key = array_key_first($button_array);
}

$site = $button_array[$site_key];
require_once("".path."/$site_key/links.php");
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array))
{
	$action = $_GET['a'];
} 
else 
{
	$action = array_key_first($loc_button_array);
}
$local_path = "";
$page_container = "";

// Two things to include - code, and markup
require_once("".path."/$site_key/main.php");
require("".path."/temps/main_tpl.php");
/*
if(!isset($cli_state))
{
	require("".path."/temps/main_tpl.php");
}
else { require("".path."/temps/cli_tpl.php");}
*/
// the local markup will be included() inside this file; the variable name will be passed from above
