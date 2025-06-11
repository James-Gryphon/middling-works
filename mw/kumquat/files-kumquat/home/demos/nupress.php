
<?php
/* All the things we need:
1st level -
The most recent post ("Recent Posts")
Each topic, the number of posts in it, and the most recent post

2nd level-
All posts in topic, and each topic for each post

3rd level - all data for one post, and the topics that correspond to it
*/

// First level
// Most recent post
$sql = "SELECT * from home_gpress_posts ORDER BY 'created' LIMIT 1";
$sth = $pdo->prepare($sql);
$sth->execute();
$most_recent_post = $sth->fetch();

// Each topic - need topic name, number of posts associated with it, and the name and time of last post
// thanks to Tom Davies and Matt Mazur for the idea of using a specific category to narrow queries
// https://mattmazur.com/2017/11/01/counting-in-mysql-when-joins-are-involved/
// thanks to Gajus on Stack Overflow for documenting multiple query support in PDO
// https://stackoverflow.com/questions/11271595/pdo-multiple-queries
$sql = "SELECT count(`t1`.`post_id`) as `count`, max(`t1`.`post_id`) as `last_post`, `t1`.`topic_id`, `t2`.`name` FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_topics` as `t2` ON `t1`.`topic_id` = `t2`.`topic_id` GROUP BY `t1`.`topic_id`";
$sth = $pdo->prepare($sql);
$sth->execute();
$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
$ids = array();
foreach($categories as $category){
$ids[$category['topic_id']] = $category['last_post'];
}
$ids = array_unique($ids);
$ids = implode(",", $ids);
$sql = "SELECT `post_id`, `post_name`, `created` FROM `home_gpress_posts` WHERE post_id IN ($ids)";
$sth = $pdo->query($sql);
$sth->execute();
$posts = $sth->fetchAll(PDO::FETCH_ASSOC);
$post_table = array();
foreach($posts as $key => $value){
$post_table[$value['post_id']] = $value;
}
$first_level_array = array();
foreach($categories as $category => $value){
$first_level_array[$category + 1] = $value;
$first_level_array[$category + 1]['post'] = $post_table[$value['last_post']];
}
// end first level
var_see($categories, "Categories");
var_see($first_level_array, "First Level Array");
// second level
if(isset($_GET['t']) && array_key_exists($_GET['t'], $first_level_array)):
$topic = $_GET['t'];
endif;
if(isset($_GET['p']) && is_int($_GET['p'])){$page = intval($_GET['p']);} else {$page = 1;}
$offset = ($page * page_count) - page_count;
$sort_table = array("post_name", "created", "updated");
if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_table)){ $sort = $_GET['sort'];} else {$sort = "created";}
if(isset($_GET['dir']) && $_GET['dir'] === 0){ $dir = "ASC";} else {$dir = "DESC";}
if(!empty($topic)){
$sql = "SELECT `t1`.*, `t2`.* FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_posts` as `t2` ON `t1`.`post_id` = `t2`.`post_id` WHERE `topic_id` = ? ORDER by `$sort` $dir LIMIT ".page_count." OFFSET $offset"; 
$sth = $pdo->prepare($sql);
$sth->execute([$topic]);
}
else {
$sql = "SELECT `t1`.*, `t2`.* FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_posts` as `t2` ON `t1`.`post_id` = `t2`.`post_id` ORDER by `$sort` $dir LIMIT ".page_count." OFFSET $offset"; 
$sth = $pdo->prepare($sql);
$sth->execute();
}
$topic_posts = $sth->fetchAll(PDO::FETCH_ASSOC);

// third level
if(isset($_GET['d'])){
    $sql = "SELECT * FROM `home_gpress_posts` WHERE `post_id` = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$_GET['d']]);
    $specific_post = $sth->fetch();
    $sql = "SELECT * FROM `home_gpress_topic_links` WHERE `post_id` = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$_GET['d']]);
    $post_topics = $sth->fetch(PDOStatement::fetchColumn);
    var_see($post_topics, "Post Topic IDs");
    foreach($categories as $category){
    $post_topics[$category['id']] = $category['name'];
    }
var_see($specific_post, "Specific Post");
var_see($post_topics, "Post Topics");
}
?>

<a href="#most_specific" class="skip">Jump to content</a>

