<?php 
?>
<form method="POST" action="">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="hidden" name="theme">
Theme Types<br>
<?php
foreach($theme_array as $key => $value): ?>
<input type="radio" name="theme" value="<?=$key?>" id="<?=$key?>"
<?php if(isset($_SESSION['theme']) && $_SESSION['theme'] == $key){
echo "checked";}?>
>
<label for="<?=$key?>"><?=$value?></label><br> <?php
endforeach; ?>

<input type="submit" value="Update Settings">
</form>
<a href='index.php?<?php if(isset($_SESSION['loc'])){ echo $_SESSION['loc'];}?>'>Back to Previous Page</a>