<?php
if(isset($_GET['a']) && array_key_exists($_GET['a'], $loc_button_array)){$action = $_GET['a'];} else {$action = "game";} 

require_once "".path."/plusminus/files/$action.php";
$local_path = "/plusminus/temps/main_tpl.php";
$local_site_meta = "With other players, add or subtract from a number and try to reach a target number."

?>