<center>
<?php
$i = 0;
$roster = $contestants;
while($i < 4096){
shuffle($roster);
$qf1 = TestingBattle($roster[0], $roster[1], 0);
$qf2 = TestingBattle($roster[2], $roster[3], 0);
$qf3 = TestingBattle($roster[4], $roster[5], 0);
$qf4 = TestingBattle($roster[6], $roster[7], 0);
$qf5 = TestingBattle($roster[8], $roster[9], 0);
$qf6 = TestingBattle($roster[10], $roster[11], 0);
$qf7 = TestingBattle($roster[12], $roster[13], 0);
$qf8 = TestingBattle($roster[14], $roster[15], 0);

$gf1 = TestingBattle($qf1, $qf2, 0);
$gf2 = TestingBattle($qf3, $qf4, 0);
$gf3 = TestingBattle($qf5, $qf6, 0);
$gf4 = TestingBattle($qf7, $qf8, 0);

$sf1 = TestingBattle($gf1, $gf2, 0);
$sf2 = TestingBattle($gf3, $gf4, 0);

$winner = TestingBattle($sf1, $sf2, 1);
resetContestants($contestants);
$i += 1;
}
echo "<br>";
echo "<i>Fair Competitor: 1.25 pts/t - 15.6% P rate </i><br>";
foreach($contestants as $contestant){
echo $contestant->name, ": ", $contestant->score, " pts - ", $contestant->score/4096, "pts/t - ", round($contestant->score/1/4096*100,1), "% - P rate", "<br>";
}

function resetContestants($contestants)
{
    foreach($contestants as $contestant)
    {
        $contestant->revertBoost();
    }
}


function TestingBattle($side1, $side2, $round){
$results = DiceBattle($side1, $side2);
$win = array_pop($results);
if($win == 1){$side1->score += $round; return $side1;} else { $side2->score += $round; return $side2;}
}
?>