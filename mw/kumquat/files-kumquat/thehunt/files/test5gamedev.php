<?php

require_once("board.php");

// Choose player starting point
$rand = random_int(0,count($Continents)-1);
$continent = $Continents[$rand];
$country = random_int(0,count($continent->territories)-1);
$country = $continent->territories[$country];
$player = $country;

// Place the Mastermind
while(!isset($mastermind) || $mastermind == $player){
$rand = random_int(0,count($Continents)-1);
$continent = $Continents[$rand];
$country = random_int(0,count($continent->territories)-1);
$country = $continent->territories[$country]; 
$mastermind = $country;
}

foreach($Continents as $Continent){
while(!isset($agent) || $agent == $mastermind || $agent == $player){
$country = random_int(0,count($Continent->territories)-1);
$agent = $Continent->territories[$country];}
$Continent->agent_spot = $agent;
unset($agent);
}

echo "Players start at $player.<br>";
echo "<b>The Mastermind</b> is at $mastermind.<br>";
foreach($Continents as $Continent){
echo "The agent for $Continent->name is at $Continent->agent_spot.<br>";
}

?>
<?php
$forbids = array([$player]);
/*
var_dump($player);
var_dump($forbids);
var_dump($Board);
var_dump($Board[$player]); */
$player_locs = GetLocs($player, $forbids);
?>
<form>
<select id="locations" name="locations" size=4>
<?php
foreach($player_locs as $player_loc){
echo "<option value='$player_loc'>$player_loc</option>";
}
?>
</select>

</form>

<?php

function Pathfinder()
{
// placeholder for when I'm ready to include the pathfinding mechanism
}

?>