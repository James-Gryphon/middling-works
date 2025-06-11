<?php
$Board = array(
"Arizona" => array(
"New Mexico", "Utah", "Colorado"),

"Colorado" => array(
"Arizona", "New Mexico", "Utah", "Kansas", "Oklahoma"),

"Utah" => array(
"Arizona", "Colorado", "New Mexico"),

"New Mexico" => array(
"Arizona", "Colorado", "Oklahoma", "Texas", "Utah"),

"Texas" => array(
"New Mexico", "Oklahoma", "Louisiana", "Arkansas"),

"Oklahoma" => array(
"Colorado", "New Mexico", "Texas", "Kansas", "Arkansas"),

"Kansas" => array(
"Colorado", "Oklahoma"),

"Arkansas" => array(
"Oklahoma", "Texas", "Louisiana"),
	
"Louisiana" => array(
"Texas", "Arkansas")
);

$player = "Louisiana";
$dest = "Colorado";

if($player == $dest){ $d = 0; }
else {
// initializing block
$abs_forbids = array($player);
$list[0] = GetLocs($player, $abs_forbids);
if(in_array($dest, $list[0])){ echo "<b>D has been found.</b><br><br><br>";
$d = 1;
} else{
$abs_forbids = array_merge($abs_forbids, $list[0]);
var_see($abs_forbids, "Forbids: ");
var_see($list, "List");
$t = 0; // turns of transit
$d = 0;
$var = $player;
$list[1] = array();
while($d == 0){
foreach($list[$t] as $var){
$l = GetLocs($var, $abs_forbids);
var_see($l, "l");
if(is_array($l)){ $list[$t+1] = array_merge($list[$t+1], $l);}
}
var_see($list[$t+1], "List T+1: ");
$t += 1;
if(in_array($dest, $list[$t])){ echo "<b>D has been found.</b><br><br><br>";
$d = $t+1;
} else {$abs_forbids = array_merge($abs_forbids, $list[$t]); echo "Starting new turn ", $t+1, ".<br>"; $list[$t+1] = array();}
}
}
}
var_dump($d);


function GetLocs($location, $forbids){
echo "Getting locs...<br>";
    global $Board;
var_see($location, "Location");
$list = $Board[$location];
if(is_array($list)){
$list = array_diff($list, $forbids);}
else if (in_array($list, $forbids)){ return array();}
return $list;
}

function var_see($var, $name){
echo "$name: "; var_dump($var); echo "<br>";
}

?>