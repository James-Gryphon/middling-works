<?php
$ip = $_SERVER['REMOTE_ADDR'];
if($ip == "192.168.1.1"):
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db.";charset=utf8mb4;", member, passcode);
include_once "files-kumquat/files/functions.php";
$query = $pdo->query("SELECT COUNT(`id`) as count FROM `middling_main`.`home_accounts`");
$accounts_count = $query->fetch();
$accounts_count = $accounts_count['count'];
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<link rel="stylesheet" href="public/files/base.css">
<link rel="stylesheet" href="public/files/beige.css">
</head>
<?php
/* Tasks for kumquat_ap:
1. Manage gPress posts - writing them, updating them, and pushing them from Kumquat to Main. Currently done by gpp.php and kumquat_cp.php
2. Manage Kumquat/Kiwi files and writing to Main. Currently done by kumquat_cp.php
Perhaps sometime this should be able to replace, or modify, the shell script so that directories can be added without manually modding the script.
3. Upload new FitB puzzles. In the future, this should also be able to manage them, the same as gPress. Currently done by new_puzzles.php
4. Read and destroy error logs. Currently done by error_reader.php
*/
?>
<header><a href='kumquat_ap.php'>Main Page</a>  |   <a href='kumquat_ap.php?file_sink'>Update Files</a>    |   <a href='kumquat_ap.php?gpress'>gPress</a>   |   <a href='kumquat_ap.php?error_reader'>Errors</a>    |   <a href='kumquat_ap.php?fitb'>FitB</a>  |   <a href='month_filter.php'>FitB Months</a>  |   <a href='kumquat_ap.php?info'>Info</a>  |   <a href='public/index.php?s=thehunt&a=boardbuilder_real'>TD Board Builder</a></header>
<?php
echo qdateBuilder(time()), "<br>";
echo time(), "<br>";
echo date("M d Y H:i:s"), "<br>";
echo gmdate("M d Y H:i:s"), "<br>";
if(isset($_GET['file_sink'])):
    if(isset($_POST['updatemain']) && isset($_POST['update_main_check']) && isset($_POST['update_main_check2']))
    { 
        echo "Updating main...<br>";
        require_once("sitemapbuilder.php");
        exec('~/upload_new.sh', $output, $retval);
    }
    ?>
    <form method="POST">
    <input type="submit" name="updatemain" id="updatemain" value="Update Main "></input><input type="checkbox" name="update_main_check" id="update_main_check"><input type="checkbox" name="update_main_check2" id="update_main_check2"><br>
    </form>
