<?php $res = "maths/local.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php
    if(isset($_SESSION['th']) && file_exists("maths/{$_SESSION['th']}.css")){
        echo "<link rel='stylesheet' href='maths/{$_SESSION['th']}.css'>"; }       
?>
<?php
require_once "".path."/maths/temps/{$action}_tpl.php";
?>
<center>
<footer class="local">
<span class='z3'>Version: 1.2.3</span>
</footer>
    </center>
