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

$player = "Arizona";
$dest = "Louisiana";
$dist = array(); // no path found yet
$i = 0;
$abs_forbids = array($player);
$forbids = $abs_forbids;
var_see($forbids, "Initial Forbids");
$path = array();
$count = count($Board[$player]);
$stop = $player;
while($count > 0){
	$Step[$i] = GetLocs($stop, $forbids);
var_see($Step[$i], "Step $i");
if(empty($Step[$i])){
// Everything must be forbidden; reset.
echo "No exit found!!<br>";
$i = 0;
$abs_forbids[] = $path[0];
var_see($abs_forbids, "Abs Forbids: ");
$path = array();
$count -= 1;
$stop = $player;
$forbids = $abs_forbids;
} else {
if(in_array($dest, $Step[$i])){
// we found it!
echo "Target discovered!<br>";
$dist[] = $path;
var_see($path, "PathD");
var_see($dist, "DistD");
$i = 0;
$abs_forbids[] = $path[0];
var_see($abs_forbids, "Abs Forbids: ");
$path = array();
$count -= 1;
$stop = $player;
$forbids = $abs_forbids;
}
else {
echo "Popping array.<br>";
$stop = array_pop($Step[$i]);
$forbids = array_merge($forbids, $Step[$i]);
var_see($forbids, "Forbids");
$i += 1;
array_push($path, $stop);
var_see($path, "Path");
}
}
}
foreach($dist as $dists){
echo "Final Dists: <br>"; var_dump($dists); echo "<br>";
}

function GetLocs($location, $forbids){
echo "Getting locs...<br>";
    global $Board;
$list = $Board[$location];
if(is_array($list)){
$list = array_diff($list, $forbids);}
else if (in_array($list, $forbids)){ return array();}
return $list;
}

function var_see($var, $name){
echo "$name: "; var_dump($var); echo "<br>";
}