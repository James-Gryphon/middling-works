<?php
$grand_array = array();
$dcount = 2;
$sc = 4;
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
?>