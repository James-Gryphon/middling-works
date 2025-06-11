<?php $res = "main/gpress.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<a href="#most_specific" class="skip">Jump to content</a>
<a id="table_of_contents" class="skip">Table of Contents</a>
<!-- 
Buttons and labels here
-->
<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="first_box_checker" class="title_header"><h2>Categories</h2></label>
<input type="radio" id="second_box_checker" name="cat_buttons" class="hidden box_checker" <?php if(!isset($specific_post)){echo "checked";}?>>
<label for="second_box_checker" class="title_header" tabindex="-1"><h2><?php if(!isset($topic)){echo "Recent Posts";} else { echo $first_level_array[$topic]['name']; }?></h2></label>
<?php if(isset($specific_post)): ?>
<input type="radio" id="third_box_checker" name="cat_buttons" class="hidden box_checker" checked>
<label for="third_box_checker" class="title_header" tabindex="-1"><h2>Post</h2></label>
<?php endif; ?>
<br>
<!-- 
Print the main_box_contents
-->
<div class="main_box_content first">
<div class="con_box">
<span class='z4'><a href='index.php?a=gpress'>Recent Posts</a></span>
<span class='z3'><br></span>
<?php if(empty($specific_post)): ?>
    <span class='z3'>newest post <?=qdateBuilder($most_recent_post['created'])?></span><br>
    <span class='z3'><a href='index.php?a=gpress&d=<?=say($most_recent_post['post_id'])?>'><?=$most_recent_post['post_name']?></a></span>
<?php endif; ?>
</div>
<hr>
<?php foreach($first_level_array as $cat => $value): ?>
    <div class="con_box">
    <span class='z4'><a href='index.php?a=gpress&t=<?=$value['topic_id']?>'><?=$value['name']?></a></span>
    <br><span class='z3'>(posts: <?=$value['count']?>)</span><br>
    <?php // if(empty($specific_post)): ?>
    <span class='z3'>newest post <?=qdateBuilder($value['post']['created'])?></span><br>
    <span class='z3'><a href='index.php?a=gpress&t=<?=$value['topic_id']?>&d=<?=say($value['post']['post_id'])?>'><?=$value['post']['post_name']?></a></span>
    <?php // endif; ?>
    </div>
    <hr>
<?php endforeach; ?>
</div>

<?php
// end first level
// second level print
// Build a sorting link
if(empty($specific_post)): ?> <a id="most_specific"></a> <?php endif; ?>	
<div class="main_box_content second">
<?php
$link_prototype = "index.php?a=gpress";
if(isset($topic)){ $link_prototype .= "&t=$topic";}
if($sort != "created"){$link_prototype .= "&sort=$sort";}
if(isset($_GET['dir']) && $_GET['dir'] === "on"){$link_prototype .= "&dir=on";}
if($max_pages > 1):
if(!empty($specific_post)){ $link_prototype .= "&d={$specific_post['post_id']}";}
$prev_page = $page - 1;
if($prev_page <= 1){ $prev_page = false;}
$next_page = $page + 1;
if($next_page >= $max_pages){ $next_page = false;}