<?php
elseif(isset($_GET['gpress'])):
    if(!isset($_POST['post'])):
        ?>
        <form method="POST">
        <input type="submit" name="blogger" id="blogger" value="Update Blog Posts (FROM Kumquat/Kiwi to Main)"></input><input type="checkbox" name="update_blog_check" id="update_blog_check"><br><br>
        </form>
    <?php
        if(isset($_POST['blogger']))
        {
        $sql =
        "DROP TABLE IF EXISTS `middling_main`.`home_gpress_posts`;
        CREATE TABLE `middling_main`.`home_gpress_posts` LIKE `middling_kumquat`.`home_gpress_posts`;
        INSERT INTO `middling_main`.`home_gpress_posts` SELECT * FROM `middling_kumquat`.`home_gpress_posts`;
        DROP TABLE IF EXISTS `middling_main`.`home_gpress_topics`;
        CREATE TABLE `middling_main`.`home_gpress_topics` LIKE `middling_kumquat`.`home_gpress_topics`;
        INSERT INTO `middling_main`.`home_gpress_topics` SELECT * FROM `middling_kumquat`.`home_gpress_topics`;
        DROP TABLE IF EXISTS `middling_main`.`home_gpress_topic_links`;
        CREATE TABLE `middling_main`.`home_gpress_topic_links` LIKE `middling_kumquat`.`home_gpress_topic_links`;
        INSERT INTO `middling_main`.`home_gpress_topic_links` SELECT * FROM `middling_kumquat`.`home_gpress_topic_links`;
        ";
        $pdo->exec($sql);
        echo "Table updates attempted.";
        }
        ?>
        <form METHOD="POST" name="new_post">
        <input type="hidden" name="post" id="post">
        <input type="submit" value="New Post">
        </form>
        <form METHOD="POST" name="post">
        <select name="old_post">
        <?php
        $sql = "SELECT * FROM `home_gpress_posts`";
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $posts = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($posts as $post): ?>
            <option><?=$post['post_name']?></option>
        <?php endforeach; ?>
        </select>
        <input type="hidden" name="post" id="post">
        <input type="hidden" name="e" id="e">
        <input type="submit" value="Edit">
        <?php
        else:
        ?>
        <a href='kumquat_ap.php?gpress=1'>Main gPress Page</a>
        <?php 
        if(!empty($_POST['post_name']) && !empty($_POST['post_text']) || isset($_POST['post_id']))
        {
            echo "Attempting to update something...";
            echo "<br>";
            $updated = date("Y-m-d H:i:s");
            // The procedure for an existing post is different from a new post
            if(isset($_POST['post_id']))
            {
                echo "Updating post.<br>";
                $sql = "SELECT * FROM `home_gpress_posts` WHERE `post_id` = ?";
                $sth = $pdo->prepare($sql);
                $sth->execute([$_POST['post_id']]);
                $edit_post = $sth->fetch();
                if(empty($_POST['post_text'])){ $post_text = $edit_post['post_text']; $i += 1;} else {$post_text = $_POST['post_text'];}
                if(empty($_POST['post_name'])){ $post_name = $edit_post['post_name']; $i += 1;} else {$post_name = $_POST['post_name'];}
                if(!isset($i) || $i != 2)
                {
                $sql = "UPDATE `home_gpress_posts` SET post_name=:post_name, post_text=:post_text, updated=:updated WHERE post_id=:post_id";
                $sth = $pdo->prepare($sql);
                $sth->execute([
                ":post_name" => $post_name,
                ":post_text" => $post_text,
                ":updated" => $updated,
                ":post_id" => $_POST['post_id']
                ]);
                $c = 2;
                $post_id = $_POST['post_id'];
                $_POST['e'] = 1; // a hack
                $_POST['old_post'] = $post_name; // another hack
                }
                else {echo "No changes made.";}
            }
            else
            {
                echo "Creating new post.<br>";
                $post_name = $_POST['post_name'];
                $post_text = $_POST['post_text'];
                $sql = $pdo->prepare("INSERT INTO `home_gpress_posts` (`post_name`, `post_text`) VALUES (:post_name, :post_text)");
                $sql->execute([
                ":post_name" => $post_name,
                ":post_text" => $post_text,
                ]);
                $c = 1;
                $post_id = $pdo->lastInsertId();
                $_POST['e'] = 1; // a hack
                $_POST['old_post'] = $post_name; // another hack
            }
            if(isset($c))
            {
                $sql = $pdo->prepare("DELETE FROM `home_gpress_topic_links` WHERE `post_id` = :post_id");
                $sql->execute([
                    ':post_id' => $post_id
                    ]); 
            foreach($_POST['topics'] as $key => $topic)
                {
                $sql = $pdo->prepare("INSERT INTO `home_gpress_topic_links` (`post_id`, `topic_id`) VALUES (:post_id, :topic_id)");
                $sql->execute([
                    ":post_id" => $post_id,
                    ":topic_id" => $topic
                    ]);
                }
            }
        }
        
        // Editing box
        if(isset($_POST['e'])){
            $sql = "SELECT * FROM `home_gpress_posts` WHERE `post_name` = ?";
            $sth = $pdo->prepare($sql);
            $sth->execute([$_POST['old_post']]);
            $post = $sth->fetch();
        }
        ?>
        <form method="POST" name="gpress_post">
        <input type="hidden" name="post" id="post">
        <input type="hidden" name="e" value="1">
        <input type="text" name="post_name"
        <?php if(isset($post['post_name'])){
            echo "value='", say($post['post_name']), "'";
        } ?>
        ><br>
        <textarea name="post_text" cols=80 rows=30><?php if(isset($post['post_text'])){echo ($post['post_text']);} ?></textarea><br>
        <?php
            $sql = "SELECT `topic_id`, `name` FROM `home_gpress_topics`";
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $topics = $sth->fetchAll();
        
        if(isset($_POST['e'])){
            echo "<input type='hidden' name='post_id' value={$post['post_id']}>";
            $sql = "SELECT `topic_id` FROM `home_gpress_topic_links` WHERE `post_id` = ?";
            $sth = $pdo->prepare($sql);
            $sth->execute([$post['post_id']]);
            $topic_links = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        // thanks for the 'name' tip - https://stackoverflow.com/questions/11616659/post-values-from-a-multiple-select
        ?>
        <select name="topics[]" id="topics" multiple>
        <?php
        foreach($topics as $topic){
        echo "<option name='{$topic['topic_id']}' value='{$topic['topic_id']}'";
        if(!empty($topic_links) && in_array($topic['topic_id'], $topic_links)){ echo " selected";}
        echo ">{$topic['name']}";
        }
        ?>
        </select>
        <input type="submit">
        </form>
        <?php
        endif;
elseif(isset($_GET['error_reader'])):
    if(isset($_POST['dump'])){ fopen("public/error_log", "w"); echo "Error log cleared.";}
    echo "<pre>";
    require_once("public/error_log");
    echo "</pre>";
    ?>
    <br>
    <form method="POST" action=''>
    <input type="hidden" name="dump" value="1" id="dump">
    <input type="submit" value="Clear Log">
    </form>
    <a href="">Refresh Page</a>
    <?php
elseif(isset($_GET['fitb'])):
    
elseif(isset($_GET['fitb_old'])):
    exec('~/fitb_update.sh', $output, $retval);
    echo $retval; echo "<br>"; var_dump($output);
elseif(isset($_GET['info'])):
    phpinfo();
endif;

echo "<br><hr>Registered members: $accounts_count<br><br>Post: "; var_dump($_POST); echo "<br>"; 
else:
echo "Invalid credentials.";
endif;
?>
</html>
