<?php
require_once("files/functions.php");
?>
<html>
<head>
<title>Ephemeral Dice Tournament: Session Demo</title>
<link rel="stylesheet" href="files/base.css"></head>
<body>
<center>

<?php
/* 
Stage 1 - initialization
Stage 2 - play
*/
session_start();
if(!isset($_SESSION['init'])){$_SESSION['init'] = 1;}
if($_POST[''])


if($_SESSION['init'] == 1){
Initializer_Maker(4);
}

?>    
<a id="about" href="https://middlingworks.com/gpress/index.php?a=read&d=Virtual%20Dice%20Games" target="_blank">About</a>
<label for="switchbox" id="switch">Statistician Mode</label>
<input type="checkbox" id="switchbox" name="switchbox">
<div class="diceboard">


?>
<span class='z3'>Roster: 1</span><br>
<span class='z3'>Version: 1-SES</span>
</div>
</center>
</body>
</html>



<?php
// functions
function Initializer_Maker($i){
?>
<form name="initialize" method="POST">
<?php
while($i > 0):
?>
<div class="groupbox">
    Name:<br>
<input type="text" name="name_<?=$i?>" id="name_<?=$i?>"><br>
    Color:<br>
<input type="color" name="color_<?=$i?>" id="color_<?=$i?>"><br>
    Dice Count:<br>
<input type="number" name="dicecount_<?=$i?>" id="dicecount_<?=$i?>"><br>
    Sides:<br>
<input type="number" name="sides_<?=$i?>" id="sides_<?=$i?>"><br>
    Additive bonus:<br>
<input type="number" name="addmod_<?=$i?>" id="addmod_<?=$i?>"><br>
    Multiplier:<br>
<input type="number" name="mulmod_<?=$i?>" id="mulmod_<?=$i?>"><br>
    Rules:<br>
<input type="text" name="rules_<?=$i?>" id="rules_<?=$i?>"><br>
    Rint:<br>
<input type="number" name="rint_<?=$i?>" id="rint_<?=$i?>"><br>
</div>
<?php
if(is_odd($i)){ echo "<br>";}
$i -= 1;
endwhile;
?>
</form>
<?php
}
/*
function Initializer_Verifier($iv){
// make sure name's good; reset if it isn't
if(strlen($iv['name']) == 0){
switch($iv['id']){
case 0: $name = "Red Flag"; break;
case 1: $name = "Blue Badger"; break;
case 2: $name = "Green Shield"; break;
case 3: $name = "Yellow Light"; break;
}} else {$name = $iv['name'];}

if($strlen($iv['color'] != 7)){
switch($iv['id']){
case 0: $color = "#f00"; break;
case 1: $color = "#00f"; break;
case 2: $color = "#0f0"; break;
case 3: $color = "#ff0"; break;
}} else {$color = $iv['color'];}

$sides = $iv['sides'];
if()
}
*/


?>