// Build the pager
$pager = "<form name='page_switcher' method='GET' action='"; $pager .= $link_prototype; $pager .= "'><span class='z3'><b>Pages:</b></span> ";
$pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=1'>1</a> ";
if ($prev_page !== false){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$prev_page'>$prev_page</a> "; }
$pager .= "<input type='number' name='p' id='p' value='"; $pager .= $page; $pager .= "'><input type='submit' value='Go'> ";
if ($next_page !== false){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$next_page'>$next_page</a> "; }
if($max_pages > 1){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$max_pages'>$max_pages</a>"; }
$pager .= "</form>";
echo "<div class='page_box top'>$pager</div>";
endif;
?>

<?php
foreach($topic_posts as $key => $value):
?>
<div class='con_box'>
<?php
if(!isset($specific_post) || $value['post_name'] !== $specific_post['post_name'])
{
    echo "<a href='index.php?a=gpress";
    if(isset($topic)) {echo "&t={$topic}";}
    if($page > 1){echo "&p=$page";}
    if($sort != "created"){echo "&sort=$sort";}
    if(isset($_GET['dir']) && $_GET['dir'] === "on"){echo "&dir=on";}
    echo "&d=", $value['post_id'], "'>", say($value['post_name']), "</a>";
    echo "<div class='meta_data'><span class='z3'>first created ", qdateBuilder($value['created']), "</span><br>";
    if($value['created'] != $value['updated']){ echo "<span class='z3'>last updated ", qdateBuilder($value['updated']), "</span><br>"; }
    if(!empty($value['topics']))
    {
        echo "<span class='z3'>topics: ";
        foreach($value['topics'] as $key => $top_value)
        {
            echo "<a href='index.php?a=gpress&t=", say($key), "'>{$top_value}</a> ";
        }
    }
    echo "</span></div>";
    ?>
    <div class="compress">
    <?php
    $string = strstr($value['post_text'], "\n", true);
    $string .= "<a href='index.php?a=gpress";
    if(!empty($topic)) {$string .= "&t=$topic";}
    if($page > 1){$string .= "&p=$page";}
    if($sort != "created"){$string .= "&sort=$sort";}
    if(isset($_GET['dir']) && $_GET['dir'] === "on"){$string .= "&dir=on";}
    $string .= "&d=";
    $string .= $value['post_id'];
    $string .= "'>read entire post</a>";
    echo($string);
    echo "</div>";
    echo "</div><hr>";
} 
else 
{
    echo "<i>", say($value['post_name']), " (currently selected post)</i><br><hr>";
}
//    echo metaDataMaker($key, $value, $cats);
    endforeach;
?>
<?php
if($max_pages > 1):
    echo "<div class='page_box bottom'>$pager</div>";
endif;
?>
<div class="sort_box"><form method="GET" name="post_sorter">
<?php
echo "<input type='hidden' name='a' value='gpress'>";
   if(!empty($topic)) {echo "<input type='hidden' name='t' value='$topic'>";}
   if($page > 1){echo "<input type='hidden' name='p' value='$page'>";}
   if(!empty($specific_post)){ echo "<input type='hidden' name='d' value='{$specific_post['post_id']}'>";}
?>
<span class='z3'>sort: </span><select name="sort"><option value="created" <?php if($sort === "created"){echo "selected";} ?>>Created</option><option value="updated" <?php if($sort === "updated"){echo "selected";} ?>>Updated</option><option value="post_name" <?php if($sort === "post_name"){echo "selected";} ?>>Post Name</option></select><input type="checkbox" name="dir" id="dir" <?php if(isset($_GET['dir'])){echo "checked";}?>><label for="dir"><span class='z3'>Old? </span></label><input type="submit" value="Sort"></form></div>
</div>
</div>
<?php
// begin third level print
if(!empty($specific_post)):
?>
    <a id="most_specific"></a>
    <div class="main_box_content third">
    <div class="con_box">
    <?php
    // postOnIndex(key($specific_post), $topic);
    echo "<a href='index.php?a=gpress";
    if(isset($topic)) {echo "&t={$topic}";}
    if($page > 1){echo "&p=$page";}
    if($sort != "created"){echo "&sort=$sort";}
    if(isset($_GET['dir']) && $_GET['dir'] === "on"){echo "&dir=on";}
    echo "&d=", $specific_post['post_id'], "'>", say($specific_post['post_name']), "</a>";

    //    echo metaDataMaker($key, $value, $cats);
    echo "<div class='meta_data'><span class='z3'>first created ", qdateBuilder($specific_post['created']), "</span><br>";
    if($specific_post['created'] != $specific_post['updated']){ echo "<span class='z3'>last updated ", qdateBuilder($specific_post['updated']), "</span><br>"; }
    if(!empty($specific_post['topics']))
    {
        echo "<span class='z3'>topics: ";
        foreach($specific_post['topics'] as $key => $top_value)
        {
            echo "<a href='index.php?a=gpress&t=", say($key), "'>{$top_value}</a> ";
        }
        echo "</span>";
    }
    echo "</div>";
    // postGetter(key($specific_post), false, $topic);
    $string = $specific_post['post_text'];
    echo($string);
    ?>
    </div>
    </div>
    </div>
<?php
endif;
// end third level
$res = "main/gpress.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>