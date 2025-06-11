<div class="title_header"><h3>Our Sites</h3></div><br>
<div class="main_box_content">
<?php
foreach($links as $link_section)
{
    echo "<a href='{$link_section['url']}'>{$link_section['linktext']}</a><i>: {$link_section['blurb']}</i><br>";
}
?>
</div>
