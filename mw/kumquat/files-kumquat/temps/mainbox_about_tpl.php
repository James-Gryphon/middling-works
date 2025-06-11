<a id="most_specific" class="skip">Content</a>
<center>
<div class="title_header"><h3><?=$local_site_name?></h3></div><br>
<div class="main_box_content single half_width">
<div class="sub_box">
<div class="con_box left">
<?php
foreach($content_array as $content_section){
echo "<a id='{$content_section['anchor']}'></a><details><summary><i>{$content_section['title']}</i></summary>{$content_section['text']}</details><hr>";
}
?>
</div>
</div>
</div>
</center>