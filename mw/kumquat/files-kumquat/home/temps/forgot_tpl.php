<center>
<div class="title_header"><h3>Reset Password</h3></div><br><br>
<div class="main_box_content single half_width">
<div class="con_box">
<form method="POST" action="" autocomplete=off>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>

<?php
if((isset($_POST['callsign']) && !empty($_POST['callsign'])) || isset($_POST['verify'])):
        // Rewrite this page; it's a mess
        if(isset($errors['reset_locked'])): 
            echo notice("resets_locked", "autoinline");
        else:
            if(!(empty($_POST['verify']) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']))
            {
                echo notice("failed_verification", "autoinline");
            }
    ?>
    If you submitted the username or email address of an existing account, a message with a verification code was sent to the email address associated with it. Enter that code into the box below.<br>
    <label for="verify"><b><span class='z3'>Verification Code</span></b></label><br>
    <input type="text" name="verify" id="verify" autocomplete=off><br>
    Enter the new password you want to use with this account here.<br>
    <label for="new_pass"><b><span class='z3'>Password</b></span></label><br>
    <input type="password" 
    <?php
    if(!empty($errors['password'])):
    ?>
    class="error_wrap"
    <?php endif; ?>
    name="new_pass" id="new_pass" autocomplete=off><br>
    <?php
    if(!empty($errors['password'])):
        foreach($errors['password'] as $error => $value)
        {
            echo "<span class='notice_text red'>• <span class='z3'>", notice($error), "</span></span><br>";
        }
    endif;
    ?>
    <input type="submit">
    <?php
    endif;
else:
// Section 3: Initial password reset form
?>
<label for="callsign"><span class='z3'><b>Username</b></span> <u>or</u> <span class='z3'><b>Email Address</span></b></label><br>
<input type="text" name="callsign" id="callsign"><br>
<input type="submit">
<?php
endif;
?>
</form>
</div>
</div>
</div>
</center>