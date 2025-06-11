<?php
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "map";} 
if(!isset($_POST['door'])){ $_POST = [];}
require_once "".path."/maths/files/$action.php";
$local_path = "/maths/temps/main_tpl.php";
$local_site_meta = "Solve math problems in different categories on a map."

?>
