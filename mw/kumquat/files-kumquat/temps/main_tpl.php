<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset="utf-8">
<title><?php
if(isset($local_site_name))
{
    echo $local_site_name;
}
elseif(!empty($site))
{
    echo $site;
}
else 
{
    echo(sitename); 
}
?></title>
<meta name="description" content="<?php if(isset($local_site_meta)){echo $local_site_meta;} else {echo "Solve word puzzles, exchange messages, practice mathematics, and more, all in software projects run and maintained by a single developer.";}?>">
<?php $res = "files/base.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php $res = "files/{$_SESSION['theme']}.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<a href="#local_navigation" class="skip">Jump to main section</a>
<a href="#footer" class="skip">Jump to footer</a>
<header tabindex="-1">
<table class="main" border="1px">
<tbody>
<tr>
<td class='h_button home'><a href="index.php">Home</a>
<?php
foreach($loc_button_array as $key => $button):
    if(!empty($button)):?> 
    <td class='h_button local<?php
    if ($key == $action){
    echo " active'>$button ";}
    else { echo "'><a href='index.php?s=$site_key&a=$key'>$button</a>";}
    echo "</td> ";endif;
endforeach;
if(!isset($_SESSION['id'])): ?>
<td class="h_button" id="register"><a href="index.php?a=register&z">Register</a></td>
<td class="h_button" id="login"><a href="index.php?a=login&z">Login</a></td>
<?php else: ?>
<td class="h_button" id="mail"><a href="index.php?a=mail">Mail</a></td>
<td class="h_button" id="account"><a href="index.php?a=account&z">Account</a></td>
<td class="h_button" id="logout"><a href="index.php?a=logout">Logout (<?=$_SESSION['username']?>)</a></td>
<?php endif; ?>
</tr>
</tbody>
</table>
</header>
<main>
<a id="local_navigation" class="skip">Main section</a>
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
    <span class="z4"><a href="index.php?s=home&a=about">site by James Gooch, 2025</a></span><br>
    <a href="index.php?a=terms&z">Terms of Use</a> - <a href="index.php?a=privacy&z">Privacy Policy</a> - <a href="index.php?a=credits&z">Credits</a> - <a href="index.php?a=affiliates&z">Affiliates</a><br>
    <span class="z2"><i>ran in <?=round($time/1000000,3)?> ms</i></span><br>
    <?php /* if($_SESSION['theme'] === "light"){$alt_theme = "dark";} else { $alt_theme = "light";} */?>
    <!-- The super secret theme link
    <a href='index.php?<?php /* if(!empty($_SESSION['loc'])){ echo "{$_SESSION['loc']}&";} */?>theme=<?php /* $alt_theme */?>&z'>Use the <?php/* $alt_theme */?> theme</a>-->
</center>
</footer>
<div class="closebox"></div>
<?php
// one new thing (8/7/24) to help debugging - for kumquat only
if(defined("debug") && $_SERVER['REMOTE_ADDR'] == "162.198.148.192" && isset($_SESSION['id']) && $_SESSION['id'] === 1): ?>
<input type='checkbox' class='dpancheck'>
<div class='dpanc'>
    <div class="dpanl"><?php var_see($_SESSION, "Session: ");?></div>
    <div class="dpanl"><?php var_see($_GET, "Get: ");?></div>
    <div class="dpanl"><?php var_see($_POST, "Post: ");?></div>
    <div class="dpanl"><?php var_see($debug, "Debug: ");?></div>
</div>
<?php endif; ?>
</body>
</html>
