<?php $res = "fitb/local.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<?php
require_once "".path."/fitb/temps/{$action}_cli_tpl.php";
?>
