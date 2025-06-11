<center>
<a id="most_specific" class="skip">Content</a>
<div class="main_box single">
<div class="title_header"><h3>Fill in the Blanks</h3></div><br>
<div class="main_box_content">
<div class="con_box">
<?php
/* "Test string"

Puzzle sequence -
Get current puzzle (puzzle of the day)
Get/scan guessed letter cache, which should be array
Generate status - each letter in the cache is shown in all caps; each letter not in the cache is shown as an underscore, surrounded by spaces
If the status is equal to the puzzle, then the puzzle is solved
For a letter guess - add to the cache - generate status
For a solution - see if string matches - if it doesn't, you instantly lose; if  it does, the puzzle is solved

// A word unit consists of alphabetical symbols, broken up by spaces. Punctuation is ignored for the sake of the puzzle, and shown after a puzzle is solved.
*/

$new_game = 0;
// old: $date = date("mdy");
$date = date("Y-m-d");
$day = date("D");

// Begin game maintenance and building phase
if((isset($_SESSION['puzzle']) && isset($_SESSION['puz_date']) && $date != $_SESSION['puz_date'])){
$new_game = 1; // new game available
}
if((isset($_GET['clear']) && $new_game == 1) || isset($_GET['puz'])){
    unset($_SESSION['puz_date']);
    unset($_SESSION['puzzle']);
    $_SESSION['guessed'] = ""; 
    $_SESSION['unused'] = ""; 
    unset($_SESSION['lettered']); 
    unset($_SESSION['solved']); 
    $new_game = 0;
}
if(!isset($_SESSION['puzzle'])){
$_SESSION['puz_date'] = $date; // for time checks
// SQL section here: this solution is a bit hacky, but look for either the $_GET date, *or* the regular date
$sql = "SELECT * FROM `fitb_puzzles` WHERE `puzzle_date` = ? OR `puzzle_date` = ? ORDER BY puzzle_date ASC LIMIT 1";
$sth = $pdo->prepare($sql);
if(isset($_GET['puz']))
{
	$sth->execute([$_GET['puz'], $date]);
	$puzzle = $sth->fetch();
	if(empty($puzzle))
	{ // we can't allow an insert because someone made a bad $_GET['puz']
	$sth->execute([$date, $date]);
	$puzzle = $sth->fetch();
	}
} else 
	{
	$sth->execute([$date, $date]);
	$puzzle = $sth->fetch();
	}
	if(empty($puzzle))
	{
	// There's no puzzle for today, so reuse an earlier one.
	if($day === "Sun"){$sunday = 1;} else {$sunday = 0;}
	$sql = "SELECT * FROM `fitb_puzzles` WHERE `puzzle_sunday` = ? AND `puzzle_repeat` > -1 ORDER BY `puzzle_repeat` ASC, `puzzle_date` ASC LIMIT 1";
	$sth = $pdo->prepare($sql);
	$sth->execute([$sunday]);
	$puzzle = $sth->fetch();
	$puzzle['puzzle_clone'] = $puzzle['puzzle_date'];
	// Now, create a new puzzle with old_puzzle as a pattern.
	$sql = $pdo->prepare("INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES (:puzzle_date, :puzzle_sunday, :puzzle_repeat, :puzzle_clone)");
    $sql->execute([
    ":puzzle_date" => $date,
    ":puzzle_sunday" => $sunday,
	":puzzle_repeat" => -1,
	":puzzle_clone" => $puzzle['puzzle_date'],
    ]);
	$new_puz_repeat = $puzzle['puzzle_repeat'] + 1;
	$sql = $pdo->prepare("UPDATE `fitb_puzzles` SET puzzle_repeat=:puzzle_repeat WHERE puzzle_date=:puzzle_date");
	$sql->execute([
		":puzzle_repeat" => $new_puz_repeat,
		":puzzle_date" => $puzzle['puzzle_date']
	]);
	$puzzle_clone = $puzzle['puzzle_date'];
	}
	elseif(!empty($puzzle['puzzle_clone'])){
	$puzzle_clone = $puzzle['puzzle_clone'];
	$sth = $pdo->prepare("SELECT * from `fitb_puzzles` WHERE `puzzle_date` = :puzzle_clone LIMIT 1");
	$sth->execute([":puzzle_clone" => $puzzle_clone]);
	$puzzle = $sth->fetch();
	$puzzle['puzzle_clone'] = $puzzle_clone;
	}
$_SESSION['puzzle'] = $puzzle; }
$puzzle = $_SESSION['puzzle'];
// End main game building phase

if(isset($_SESSION['guessed'])){ $guessed = $_SESSION['guessed'];} else {
    $guessed = ""; }
if(isset($_SESSION['unused'])){ $unused = $_SESSION['unused'];} else {
    $unused = ""; }
// end post handling

