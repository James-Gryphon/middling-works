<?php

function BattleBox(&$buffer, $side1, $side2){
$buffer = "<div class='lead {$side1->class}'>$side1->name</div>
<div class='lead {$side2->class}'>$side2->name</div></div><div class='rollbox'>";
$results = DiceBattle($side1, $side2);
$win = array_pop($results);
foreach($results as $result){
$buffer .= "<div class='boxcol hid'>";
if($result[2] == 1){$wclass = $side1->class;} else if ($result[2] == 2) {$wclass = $side2->class;} else {$wclass = "cat";}
$buffer .= "<div class='score";
if($result[2] == 1){ echo " $wclass";}
$buffer .= "'>{$result[0]}</div>
<div class='result $wclass'";
$buffer .= ">{$result[3]} | {$result[4]}</div>
<div class='score";
if($result[2] == 2){ $buffer .= " $wclass";}
$buffer .= "'>{$result[1]}</div></div>";
};
return $win;
if($win == 1){return $side1;} else { return $side2;}
}

function DiceBattle(Dice $d1, Dice $d2){
    global $rostertexts;
$d1w = 0;
$d2w = 0;
$win = 0;
$wtl = array();
$d1->addBoostMo();
$d2->addBoostMo();
while($win == 0){
$s1 = $d1->makeSum();
$s2 = $d2->makeSum();
if(($s1 > $s2) || ($s1 === $s2 && $d1->tiebreak > $d2->tiebreak)){ $s3 = 1;} 
elseif(($s2 > $s1) || ($s2 === $s1 && $d2->tiebreak > $d1->tiebreak)){$s3 = 2;} else {
$s3 = 0;}
if($s3 == 1){ 
    $d1->surgeAlt();
    $d2->slumpAlt();
    $d1w += 1;
} else
if($s3 == 2){
    $d2->surgeAlt();
    $d1->slumpAlt();
    $d2w += 1;
}
$s4 = array($s1, $s2, $s3, $d1w, $d2w);
array_push($wtl, $s4);
if ($d1w == $rostertexts['length']){$win = 1; $d1->addBoostVal();}
if ($d2w == $rostertexts['length']){$win = 2; $d2->addBoostVal();}
}
$d1->revertMo();
$d2->revertMo();
array_push($wtl, $win);
return $wtl;
}

class Dice
{
public int $dicecount; // number of dice to roll
public int $sides; // sides of dice
public int $addmod; // arithmetical modifier; applies per die
public int $mulmod; // multiplicative modifier
public string $rules;
/* a little more complicated
"high" uses the sum of the top $rint dice
"low" uses the sum of the bottom $rint dice
*/
public int $rint; // how many dice to destroy for rules; 0 is none
public string $name; // name
public string $color; // now unused?
public string $background; // now unused?
public int $surge;
public int $slump;
public int $mo;
public string $blurb;
public int $score;
public string $class;
public int $tiebreak; // tiebreak winning level
public int $boost; // when the team wins a match, this gets added to their boostval
public int $boostval; // this amount is added to the 'mo' at the beginning of each match

public function __construct(int $dicecount = 1, int $sides = 6, int $addmod = 0, int $mulmod = 1, string $rules = "high", int $rint = 0, $name = "Default", $color = "#000000", $background = "#FFFFFF", $surge = 0, $slump = 0, $mo = 0, $blurb = "This too is a competitor, but they aren't well-known yet.", $score = 0, $class = "anonymous", $tiebreak = 0, $boost = 0, $boostval = 0){
$this->dicecount = $dicecount;
$this->sides = $sides;
$this->addmod = $addmod;
$this->mulmod = $mulmod;
$this->rules = $rules;
$this->rint = $rint;
$this->name = $name;
$this->color = $color;
$this->background = $background;
$this->surge = $surge;
$this->slump = $slump;
$this->mo = $mo;
$this->blurb = $blurb;
$this->score = $score;
$this->class = $class;
$this->tiebreak = $tiebreak;
$this->boost = $boost;
$this->boostval = $boostval;
}

public function surgeAlt(){
$this->mo += $this->surge;
}

public function slumpAlt(){
$this->mo -= $this->slump;
}

public function revertMo(){
$this->mo = 0;
}

public function addBoostVal(){
$this->boostval += $this->boost;
}

public function revertBoost(){
$this->boostval = 0;
}

public function addBoostMo(){
$this->mo += $this->boostval;
}

public function roll(){
return random_int(1, $this->sides);
}

public function makeSum(){
$i = $this->dicecount;
$d = array();
while($i > 0){
$d[$i] = $this->roll();
$i -= 1;
}
if($this->rint > 0){
if($this->rules == "high"){ rsort($d, SORT_NUMERIC);}
else {sort($d, SORT_NUMERIC);}
$i = $this->rint;
while($i > 0){
array_pop($d);
$i -= 1; }
}
$mod_calc = count($d);
$sum = array_sum($d);
$sum += $this->mo;
$sum += $mod_calc*$this->addmod;
$sum *= $this->mulmod;
return $sum;
}

}

