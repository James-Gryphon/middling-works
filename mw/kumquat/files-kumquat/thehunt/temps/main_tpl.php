<?php $res = "thehunt/local.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php
require_once "".path."/thehunt/temps/{$action}_tpl.php";
?>
<center>
<footer class="local">
<span class='z3'>Version: 1.0.1</span>
</footer>
</center>