<a id="table_of_contents" class="skip">Table of Contents</a>
<div class="main_box first <?php if(isset($specific_post)){ echo "short";}?>">
<div class="title_header"><h2>Categories</h2></div><br>
<div class="main_box_content">
<div class="sub_box">
<div class="con_box recent">
<span class='z4'><a href='index.php?a=gpress'>Recent Posts</a></span>
<span class='z3'><br></span>
</div>
<?php
if(!isset($specific_post)): ?>
<div class="con_box compress">
<span class='z3'>most recent post <?=qdateBuilder($most_recent_post['created'])?></span><br>
<span class='z3'><a href='index.php?a=gpress&d=<?=sfsay($most_recent_post['post_id'])?>'><?=$most_recent_post['post_name']?></a></span>
</div>
<?php endif; ?>
</div><hr>
<?php
foreach($first_level_array as $cat => $value):
?>
<div class="sub_box">
<div class="con_box">
<span class='z4'><a href='index.php?a=gpress&t=<?=$value['topic_id']?>'><?=$value['name']?></a></span>
<br><span class='z3'>(posts: <?=$value['count']?>)</span>
</div>
<?php if(!isset($specific_post)): ?>
<div class="con_box compress">
    <span class='z3'>most recent post <?=qdateBuilder($value['post']['created'])?></span><br>
    <span class='z3'><a href='index.php?a=gpress&t=<?=$value['topic_id']?>&d=<?=sfsay($value['post']['post_id'])?>'><?=$value['post']['post_name']?></a></span>
</div>
<?php endif; ?>
</div><hr>
    <?php
endforeach;
?>
</div>
</div>

<?php
// end first level
// second level print
if(!isset($specific_post)): ?> <a id="most_specific"></a> <?php endif; ?>	
<div class="main_box second <?php if(isset($specific_post)){echo "short";} else {echo "last";}?>">
<div class="title_header"><h2><?php if(!isset($topic)){echo "Recent Posts";} else { echo $first_level_array[$topic]['name']; }?></h2></div><br>
<div class="main_box_content">
<?php
    foreach($topic_posts as $key => $value):
?>
<div class='sub_box'>
<div class='con_box'>
<?php
echo "<a href='index.php?a=gpress";
if(!isset($topic)) {echo "&t={$value['topic_id']}";}
echo "&d=", $value['post_id'], "'>", sfsay($value['post_name']), "</a>";

//    echo metaDataMaker($key, $value, $cats);
echo "<div class='meta_data'><span class='z3'>last updated ", qdateBuilder($value['created']), "</span><br>";
echo "<span class='z3'>topics: ";
foreach($post_topics as $post_topic){
echo "<a href='index.php?a=gpress&t=", sfSay($post_topic['topic_id']), "'>{$post_topic['name']}</a> ";
}
echo "</span>";
    ?>
    <?php if(!isset($specific_post)): ?>
<div class="compress">
<?php
    echo postGetter($key, true, $topic);
    echo "</div>";
    endif;
    echo "</div></div><hr>";
    endforeach;
?>
</div>
</div>
<?php

// begin third level print
if(isset($specific_post)):
?>
<a id="most_specific">
<div class="main_box third last" tabindex="-1">
<div class="title_header"><h2><?=$specific_post['post_name']?></h2></div><br>
<div class="main_box_content">
<div class="sub_box">
<div class="con_box">
<?php
// postOnIndex(key($specific_post), $topic);
echo "<a href='index.php?a=gpress";
if($topic != "Recent Posts") {echo "&t=$topic";}
echo "&d=", sfsay($key), "'>$key</a>";
// metaDataMaker(key($third), $specific_post[key($specific_post)], $cats);
echo "<div class='meta_data'><span class='z3'>last updated ", dateBuilder($value), "</span><br>";
$topics = fileTopics($key, $cats);
topicPrinter($topics);
echo "</div>";

// postGetter(key($specific_post), false, $topic);
if(isset($file) && is_readable("home/texts/{$file}")){
    $string = file_get_contents("home/texts/{$file}");
    if($lb == true){
    $string = strstr($string, "\n", true);
    $filed = sfsay($file); $string .= "<a href='index.php?a=gpress"; 
    if($topic != "Recent Posts") {$string .= "&t=$topic";}
    $string .= "&d=$filed'>read entire post</a>";}
    echo $string;
}
?>
</div>
</div>
</div>
</div>
<?php
endif;
// end third level