function DiceGetter(){
if(isset($_GET['dc']) && is_numeric($_GET['dc']) && $_GET['dc'] > 0 && $_GET['dc'] < 100){
$dc = $_GET['dc']; } else { $dc = 1;}

if(isset($_GET['sc']) && is_numeric($_GET['sc']) && $_GET['sc'] > 1 && $_GET['sc'] < 100){
$sc = $_GET['sc']; } else { $sc = 4;}

if(isset($_GET['ad']) && is_numeric($_GET['ad']) && $_GET['ad'] < 100 && $_GET['ad'] > -100){
$ad = $_GET['ad']; } else { $ad = 0;}

if(isset($_GET['md']) && is_numeric($_GET['md']) && $_GET['md'] > 0 && $_GET['md'] < 100){
$md = $_GET['md']; } else { $md = 1;}

if(isset($_GET['rule']) && ($_GET['rule'] == "high" || $_GET['rule'] == "low")){
$rule = $_GET['rule']; } else { $rule = "high";}

if(isset($_GET['rint']) && $_GET['rint'] < $dc && $_GET['rint'] > 0){ 
    $rint = $_GET['rint'];} else {$rint = 0;}

if(isset($_GET['sr']) && is_numeric($_GET['sr']) && $_GET['sr'] > -100 && $_GET['sr'] < 100){
$sr = $_GET['sr']; } else { $sr = 0;}

if(isset($_GET['sl']) && is_numeric($_GET['sl']) && $_GET['sl'] > -100 && $_GET['sl'] < 100){
$sl = $_GET['sl']; } else { $sl = 0;}

if(isset($_GET['name'])){
$name = htmlspecialchars($_GET['name']);
}
else { $name = "Unnamed Entrant";}

if(isset($_GET['col'])){
$color = htmlspecialchars($_GET['col']);
$color = substr($color, 1, 6); // thanks to geeks-for-geeks for substr
// Cut the string off to the point where it's valid; if we expand the hex code to 6-digits, we should do this in reverse
if (strlen($color) < 6){
	$color = "555555";}
// Make sure there's only hex-code symbols in the string  - thanks to Kobi for the delimiter tip - "PHP regex strings need delimiters."
$var = preg_match("/^([0-9a-fA-F]*)\Z/", $color);
if (!$var){ $color = "#555555";}
else { $color = str_pad($color, 7, "#", STR_PAD_LEFT);}
}
else { $color = "#555555";}

if(isset($_GET['bg'])){
    $background = htmlspecialchars($_GET['bg']);
    $background = substr($background, 1, 6); // thanks to geeks-for-geeks for substr
    // Cut the string off to the point where it's valid; if we expand the hex code to 6-digits, we should do this in reverse
    if (strlen($background) < 6){
        $background = "eeeeee";}
    // Make sure there's only hex-code symbols in the string  - thanks to Kobi for the delimiter tip - "PHP regex strings need delimiters."
    $var = preg_match("/^([0-9a-fA-F]*)\Z/", $background);
    if (!$var){ $background = "#eeeeee";}
    else { $background = str_pad($background, 7, "#", STR_PAD_LEFT);}
    }
    else { $background = "#eeeeee";}

$competitor = new Dice(
dicecount: $dc,
sides: $sc,
addmod: $ad,
mulmod: $md,
rules: $rule,
rint: $rint,
name: $name,
color: $color,
background: $background,
surge: $sr,
slump: $sl,
class: "contestant"
);

return $competitor;
}

?>