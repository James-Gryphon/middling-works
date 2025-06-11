<?php
// Section 1 - the main form
?>

<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="first_box_checker" class="title_header"><h2>Input Form</h2></label>
<input type="radio" id="second_box_checker" name="cat_buttons" class="hidden box_checker" <?php if(isset($batch)){echo "checked";}?>>
<label for="second_box_checker" class="title_header" tabindex="-1"><h2>Messages</h2></label><br>

<div class="main_box_content first">
<div class="con_box">
<form method="POST" action="#sync_messages" autocomplete=off>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<h3>Main Form</h3><br>
<label for="username">Username <span class='z3'>(optional)</span></label><br>
<input type="text" name="username" autocomplete=off
<?php if((!isset($errors["batch"]['failed_authentication'])) && empty($used) && !isset($errors["batch"]['user_length'])):
echo "value='", say($username), "'";
else:
echo "class='error_wrap'";
endif;
?>
><br>
<?php
if(!empty($used) && empty($auth)):
foreach($used as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice("$error"), "</span></span><br>";
}
endif;
if(isset($errors["batch"]['user_length'])):
	echo "<span class='notice_text red'>• <span class='z3'>", notice("user_length"), "</span></span><br>";
	endif;
?>
<label for="password">Password <span class='z3'>(optional)</span></label><br>
<input type="password" name="password" autocomplete=off><br>
<?php if(isset($errors["batch"]['failed_authentication'])):
echo "<span class='notice_text red'>• <span class='z3'>", notice("failed_authentication"), "</span></span><br>";
endif; 
?>
<label for="batch">Batch <span class='z3'>(leave blank to create a new one)</span></label><br>
<input type="text" 
<?php
if(isset($errors["batch"]['invalid_batch_choice'])):
?>
class="error_wrap" 
<?php
else: if (!empty($batch)){ echo "value='", say($batch), "'"; }
endif; 
?>
name="batch" autocomplete=off><br>
<?php
if(isset($errors["batch"]['invalid_batch_choice'])):
echo "<span class='notice_text red'>• <span class='z3'>", notice("invalid_batch_choice"), "</span></span><br>";
endif;
?>
<label for="count">User Count <span class='z3'>(used when creating, ignored otherwise)</span></label><br>
<input type="number" name="count" autocomplete=off value="2"><br>
<label for="message">Text <span class='z3'>(leave blank to read without making/updating a post)</span></label><br>
<textarea name="message">
<?php if (!empty($errors["batch"]) && !empty($_POST['message'])){echo say($_POST['message']);} 
if(isset($errors["batch"]['message_length'])){
echo "<span class='notice_text red'>• <span class='z3'>", notice("message_length"), "</span></span><br>";
}
?>
</textarea><br>
<input type="submit"><br>
</form>
</div>
</div>
<?php
// end section 1
// Section 5, Read Batch
?>
<a id="sync_messages"></a>
<div class="main_box_content second message_box">
<div class="con_box">
<?php
// try to read the batch - needs only a valid batch
if($batch !== false):
	$query = $pdo->prepare("SELECT * FROM sm_msgs WHERE batch = :batch");
	$query->execute(
		[
		':batch' => $batch
		]
	);
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

// fetch roster
$query = $pdo->prepare("SELECT * FROM sm_users WHERE batch = :batch");
$query->execute(
	[
	':batch' => $batch
	]
);
$roster = $query->fetchAll(PDO::FETCH_ASSOC);
$roster_count = count($roster);

// build roster
$rollcall = array();
foreach($roster as $key => $player){
	if(!empty($player['user_id'])){
	$rollcall[$player['local_id']] = "<u>";
	$temp = get_username($player['user_id']);
	$rollcall[$player['local_id']] .= $temp[0];
	$rollcall[$player['local_id']] .= "</u>";
	}
	elseif(!empty($player['username'])){
	$rollcall[$player['local_id']] = say($player['username']);
	}
	else {
	// don't *really* show people's IPs here...	
	$rollcall[$player['local_id']] = "<i>Guest #$key</i>";
	}	
}
// end fetch roster

$i = count($result);
$sets = ceil($i / $count);
echo "Batch <b><span class='z3'>{$batch}</span></b> <br>";
echo "$i total messages in $sets round(s)<br>";
$q = $count * $sets; // the total if all the rounds were full
$q = $q - $i; // the difference between the actual total and the max total
$threshold = $i - $q; // the number of messages that should be considered 'pending'?
$i -= 1;
echo "$q out of $count messages submitted in current round<br><br>";
// the current round is special
while ($i >= $threshold){
if(isset($auth) && $auth === true &&
	$result[$i]['local_id'] == $local_id){
echo "<i>Your pending message: ({$rollcall[$result[$i]['local_id']]}) <br>";
echo say($result[$i]['msg']); echo "</i><br><br>";
$i = $threshold;
}
$sets -= 1;
$i -= 1;
}
// now on to regular sets
$c = $count - 1;
while ($sets > 0){
	$c = $count -1;
echo "<b><span class='z3'>Round ", $sets, "</span></b><br>";
while($c >= 0){
echo "<span class='z3'>From: {$rollcall[$result[$i]['local_id']]}";
echo "</span><br>";
echo "<div class='msg'>", say($result[$i]['msg']), "</div>";
if($c > 0){echo "<br>";}
$c -= 1;
$i -= 1;
}
echo "<hr>";
$sets -= 1;
}

// display roster
echo "Roster: $roster_count out of $count users<br>";
foreach($rollcall as $callsign){
echo "$callsign<br>";
}
echo "<hr>";
else:
echo "<i>(this space left intentionally blank)</i>";
endif;

?>
</div>
</div>

<?php

/*
error messages -
wrong username or password
batch doesn't exist
no text message?
*/