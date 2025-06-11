<?php
require_once("files/functions.php");
?>
<html>
<head>
<title>Ephemeral Dice Tournament</title>
<link rel="stylesheet" href="https://middlingworks.com/base.css">
<link rel="stylesheet" href="files/local.css">
<noscript><link rel="stylesheet" href="files/unhider.css"></noscript></head>
<body>
<a href="index.php" id="homelink">Dice Games</a>
<center>
<a id="about" href="about.php" target="_blank">About</a>
<label for="switchbox" id="switch">Statistician Mode</label>
<input type="checkbox" id="switchbox" name="switchbox">
<div class="diceboard">

<?php
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
$purple = new Dice(
    2, 4, 0, 1, "high", 1,
name: 'Super Grape',
color: '508'
    ),
$orange = new Dice(
    3, 12, 0, 1, "low", 2,
name: 'Orange Machine',
color: 'f90'
    ),
    $cyan = new Dice(
    1, 3, -1, 3,
name: 'Light Blue',
color: '09f'
    ),
    $beige = new Dice(
    3, 3, 0, 1, "low", 1,
name: 'Beige Beauty',
color: '975'
    ),
    $maroon = new Dice(
    1, 6, 0, 1, "high", 0,
name: 'Maroon Marshall',
color: '700',
surge: 1,
slump: 1
    ),
    $rose = new Dice(
    1, 3, 1, 1, "high", 0,
name: 'Rose Reactor',
color: 'f08',
surge: -1,
slump: -1
    ),
    $tan = new Dice(
    1, 10, 0, 1, "high", 0,
name: 'Tan Tumbler',
color: 'ca7',
surge: 0,
slump: 1
    ),
    $tan = new Dice(
    2, 4, 0, 1, "low", 1,
name: 'Steely Standard',
color: '39c',
surge: 1,
slump: 0
    ),
    );
shuffle($contestants);
?>
<div class="roundbox quarters">
<span class='z4'>Quarter-Finals</span><br>
<div class="groupbox"><span class='z4'><u>Group 1</u></span><br>
<div class="setbox"><b><span class='z3'>Set 1</span></b><br>
<div class='lead' style="color:#<?=$contestants[0]->color?>; background:#<?=$contestants[0]->color?>1"><?=$contestants[0]->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$contestants[1]->color?>; background:#<?=$contestants[1]->color?>1"><?=$contestants[1]->name?></div>
</div>
<div class="setbox"><b><span class='z3'>Set 2</span></b><br>
<div class='lead' style="color:#<?=$contestants[2]->color?>; background:#<?=$contestants[2]->color?>1"><?=$contestants[2]->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$contestants[3]->color?>; background:#<?=$contestants[3]->color?>1"><?=$contestants[3]->name?></div>
</div>
</div>
<div class="groupbox"><span class='z4'><u>Group 2</u></span><br>
<div class="setbox"><b><span class='z3'>Set 3</span></b><br>
<div class='lead' style="color:#<?=$contestants[4]->color?>; background:#<?=$contestants[4]->color?>1"><?=$contestants[4]->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$contestants[5]->color?>; background:#<?=$contestants[5]->color?>1"><?=$contestants[5]->name?></div>
</div>
<div class="setbox"><b><span class='z3'>Set 4</span></b><br>
<div class='lead' style="color:#<?=$contestants[6]->color?>; background:#<?=$contestants[6]->color?>1"><?=$contestants[6]->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$contestants[7]->color?>; background:#<?=$contestants[7]->color?>1"><?=$contestants[7]->name?></div>
</div>
</div></div><br> <?php
echo "<h5>Quarter-Finals: Group 1, Set 1</h5>";
$qf1 = BattleBox($contestants[0], $contestants[1]);
echo "<a id='match1'></a><br>";
echo "<h5>Quarter-Finals: Group 1, Set 2</h5>";
$qf2 = BattleBox($contestants[2], $contestants[3]);
echo "<a id='match2'></a><br>";
echo "<h5>Quarter-Finals: Group 2, Set 1</h5>";
$qf3 = BattleBox($contestants[4], $contestants[5]);
echo "<a id='match3'></a><br>";
echo "<h5>Quarter-Finals: Group 2, Set 2</h5>";
$qf4 = BattleBox($contestants[6], $contestants[7]);
echo "<a id='match4'></a><br>";
?>
<br>
<div class="groupbox hid" id="semis"><b><span class='z5'>The Semi-Finals</span></b><br>
<div class="setbox"><b><b><u><span class='z4'>Group 1</span></u></b></b><br>
<div class='lead' style="color:#<?=$qf1->color?>; background:#<?=$qf1->color?>1"><?=$qf1->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$qf2->color?>; background:#<?=$qf2->color?>1"><?=$qf2->name?></div>
</div>
<div class="setbox"><b><b><u><span class='z4'>Group 2</span></u></b></b><br>
<div class='lead' style="color:#<?=$qf3->color?>; background:#<?=$qf3->color?>1"><?=$qf3->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$qf4->color?>; background:#<?=$qf4->color?>1"><?=$qf4->name?></div>
</div>

<?php
echo "<h4>Semi-Finals: Group 1</h4>";
$sf1 = BattleBox($qf1, $qf2);
echo "<a id='match5'></a><br>";
echo "<h4>Semi-Finals: Group 2</h4>";
$sf2 = BattleBox($qf3, $qf4);
echo "<a id='match6'></a><br>";
?>
</div>

<br><div class="groupbox hid" id="finals"><b><span class='z6'>The Ephemeral Dice Tournament Finals</span></b><br><br
<div class="setbox">
<div class='lead' style="color:#<?=$sf1->color?>; background:#<?=$sf1->color?>1"><?=$sf1->name?></div>
<div class="lead">vs.</div>
<div class='lead' style="color:#<?=$sf2->color?>; background:#<?=$sf2->color?>1"><?=$sf2->name?></div>
<br><br>
<?php
$winner = BattleBox($sf1, $sf2);
echo "<a id='match7'></a><br>";
?> </div> <?php
echo "<br><div class='hid' id='celebration' style=\"color:#$winner->color; background:#{$winner->color}1\">$winner->name wins the Championship!!</div><br>";
?>
</div>
<span class='z3'>Roster: 1.1</span><br>
<span class='z3'>Version: 1.2</span>
</div>
    <br><br><span class='z3'><i>"A classic never goes out of style."</i><br></span>
    <z2><a href="mailto:gpress@soopergrape.com">site by James Gooch, 2022</a></z2><br>
</center>
<script src="files/inter.js"></script>
</body>
</html>
