<?php
$Board = array(

"Utah" => array(
"Arizona", "Colorado", "New Mexico"),

"Arizona" => array(
"Utah", "Colorado", "New Mexico"),

"Colorado" => array(
"Arizona", "Utah", "New Mexico", "Oklahoma", "Kansas", "Nebraska"),

"New Mexico" => array(
"Utah", "Arizona", "Colorado", "Oklahoma", "Texas"),

"Nebraska" => array(
"Colorado", "Kansas"),

"Kansas" => array(
"Nebraska", "Colorado", "Oklahoma"),

"Oklahoma" => array(
"Colorado", "New Mexico", "Kansas", "Texas", "Arkansas"),

"Texas" => array(
"New Mexico", "Oklahoma", "Arkansas", "Louisiana"),

"Arkansas" => array(
"Oklahoma", "Texas", "Louisiana", "Tennessee"),

"Louisiana" => array(
"Texas", "Arkansas"),

"Tennessee" => array(
"Arkansas", "Kentucky"),

"Kentucky" => array(
"Tennessee"),

);

$player = "Arizona";
$dest = "Oklahoma";

// Is the destination the same as the location? If so, skip the search.
if($player == $dest){
echo "Distance is 0.";
} else {
// Make forbid list. Include Arizona.
$forbids = array($player);
$forbid_stack = array();
$found = -1;
$found_array = array();
$main_array = array(0 => array(
// step number
"Name" => $player, // name of state
"Step" => 0,
"Forbids" => $forbids,
"Links" => GetLocs($player, $forbids),
"From" => "Start",
)
);
var_see($main_array, "Main Array");
var_see($forbids, "Forbids");
$forbids = array_merge($forbids, $main_array[0]['Links']);
$i = 0;
while($found == -1){ // continue loop until dest is found
$counter = count($main_array);
foreach($main_array as $key => $array){
if($array['Step'] == $i){ // only process items where step is the step we're on
if($array['Name'] == $dest){echo "TARGET ACQUIRED!!!"; $found_array[] = $array;} else if(empty($found_array)){
foreach($array['Links'] as $Link){
$temp_forbids = $forbids; $temp_forbids[] = $Link;
$main_array[] = array(
"Name" => $Link,
"Step" => $i+1,
"Forbids" => $temp_forbids,
"Links" => GetLocs($Link, $temp_forbids),
"From" => $key,
    );
}
$forbid_stack = array_merge($forbid_stack, $temp_forbids);
}}}
$forbids = array_merge($forbids, $forbid_stack);
$forbids = array_unique($forbids);
$i += 1;
var_see($i, "Step $i");
var_see($main_array, "Main Array");
var_see($forbids, "Forbids");
if(!empty($found_array)){
echo "Targets found.<br><br><br>";
var_see($found_array, "Found Array");
$paths = array();
foreach($found_array as $possible_path){
array_push($paths, BuildPath($possible_path['From'], $main_array));
}
var_see($paths, "Paths");
echo(count($paths[0]) - 1);
    $found = 1;
} else
// it should always be possible to eventually find the destination, but just in case...
if(count($main_array) == $counter){ echo "No change to the main array, aborting search..."; break; }
}
}

/* echo "<br><br><br><hr>DEMO ARRAY: <br>";
$demo_array = array(0 => array(
// step number
"Name" => "Arizona", // name of state
"Step" => "0",
"Links" => array(
"California", "Colorado", "New Mexico", "Utah"),
"Forbids" => array("Arizona"),
),

1 => array(
"Name" => "California",
"Step" => "1",
"Links" => array(
"Oregon"),
"Forbids" => array("Arizona", "California"),
),
);
    
var_see($demo_array, "Main Array");
echo "<hr>";
*/

function BuildPath($from, $main_array){
echo "Building path...";
$beginning = -1;
$i = 10;
$path_stack = array($from);
while ($beginning == -1 && $i > 0){
$from = $main_array[$from]['From'];
array_push($path_stack, $from);
if($from == "Start"){
$beginning = 1;
}
$i -= 1;
}
$path_stack = array_reverse($path_stack);
return $path_stack;
}

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

function var_see($var, $name){ // thanks to Edward Yang @ Manual for <pre> idea
echo "$name: <pre>"; var_dump($var); echo "</pre><br>";
}