// insert stuff here to get string and/or 'nice string' from external file
$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$nicestring = $puzzle['puzzle_text'];
$guess_count = mb_strlen($guessed, "UTF-8") + mb_strlen($unused, "UTF-8");
$guess = "";

// The String Factory, Part 1
// Make it uppercase, to avoid case-sensitivity issues
$string = strtoupper($nicestring);
// Create a non-formatted version
$problem = mb_str_split($string, 1, "UTF-8");
foreach($problem as $key => $letter){
if($letter == " "){}
elseif(strpos($alphabet, $letter) === false){unset($problem[$key]);}
} // we should now have only letters and spaces
$string = implode("", $problem);

// Processing the Guess
if(isset($_POST['guess']) && !empty($_POST['guess']) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']) { 
$guess = $_POST['guess']; // put filters here later
$guess = strtoupper($guess);
$guess_exploded = mb_str_split($guess, 1, "UTF-8");
foreach($guess_exploded as $key => $letter){
if($letter == " "){}
elseif(strpos($alphabet, $letter) === false){unset($guess_exploded[$key]);}
} // we should now have only letters and spaces
$guess = implode("", $guess_exploded);
$guess_exploded = array_values($guess_exploded);
}
$guess_length = mb_strlen($guess, "UTF-8");

// Several junctions here. We want to see if the puzzle has already been finished (whether by being solved, lost, or having had all the letters guessed). If it has, we should skip input, but still do the work to display the proper puzzle, guess counts, etc.

// IF the puzzle isn't over, AND the guess is valid, AND the guess isn't already in either guess or unused, THEN add guess to either guessed or unused, our letter caches.
if($guess_length == 1 && !isset($_SESSION['solved']) && !isset($_SESSION['lettered'])){
$guessed_check = strpos($guessed, $guess);
if($guessed_check === false && strpos($string, $guess) !== false){
$guessed .= $guess;
}
else if(strpos($unused,$guess) === false && $guessed_check === false) 
{$unused .= $guess;}
else {$used_guess = 1;}
}
$_SESSION['guessed'] = $guessed;
$_SESSION['unused'] = $unused;

// The String Factory, Part 2
/* Now that we have guess information, it's time to build all we'll need for matching text info.
We have 1) $problem, which contains all of the problem information in an array.
We need three things: 
1) a nice string that shows the problem for players in a readable way,
2) an array with only uppercase letters, for solving purposes,
3) an array with only letters and underscores, for comparing to the problem
*/

// 1 and 3 go together
$problem = array_values($problem);
$problem_dup = $problem; // so the old array survives
foreach($problem_dup as $key => $letter){
if($letter == " "){}
elseif(strpos($guessed, $letter) === false){ $problem_dup[$key] = "_";}
}
$string = implode($problem_dup);
$render_string = str_replace(" ", " * ", $string);
$render_string = str_replace("_", " _ ", $render_string);
// We should now have a string suitable for showing to the player - 1

