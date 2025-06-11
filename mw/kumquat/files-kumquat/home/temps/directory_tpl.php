<center>
<a id="most_specific" class="skip">Content</a>
<div class="title_header"><h3>Our Features</h3></div><br>
<div class="main_box_content single half_width">
<?php
foreach($links as $link_section){
    echo"<span class='linkbox'>
    <div class='z4'><div><a href='{$link_section['url']}'><img longdesc='' title='{$link_section['name']} logo}' src='stuff/{$link_section['svg']}";
    if(isset($_SESSION['theme']) && ($_SESSION['theme'] != "beige" && $_SESSION['theme'] != "light")){ echo "_{$_SESSION['theme']}";}
    echo ".svg'><br><span>{$link_section['linktext']}</a></div></div><div><i>{$link_section['blurb']}</i></div></span>";
}
?>
</div>
</center>