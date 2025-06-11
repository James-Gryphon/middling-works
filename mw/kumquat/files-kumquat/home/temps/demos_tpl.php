<center>
<div class="title_header"><h3>Demonstrations</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<?php
if(isset($_GET['run']) && file_exists("".path."/home/demos/{$_GET['run']}.php")){
require_once("".path."/home/demos/{$_GET['run']}.php");
echo "<hr><a href='index.php?a=demos'>Back to demonstration listing...</a>";
} else {
foreach($content_array as $key => $value)
{
    echo "<a href='index.php?a=demos&run={$value['url']}'>{$value['name']}</a><br>{$value['desc']}<br>";
}
}
?>
</div>
</div>
</center>