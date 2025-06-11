<center>

<?php
if (!empty($_SESSION['temp_post'])){ echo notice("saved_work");}
?>
<div class="title_header"><h3>Login</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<form method="POST" action="">
<?php

if(!empty($errors))
{ 
    foreach($errors as $error => $value)
    {
    echo notice($error, "autoinline");
    }
}

?>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<label for="callsign"><span class='z3'><b>Username</b></span> <u>or</u> <span class='z3'><b>Email Address</span></b></label><br>
<input type="text" name="callsign" id="callsign" value="<?php if(isset($_POST['callsign'])){echo say($_POST['callsign']);}?>"><br>
<label for="password">Password</label><br>
<input type="password" name="password"><br>
<input type="submit">
</form>
<span class='z3'><a href="index.php?a=forgot&z=1">click here if you want to reset your password</a></span>

</div>
</div>
</div>
</center>