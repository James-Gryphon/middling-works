<?php
require_once "".path."/home/files/functions.php";
// valids seems to be superseded by the link array; we should replace it
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} 
elseif(isset($_GET['s']) && $_GET['s'] == "home") { $action = "gpress";}
else {$action = "directory";}
?>
<?php
require_once("".path."/home/files/$action.php");
if(isset($cli_state))
{
	$local_path = "/home/temps/{$action}_cli_tpl.php";
}
else
{
	$local_path = "/home/temps/{$action}_tpl.php";
}
?>
