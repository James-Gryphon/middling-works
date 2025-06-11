<center>
<div class="title_header"><h3>Account Settings</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<form method="POST" action="">
<?php
if(isset($account_changed)){echo "<span class='success_text'>• <span class='z3'>", "Your account settings were successfully changed.", "</span></span><br>";}
if(isset($account_not_changed)){echo "<span class='notice_text'>• <span class='z3'>", "You didn't make any changes, so nothing was done.", "</span></span><br>";}
?>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="hidden" name="account_sets">
<label for="username">Username</label><br>
<b><?=say($result['username'])?></b><br>
<label for="new_password">Password</label><br>
<input type="password" id="new_password"
<?php
if(!empty($errors['password'])):
?>
class="error_wrap"
<?php endif; ?>
name="new_password"><br>
<?php
if(!empty($errors['password'])):
foreach($errors['password'] as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
}
endif;
?>

<label for="email">Email</label><br>
<input type="email" id="email" value='<?=say($result['email'])?>'
<?php
if(!empty($errors['email'])):
?>
class="error_wrap"
<?php endif; ?>
 name="email"><br>
<?php
if(!empty($errors['email'])):
foreach($errors['email'] as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
}
endif;
?>
<hr>
<label for="password">Current Password</label><br>
<input type="password" id="password" name="password"
<?php
if(!empty($errors['cur_password'])):
?>
class="error_wrap"
<?php endif; ?>><br>
<?php
if(!empty($errors['cur_password'])):
foreach($errors['cur_password'] as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
}
endif;
?>
<br>
<input type="submit" value="Update Settings">
</form>
<a class='z3' href='index.php?a=subscribe'>Subscription Manager</a>
</div>
</div>
</div>
</center>