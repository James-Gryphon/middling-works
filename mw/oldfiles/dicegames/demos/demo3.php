<?php
require_once("files/functions.php");
?>
<html>
<head>
<title>Ephemeral Dice Tournament</title>
<link rel="stylesheet" href="files/base.css"></head>
<body>
<center>
<a id="about" href="https://middlingworks.com/gpress/index.php?a=read&d=Virtual%20Dice%20Games" target="_blank">About</a>
<label for="switchbox" id="switch">Statistician Mode</label>
<input type="checkbox" id="switchbox" name="switchbox">
<div class="diceboard">

<?php
session_start();
if(!isset($_SESSION['init'])){$_SESSION['init'] = 1;} else {
if($_SESSION['init'] == 1){
// initialize
$contestants = array(
$red = new Dice(
name: 'Red Flag',
color: 'c00'
    ),
$blue = new Dice(
    1, 4, 1,
name: 'Blue Badger',
color: '009'
    ),
$green = new Dice(
    2, 2, 0,
name: 'Green Shield',
color: '070'
    ),
$yellow = new Dice(
    1, 8, -1,
name: 'Yellow Light',
color: '990'
    ),
    );
shuffle($contestants);
}


echo "<h5>Semi-Finals: Group 1</h5>";
$qf1 = BattleBox($contestants[0], $contestants[1]);
echo "<br>$qf1->name wins!<br>";
echo "<h5>Semi-Finals: Group 2</h5>";
$qf2 = BattleBox($contestants[2], $contestants[3]);
echo "<br>$qf2->name wins!<br>";
echo "<h4>The Final Battle</h4>";
$winner = BattleBox($qf1, $qf2);
echo "<br><h4><span style=\"color:#$winner->color;\">$winner->name wins the Championship!!</span></h4>";
}
?>
<span class='z3'>Roster: 1</span><br>
<span class='z3'>Version: 1.1 - SES</span>
</div>
</center>
</body>
</html>
