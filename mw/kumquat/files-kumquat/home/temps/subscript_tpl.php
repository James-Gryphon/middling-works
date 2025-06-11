<center>
<div class="main_box single">
<div class="title_header"><h3>Manage Newsletter Subscription</h3></div><br>
<div class="main_box_content">
<div class="con_box">
<?php
if(isset($filter)){
    if($filter){
        if($e == 0)
        {
            echo "<b>You have been successfully unsubscribed!</b><br><br>";
        } 
        else 
        {
            echo "<b>You have been successfully subscribed!</b><br><br>";
        }
    }
    elseif($filter === FALSE)
    {
        echo "This email was not valid."; echo "<br>";
    }
}
?>
Enter your email address here to subscribe (or unsubscribe, if you already have subscribed) to the gPress newsletter.
<form method="POST" action="">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="email" name="email" id="email"><input type="submit">
</form>
</div>
</div>
</div>
</center>