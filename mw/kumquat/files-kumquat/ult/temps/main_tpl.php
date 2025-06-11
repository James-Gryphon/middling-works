<?php $res = "ult/local.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php
require_once "".path."/ult/temps/{$action}_tpl.php";
?>
<center>
<footer class="local">
<span class='z3'>Version: 1.0.0</span>
</footer>
</center>
