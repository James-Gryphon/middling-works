<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset="utf-8">
<title><?php echo "".sitename." > $site: "; if(isset($local_site_name)){echo $local_site_name;}else{echo $loc_button_array[$action];}?></title>
<meta name="description" content="<?php if(isset($local_site_meta)){echo $local_site_meta;} else {echo "Solve word puzzles, exchange messages, practice mathematics, and more, all in software projects run and maintained by a single human developer.";}?>">
<?php $res = "files/base.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php $res = "files/{$_SESSION['theme']}.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<main>
<!-- include content here via php-->
 <?php
if(!empty($page_container))
{
    require_once("".path."/$page_container");
}
else
{
    require_once("".path."/$local_path");
}
$time_end = hrtime(true);
$time = $time_end - $time_start;
?>
<!-- end (most) php content -->
</main>
<a id="footer" class="skip">Footer</a>
<footer class="main">
<center>
<br><br>
    <span class="z4"><i>"A classic never goes out of style."</i><br></span>
    <span class="z4"><a href="index.php?s=home&a=about">site by James Gooch, 2024</a></span><br>
    <a href="index.php?a=terms&z">Terms of Use</a> - <a href="index.php?a=privacy&z">Privacy Policy</a> - <a href="index.php?a=credits&z">Credits</a><br>
    <span class="z2"><i>ran in <?=round($time/1000000,3)?> ms</i></span><br>
</center>
</footer>
<div class="closebox"></div>
<?php
// one new thing (8/7/24) to help debugging - for kumquat only
if(defined("debug")): ?>
<div class='dpanp'>
<form method="POST">
<input type="text" id="command" name="command">
<input type="submit">
</form>
</div>
<div class='dpanc'>
    <div class="dpanl"><?php var_see($_SESSION, "Session: ");?></div>
    <div class="dpanl"><?php var_see($_GET, "Get: ");?></div>
    <div class="dpanl"><?php var_see($_POST, "Post: ");?></div>
    <div class="dpanl"><?php var_see($debug, "Debug: ");?></div>
</div>
<?php endif; ?>
</body>
</html>
