<center>
<div class="title_header"><h3>Registration</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<?php
// Section 2. Verification

if(!empty($_POST) && empty($errors)):
?>
<form method="POST" action="">
<p>If both this username and email address aren't already registered, an authentication code has been sent to the email address. Enter the code into this box to verify your ownership of it.</p>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="text" name="sess"><br>
<input type="submit">
<?php
else:
?>
<div>
<h4>About Registration</h4>
<p>Registering an account is increasingly helpful: it allows for membership subscriptions, saving preferences, and user-specific data. At present, the benefits of registration include:
<ul>
<li>It's possible to sign up for memberships.</li>
<li>"Ultimate" is playable.</li>
<li>"The Hunt" is playable.</li>
<li>Maths Map keeps track of and saves statistics.</li>
<li>Theme settings are saved.</li>
<li>Messages you post to Synchronous Messages are by default credited to your account.</li>
<li>Your name is shown in the Plus-Minus game.</li>
<li>...more to come?</li>
</ul>
</p>
<form method="POST" action="">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<label for="username">Username <span class="z3"><i>(no more than 20 characters)</i></span></label><br>
<input type="text"
<?php
if(!empty($errors['user'])):
?>
class="error_wrap"
<?php endif; ?>
name="username"><br>
<?php
if(!empty($errors['user'])):
foreach($errors['user'] as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
}
endif;
?>
<label for="email">Email</label><br>
<input type="email"
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
<label for="password">Password</label><br>
<input type="password" 
<?php
if(!empty($errors['password'])):
?>
class="error_wrap"
<?php endif; ?>
name="password"><br>
<?php
if(!empty($errors['password'])):
foreach($errors['password'] as $error => $value){
echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
}
endif;
?>
<input type="submit">
</form>
</div>
</div>
</center>
<?php
endif;