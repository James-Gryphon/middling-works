
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
$local_site_name = "gPress";
$sql = "SELECT * FROM `home_gpress_posts` ORDER BY `created` DESC LIMIT 1";
$sth = $pdo->prepare($sql);
$sth->execute();
$most_recent_post = $sth->fetch();

if(isset($_SESSION['id']) && (!isset($_SESSION['gp_seen']) || !($_SESSION['gp_seen'])))
{ // Update your gPress view time - this seems to bend the definition of 'prefs' a bit
    $query = $pdo->prepare("REPLACE INTO `home_prefs` (`user_id`, `setting`, `value`) VALUES (:user_id, :setting, :value)");
    $query->execute([':user_id' => $_SESSION['id'], ':setting' => 'gp_seen', ':value' => 1]);
    $_SESSION['gp_seen'] = 1;
}

// Count
$sql = "SELECT count(*) as 'count' from home_gpress_posts ORDER BY 'created'";
$sth = $pdo->prepare($sql);
$sth->execute();
$all_post_count = $sth->fetch();
$all_post_count = $all_post_count['count'];

// Each topic - need topic name, number of posts associated with it, and the name and time of last post
// thanks to Tom Davies and Matt Mazur for the idea of using a specific category to narrow queries
// https://mattmazur.com/2017/11/01/counting-in-mysql-when-joins-are-involved/
// thanks to Gajus on Stack Overflow for documenting multiple query support in PDO
// https://stackoverflow.com/questions/11271595/pdo-multiple-queries
$sql = "SELECT count(`t1`.`post_id`) as `count`, max(`t1`.`post_id`) as `last_post`, `t1`.`topic_id`, `t2`.`name` FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_topics` as `t2` ON `t1`.`topic_id` = `t2`.`topic_id` GROUP BY `t1`.`topic_id` ORDER BY `t2`.`name`";
$sth = $pdo->prepare($sql);
$sth->execute();
$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
// Categories - contains the post count, last post, topic id, and topic name
$ids = array();
foreach($categories as $category){
// IDs - contains the 'last post' id associated with each topic
$ids[$category['topic_id']] = $category['last_post'];
}
$ids = array_unique($ids);
$ids = implode(",", $ids);
$sql = "SELECT `post_id`, `post_name`, `created` FROM `home_gpress_posts` WHERE post_id IN ($ids)";
$sth = $pdo->query($sql);
// Posts - contains all the post details for each post ID found in the IDs table.
$posts = $sth->fetchAll(PDO::FETCH_ASSOC);
// Now to make the posts show up in their proper order, so that you can use their post ID to access them
$post_table = array();
foreach($posts as $key => $value)
{
    $post_table[$value['post_id']] = $value;
}
// Now to add the posts to the "first level array"
$first_level_array = array();
foreach($categories as $category => $value)
{
    $first_level_array[$value['topic_id']] = $value;
    $first_level_array[$value['topic_id']]['post'] = $post_table[$value['last_post']];
}

// end first level
// second level
if(isset($_GET['t']) && array_key_exists($_GET['t'], $first_level_array)):
$topic = $_GET['t'];
$total_posts = $first_level_array[$topic]['count'];
$max_pages = ceil($total_posts / page_count);
$local_site_name = "gPress: {$first_level_array[$topic]['name']}";
else:
$total_posts = $all_post_count;
endif;
$max_pages = ceil($total_posts / page_count);
if(isset($_GET['p']))
{
    $intval = intval($_GET['p']);
    if($intval <= 1){ $page = 1;}
    elseif($intval > $max_pages){ $page = $max_pages;}
    else {$page = $intval;}
} else {$page = 1;}
$offset = ($page * page_count) - page_count;
$sort_table = array("post_name", "created", "updated");
if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_table)){ $sort = $_GET['sort'];} else {$sort = "created";}
if(isset($_GET['dir']) && $_GET['dir'] === "on")
{
    if($sort == "post_name"){ $dir = "DESC";}
    else {$dir = "ASC";}
}
else 
{
    if($sort == "post_name"){ $dir = "ASC";} 
    else $dir = "DESC";
}
// If topic is selected by user, then get results from that topic; otherwise, pick from all posts
if(!empty($topic))
{
    $sql = "SELECT `t1`.*, `t2`.* FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_posts` as `t2` ON `t1`.`post_id` = `t2`.`post_id` WHERE `topic_id` = ? GROUP BY `t1`.`post_id` ORDER by `$sort` $dir LIMIT ".page_count." OFFSET $offset"; 
    $sth = $pdo->prepare($sql);
    $sth->execute([$topic]);
}
else 
{
    $sql = "SELECT `t1`.*, `t2`.* FROM `home_gpress_topic_links` as `t1` LEFT JOIN `home_gpress_posts` as `t2` ON `t1`.`post_id` = `t2`.`post_id` GROUP BY `t1`.`post_id` ORDER by `$sort` $dir LIMIT ".page_count." OFFSET $offset"; 
    $sth = $pdo->prepare($sql);
    $sth->execute();
}
// Topic posts - contains all the posts for the topic, up to the predefined limit
$topic_posts = $sth->fetchAll(PDO::FETCH_ASSOC);
// because the IDs are all wrong
foreach($topic_posts as $topic_post)
{
    $topic_posts_fixed[$topic_post['post_id']] = $topic_post;
}
$topic_posts = $topic_posts_fixed; unset($topic_posts_fixed);
foreach($topic_posts as $topic_post)
{
    // get topics for each post... probably there has to be a better way to do this?
    $sql = "SELECT * FROM `home_gpress_topic_links` WHERE `post_id` = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$topic_post['post_id']]);
    $post_topics = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($post_topics as $post_topic)
    {
        $topic_posts[$post_topic['post_id']]['topics'][$post_topic['topic_id']] = $first_level_array[$post_topic['topic_id']]['name'];
    }
}
foreach($topic_posts as &$topic_post)
{
    asort($topic_post['topics'], SORT_NATURAL | SORT_FLAG_CASE);
}

// third level
if(!empty($_GET['d']))
{
    $sql = "SELECT * FROM `home_gpress_posts` WHERE `post_id` = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$_GET['d']]);
    $specific_post = $sth->fetch();
    if(!empty($specific_post))
    {
        $local_site_name = "gPress: " . say($specific_post['post_name']);
        $sql = "SELECT * FROM `home_gpress_topic_links` WHERE `post_id` = ?";
        $sth = $pdo->prepare($sql);
        $sth->execute([$_GET['d']]);
        $post_topics = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($post_topics))
        {
            foreach($post_topics as $post_topic)
            {
                $specific_post['topics'][$post_topic['topic_id']] = $first_level_array[$post_topic['topic_id']]['name'];    
            }
            asort($specific_post['topics'], SORT_NATURAL | SORT_FLAG_CASE);
        }
    }
}

$local_site_meta = "The blog of the Middling Works webmaster, containing both news about website features, and his personal opinions.";

?>