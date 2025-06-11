<?php $res = "plusminus/local.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php
    if(isset($_SESSION['th']) && file_exists("plusminus/{$_SESSION['th']}.css")){
        echo "<link rel='stylesheet' href='plusminus/{$_SESSION['th']}.css'>"; }       
?>
<?php
require_once "".path."/plusminus/temps/{$action}_tpl.php";
?>