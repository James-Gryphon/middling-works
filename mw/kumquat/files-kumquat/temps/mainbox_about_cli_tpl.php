<div class="title_header"><h3><?=$local_site_name?></h3></div><br>
<?php
foreach($content_array as $content_section){
echo "<a id='{$content_section['anchor']}'></a><h4><i>{$content_section['title']}</h4></i>{$content_section['text']}<hr>";
}
?>
</div>