/* We're not using this right now because we're trying to incorporate spaces
// thanks to Geeks for Geeks pointing out I need both the key and letter
foreach($problem_dup as $key => $letter){
if($letter == " "){unset($problem_dup[$key]);}
} // we should now have an array with only letters/underscores - 3
$problem_dup = array_values($problem_dup); */
/*
foreach($problem as $key => $letter){
if($letter == " "){unset($problem[$key]);} // we should now have an array with only letters - 2
} */
$string = implode($problem); // string version of problem array
// Now let's get the number of characters in the problem
$string_length = mb_strlen($string, "UTF-8");
/*
echo "Problem: "; var_dump($problem); echo "<br>";
echo "Problem_Dup: "; var_dump($problem_dup); echo "<br>";
echo "Guess exploded:"; var_dump($guess_exploded); echo "<br>";
*/
// Now the splitting-off point, where we process input. If you have already finished, you're sped along the fast track to the finish.
// For solutions
if((isset($_POST['solve']) && !isset($_SESSION['lettered'])) || isset($_SESSION['solved'])){
// check if input is valid
/*
Several end conditions:
1. Input is valid (string is same length as puzzle and there are no bad characters), OR solution has been solved already
2. Input is invalid (string has bad characters)
3. Input is invalid (string is not same length as puzzle)
4. Input is invalid (string has bad characters, AND isn't same length as puzzle)
5. Input is invalid (string has no characters).
*/
// Now for a series of checks to see if this is valid, but skip if already solved
$fail = 0;
if(!isset($_SESSION['solved'])){
// Can't do anything with no input
if($guess_length == 0){ echo "Nothing was entered, so nothing happens."; $fail = 1;}
else {
// Overlap test
$i = 0;
while($i < $string_length){
if(($problem_dup[$i] == " " && $guess_exploded[$i] != " ") || ($problem_dup[$i] != " " && $guess_exploded[$i] == " ")){ $space_error = true; }
if($problem_dup[$i] != "_" && $problem_dup[$i] != " " && $guess_exploded[$i] != $problem_dup[$i]){ $overlap_fail = true;}
if($problem_dup[$i] == "_" && strpos($guessed, $guess_exploded[$i]) !== false){$guessed_already = true; }
if(isset($overlap_fail) && isset($guessed_already)){ break;}
$i += 1;
}
// end overlap check

// String length check
if($guess_length != $string_length){ $length_fail = true;}

// Letter check
$letter_check = str_intersect($guess, $unused);
if(!empty($letter_check)) { $letter_fail = true;}
// Now show error messages, if any
if(isset($space_error) || isset($overlap_fail) || isset($length_fail) || isset($letter_fail) || isset($guessed_already)){
// Now show error messages
	echo "<div class='error'>Your solution attempt is not valid, because:</div>";
if(isset($space_error)){echo "• The spaces aren't in their proper places.<br>";}
if(isset($length_fail)){echo "• It doesn't have the same number of characters as the puzzle.<br>";} 
if(isset($letter_fail)){echo "• It contains letters that are known to be unused.<br>";} 
if($overlap_fail == true){echo "• Known letters aren't in their proper places.<br>";}
if($guessed_already == true){echo "• It contains a letter that has already been guessed in a blank space it is known not to be in.<br>";}
$fail = 1;   
} // end error messages
} // end alt path to 'no characters'
} // end 'check for errors'
if(isset($_SESSION['solved']) || $fail == 0){
    if(!isset($_SESSION['solved'])){ $_SESSION['solved'] = $guess; } else{
        $guess = $_SESSION['solved'];}
	// solve attempt
	if($guess == $string || $_SESSION['solved'] == $string){
	echo "<div class='congrats'><b><span class='z5'>Congratulations!</span> <span class='z5'>You have solved the puzzle!</span></b></div>";
	 echo '<span class="z6">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	} else
	{echo "<b><i><span class='sorry'><span class='z6'>Sorry</i></span></span>, you failed to solve the puzzle.</b><br>";
	 echo "The solution was:<br>";
	 echo '<span class="z6">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	}
}
}
 // end solve section
	else { // Guess attempt
if(isset($_SESSION['lettered'])){
echo "<b>You guessed all the letters in the puzzle.</b><br>";
echo '<span class="z6">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
} elseif (isset($used_guess)){ echo "You've already guessed this letter.<br>";}
elseif($guess_length == 1){
    $guess_count += 1;
		if($problem_dup == $problem){
		echo "<b>You have guessed all the letters in the puzzle!</b><br>";
	 echo '<span class="z6">"', $nicestring, '"</span>'; echo "<br><i>{$puzzle['puzzle_blurb']}</i><br><br>";
	$_SESSION['lettered'] = true;
		}
	} elseif (isset($_POST['guess'])) {echo "A guess must consist of a single Latin alphabetic character.<br>";}
	} // end guess attempt
// Now to show things for everybody
	  echo "<div class='puz_box"; 
	  if(isset($_SESSION['lettered']) || isset($_SESSION['solved'])){echo " won";} 
	  echo "'><span class='z6'>$render_string</span></div>";
	  echo "<br>";
	  if(!empty($puzzle['puzzle_clue'])){ echo "<span class='z5'>Clue: {$puzzle['puzzle_clue']}</span><br>";}
	  if(isset($puzzle['puzzle_clone'])) { 
		$old_date = date_create_immutable_from_format("Y-m-d", $puzzle['puzzle_clone']);
		$old_date = date_format($old_date, "l, F j, Y");
		 echo "<span class='z3'>This puzzle was originally released $old_date.</span><br>"; }
	  if(empty($unused)){$unused_string = "None yet";} else {$unused_string = $unused;}
	  echo "Unused letters: $unused_string<br>";
echo "Guesses used: <b>$guess_count</b><br>";

function str_intersect($string1, $string2){
$string1 = mb_str_split($string1, 1, "UTF-8");
$string2 = mb_str_split ($string2, 1, "UTF-8");
$result = array_intersect($string1, $string2);
//	if(empty($result)){$result = false;}
return $result;
}

if(!isset($_SESSION['solved']) && !isset($_SESSION['lettered'])):
?>
To guess a letter, enter a single alphabetical character in the text field.<br>To try to solve, type what you think the solution is in the text field and trigger the checkbox.<br>
In either case, hit the submit button afterwards.<br>
<form method="POST" name="puzzle_form" action="index.php?s=fitb">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="text" id="guess" name="guess" autocomplete="off">
<input type="checkbox" id="solve" name="solve"><br>
<input type="submit" value="Submit">
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
</div>
<footer class="local">
<span class='z3'>Version: 1.1</span>
</footer>
</center>