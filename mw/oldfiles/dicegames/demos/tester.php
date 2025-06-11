<?php
$sides1 = $_GET['s1'] ?? 2;
$dc1 = $_GET['dc1'] ?? 2;
$dice1 = PossibleRolls($sides1, $dc1);
$sides2 = $_GET['s2'] ?? 2;
$dc2 = $_GET['dc2'] ?? 2;
$dice2 = PossibleRolls($sides2, $dc2);
$win_array = array("wins" => 0, "ties" => 0, "losses" => 0);
foreach($dice1 as $face){
foreach($dice2 as $enemy){
if($face > $enemy){ $win_array["wins"] +=1; } 
else if($face < $enemy){$win_array["losses"] += 1 ; } 
else {$win_array["ties"] += 1;}
}
}
echo $dc1, "d", $sides1, " vs. ", $dc2, "d", $sides2, "<br>";
$win_array['sum'] = $win_array['wins'] + $win_array['losses'] + $win_array['ties'];
echo "Wins: {$win_array['wins']} - ";
echo "Ties: {$win_array['ties']} - ";
echo "Losses: {$win_array['losses']}<br>";
$wdiv = round($win_array['wins']/$win_array['sum']*100, 2);
$tdiv = round($win_array['ties']/$win_array['sum']*100, 2);
$ldiv = round($win_array['losses']/$win_array['sum']*100, 2);
echo "Win Rate: $wdiv% - ";
echo "Draw Rate: $tdiv% - ";
echo "Loss Rate: $ldiv%<br>";

function PossibleRolls($sides,$dicecount){
$grand_array = array();
$dcount = $dicecount;
$sc = $sides;
$dc = $dcount;
$stack = array();
while($dc > 0){
array_push($stack,1);
$dc -= 1;
}
$dc = $dcount - 1;
// $stack should be array(1,1,1)
$i = 0;
/*
add 1 to the last number until it reaches a threshold
1,1,2
1,1,3
if it reaches threshold, add 1 to the number to its left
1,2,3
if the number to its left reached threshold, add 1 to number to its left
and so on...
1,2,1
reduce number of everything to the right of final number to 1
loop */
while ($stack[0] <= $sc){
array_push($grand_array, array_sum($stack));
$stack[$dc] += 1;
if($stack[$dc] > $sc && $dc > 0){
$i = $dc - 1;
while($stack[$i] == $sc && $i > 0){
$i -= 1; }
$stack[$i] += 1;
while($i < $dc){
$i += 1;
$stack[$i] = 1;
}
}
}
return $grand_array;
}

function MakeArray($sides, $dc){
// prints arrays showing all dice - kinda useless now that I have PossibleRolls
$dice = array();
$i = 1;
$d = 1;
while($d <= $dc){
$dice[$d] = array();
while($i <= $sides){
array_push($dice[$d], $i);
$i += 1;
}
$d +=1;
$i = 1;
}
return $dice;
}

?>