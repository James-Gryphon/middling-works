<?php

$contestant = DiceGetter();
shuffle($contestants);
if(isset($_GET['nw'])){
array_unshift($contestants, $contestant);
array_pop($contestants);}
if(!empty($_SESSION['champions'])){
    $last_winner = array_key_last($_SESSION['champions']);
foreach($contestants as $key => $value){
    if($value->name == $_SESSION['champions'][$last_winner]['winner-name']){
        unset($contestants[$key]);
        array_unshift($contestants, $value);
        break;
    }
}
}
// For now, we only want 8 contestants
$i = 8;
while(isset($contestants[$i]))
{
    unset($contestants[$i]);
    $i += 1;
}
$actives = $contestants;
// Generalize this later
$tournament = [];
$match_associates = array();
$actives_count = count($actives);
// var_see($actives_count, "Original actives");
$t = 1;
$i = 4;
$c = 0;
while($actives_count > 1)
{
    $s = 1;
    $old_i = $i;
    $tournament[$t] = [];
    reset($actives);
    while($s <= $i)
    {
        $first = current($actives);
        $match_associates[$t][$s][1] = key($actives);
//        echo "$first->name vs. ";
        next($actives);
        $second = current($actives);
//        echo "$second->name<br>";
        $match_associates[$t][$s][2] = key($actives);
//        var_see($match_associates, "Associates $t $s");
        $tournament[$t][$s] = DiceBattle($first, $second);
//        echo "Round $t, Set $s: $first->name vs. $second->name<br>";
        $win = array_key_last($tournament[$t][$s]);
        $win = $tournament[$t][$s][$win];
        if($win === 1)
        { 
//            echo "$first->name wins1<br>";
            $key = key($actives);
//            echo "Unsetting {$actives[$key]->name}1: $key<br>";
            unset($actives[$key]);
        }
        else 
        {
//            echo "$second->name wins2<br>";
            prev($actives);
            $key = key($actives);
//            echo "Unsetting {$actives[$key]->name}2: $key<br>";
            unset($actives[$key]);
            next($actives);
        }
        $winner = array_pop($tournament[$t][$s]);
        $c += 2;
        $s += 1;
    }
    $i = 0;
    $i = $old_i / 2;
//    var_see($i, "New old i");
    $c = 0;
    $s = 1;
    $t += 1;
    $actives_count = count($actives);
//    var_see($actives_count, "Actives count");
}
// var_see($tournament, "Tournament Final Results");

if($winner === 1)
{
    $winner = $contestants[$match_associates[3][1][1]];
    $loser = $contestants[$match_associates[3][1][2]];
}
else 
{
    $winner = $contestants[$match_associates[3][1][2]];
    $loser = $contestants[$match_associates[3][1][1]];
}

if(empty($_SESSION['champions']) || isset($_GET['r'])){ $_SESSION['champions'] = array();}
$_SESSION['champions'][] = array("winner-name" => $winner->name, "loser-name" => $loser->name, "winner-class" => $winner->class, "loser-class" => $loser->class);
$roundcount = count($_SESSION['champions']);
if($roundcount > 20)
{
    $_SESSION['champions'] = array_reverse($_SESSION['champions'], true); 
    array_pop($_SESSION['champions']); 
    $_SESSION['champions'] = array_reverse($_SESSION['champions'], true);
}

?>