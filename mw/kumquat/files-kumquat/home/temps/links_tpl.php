<a href="#most_specific" class="skip">Jump to content</a>

<a id="table_of_contents" class="skip">Table of Contents</a>
<div class="title_header"><h3>Table of Contents</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<?php
foreach($content_array as $content_section){
echo "<span class='z3'><a href='#{$content_section['anchor']}'>{$content_section['title']}</a></span><hr>";
}
?>
</div>
</div>
</div>

<a id="most_specific" class="skip">Content</a>
<div class="main_box second">
<div class="title_header"><h3>Hyperlinks</h3></div><br>
<div class="main_box_content">
<div class="con_box">
<p>This is a selection of sites; some of them I use so often that I've included in my custom home page <span class='z3'>(which isn't here)</span>. Others, <i>not so much</i>... but they may be edifying, useful, and/or interesting to visitors.</p>
<hr>
<?php
foreach($content_array as $content_section){
echo "<a id='{$content_section['anchor']}'></a><h4>{$content_section['title']}</h4><br><span class='z3'><i>{$content_section['text']}</i></span><br>";
foreach($content_section['links'] as $link_section){
    echo "<a href='{$link_section['url']}'>{$link_section['name']}</a>";
    if (!empty($link_section['blurb'])){ echo " - <span class='z3'>{$link_section['blurb']}</span>";}
    echo "<br>";
}
echo "<hr>";
}
?>
</div>
</div>