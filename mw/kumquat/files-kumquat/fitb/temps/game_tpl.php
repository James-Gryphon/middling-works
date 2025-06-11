<center>
<a id="most_specific" class="skip">Content</a>
<div class="title_header"><h3>Fill in the Blanks</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<?php
// Now show error messages, if any
if(!empty($errors))
{
    echo "<div class='error'>Your solution attempt is not valid, because:</div>";
    foreach($errors as $key => $value)
    {
        echo notice($key, "autoinline");
    }
}

if(isset($outcome_solved))
{
	// solve attempt
	if($outcome_solved === "yes")
    {
        echo "<div class='congrats'><b><span class='z5'>Congratulations!</span> <span class='z5'>You have solved the puzzle!</span></b></div>";
        echo '<span class="z7">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	} 
    else
	{
        echo "<b><i><span class='sorry'><span class='z6'>Sorry</i></span></span>, you failed to solve the puzzle.</b><br>";
        echo "The solution was:<br>";
        echo '<span class="z7">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	}
}
 // end solve section
else 
{ // Guess attempt
	if(isset($_SESSION['lettered']))
	{
	echo "<b>You guessed all the letters in the puzzle.</b><br>";
	echo '<span class="z7">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	} 
	elseif (isset($used_guess)){ echo "You've already guessed this letter.<br>";}
	elseif($guess_length == 1)
	{
		if($problem_dup == $problem)
		{
			echo "<b>You have guessed all the letters in the puzzle!</b><br>";
			echo '<span class="z7">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
		}
	}
	elseif (isset($_POST['guess']) && !isset($_POST['solve'])) {echo "A guess must consist of a single Latin alphabetic character.<br>";}
} // end guess attempt
// Now to show things for everybody
echo "<div class='puz_box"; 
if(isset($_SESSION['lettered']) || isset($_SESSION['solved'])){echo " won";} 
echo "'><span class='z7' id='guess_string'>$render_string</span></div>";
if(!empty($puzzle['puzzle_clue'])){ echo "<span class='z5'>Clue: {$puzzle['puzzle_clue']}</span><br>";}
if(!isset($_SESSION['solved']) && !isset($_SESSION['lettered'])):
?>
<form method="POST" name="puzzle_form" action="index.php?s=fitb">

<?php
$keyboard = [];
$keyboard[] = ["Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P"];
$keyboard[] = ["A", "S", "D", "F", "G", "H", "J", "K", "L"];
$keyboard[] = ["Z", "X", "C", "V", "B", "N", "M"];

?>
<div id="fitb_outer_container">
<input id="solve" type="checkbox" name="solve">
<textarea id="guess" name="guess"></textarea>
<div id="fitb_inner_container">
<?php
// We might also check if the strpos thing is efficient, or if it'd be better to merge arrays or something
/*
foreach($keyboard as $keyboardkey => $keyboardvalue)
{
	echo "<div class='fitb_row'>";
	foreach($keyboardvalue as $key => $value)
	{
		$active = strpos($noreuse, $value);
		echo
		"
		<div class='fitb_cell'>
		<input id='{$value}' class='fitb_box' name='letter_box' value ='{$value}' type='radio'
		"; if($active !== false){echo " disabled";}
		echo ">
		<label class='fitb_box_label' for='{$value}'>{$value}</label>
		</div>
		"
		;
	}
	echo "</div>";
}
*/
?>
</div>
</div>
</div>

<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<br>
<?php
endif;
echo "Guesses used: <b>$guess_count</b><br>";
if(empty($unused)){$unused_string = "None yet";} else {$unused_string = $unused;}
echo "Unused letters: <span id='unused_letters'>$unused_string</span><br>";
if(isset($puzzle['puzzle_clone']))
{
	$old_date = date_create_immutable_from_format("Y-m-d", $puzzle['puzzle_clone']);
	$old_date = date_format($old_date, "l, F j, Y");
		echo "<span class='z3'>This puzzle was originally released $old_date.</span><br>";
}
if(!isset($_SESSION['solved']) && !isset($_SESSION['lettered'])): ?>
<input type="submit" value="Submit" id="fitb_submit">
<?php
endif;
if($new_game == 1):
?>
<br><a href="index.php?s=fitb&clear=1">Load Today's Puzzle</a><br>
<span class='z3'><i>This will end your current puzzle!</i></span><hr>
<?php
elseif(isset($_SESSION['solved']) || isset($_SESSION['lettered'])):
?>
<br>Come back tomorrow for a new puzzle!<hr>
<?php endif;

?>
</div>
</div>
</div>
<footer class="local">
<span class='z3'>Version: 1.2.4</span>
</footer>
</center>
<?php $res = "fitb/autokeys.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>