<publicdomain>
<i>"I use an old Windows PC, but now I need either new software or a new computer..."</i><br><br>
<form method="POST" action="">
How much money do you have available for your purchase?<br>
<input type='number' name='m' value=0><br>
Is broad and long-lasting software compatibility important to you?<br>
<input type=checkbox name="s"><br>
On a scale of 0-4, how afraid are you of 'big tech' companies? (0 being no fear, 4 being paranoia)<br>
<input type='number' name='f' value=1><br>
Do you own an Android device?<br>
<input type=checkbox name="a"><br>
Do you own an iPhone?<br>
<input type=checkbox name="i"><br>
<input type="submit" value="Which computer should I get?">
</form>
<b>
<?php
if(!empty($_POST)){ // See if the form is sent
// Define our variables
$m = (intval($_POST['m'] ?? 0)); // gets user-provided money, or sets $m to 0 if nothing was provided
if(isset($_POST['s'])) {$s = true;} else {$s = false;}
if(isset($_POST['a'])) {$a = true;} else {$a = false;}
if(isset($_POST['i'])) {$i = true;} else {$i = false;}
$f = (intval($_POST['f'] ?? 2));
// Execution phase
if($a && $f > 1){$f = 1;} // fix reported fear to match people's actual purchasing record
if($i && $f > 3){$f = 3;}
if($m > 549 && ($i == 1 || $s == 0) && $f < 4){ echo "Get a Macintosh!";} else
if($m > 199 && $f < 3){ echo "Buy a PC.";} else
if($m > 68 && $m < 200 && $f < 2){ echo "Try a Chromebook?";} else
	echo "It's Debian for you!"; 
}
?>
</b>
</publicdomain>