<?php
// check index
reject_guest();
forgive_guest();
if(isset($_SESSION['id'])){$user_id = intval($_SESSION['id']);} else {$user_id = 0;}
if(!isset($_POST['door'])){ $_POST = [];}
$post_array = $_POST;
$errors = [];
/*
Tables:
chapters: thread_id, chapter_num, first_post, last_post, chapter_name
msgs: msg_id, sender_id, thread_id, msg_body, created, updated
threads: thread_id, thread_starter, thread_name, created, updated
thread_auths: thread_id, member_id, auth_level

Auth levels:

-2: banned from group
viewer -1: can read but not post
member - 0: can read and post
curator - 1: can add members to thread, can edit chapters
moderator: 2: can promote or remove curators, can remove others' messages
3: all powers of 2. can appoint or remove moderators. can only be 1 at a time. if leaves without appointment, 
the highest-ranking senior authed member (by appointment date) becomes the new 3.

The following is largely based on gPress, except of course for the new thread/message sections
*/

/*
    Posts to look out for:
        1. New thread - this needs a name, at least one person to add to the group, and an initial chapter name
        2. New messages - this needs a thread (possibly the same thread that just got added?), and authorization
        After this is done, the thread/etc. still need to be checked, unless we just do a header
        3. Add member
        4. Remove member
        5. Edit member - change their auth levels, etc.
        6. Edit post
        7. Edit chapters
        8. 
*/

// 1. New Thread, and all its attachments
if(isset($post_array['new_thread']))
{
    $thread_name_length = strlen($post_array['new_thread']);
    if($thread_name_length < 1)
    {
        $errors['new_thread']['nothing_happens'] = true;
    }
    if($thread_name_length > 40)
    {
        $errors['new_thread']['title_length'] = true;
    }
    if(empty($errors))
    { // The first milestone; the thread is valid, now let's see if the other new things are
        if(isset($post_array['nt_message']) && strlen($post_array['nt_message'] > 1))
        {
        }
        else 
        {
            $errors['new_thread']['no_message'] = true;
        }
        if(isset($post_array['new_chptr']) && strlen($post_array['new_chptr'] > 1))
        {
            $new_chapter = $post_array['new_chptr'];
        }
        else { $new_chapter = "New Chapter";}

        if(isset($post_array['nt_invitee']))
        {
            // Check if username is valid, same as with ULT invite
            $invite_id = get_userid($post_array['nt_invitee']);
            if(empty($invite_id))
            {
                $errors['new_thread']['invite_fail'] = true;
            }
            else
            {
                $checksCount = 2;
                if(isset($post_array['nt_invite_rank']) && is_numeric($post_array['nt_invite_rank']) && $post_array['nt_invite_rank'] > -2) 
                {
                    if($post_array['nt_invite_rank'] > 2)
                    {
                        $invitee_auth = 3;
                        $your_auth = 2;
                    }
                    else 
                    { 
                        $your_auth = 3;
                        $invitee_auth = $post_array['nt_invite_rank'];
                    }
                }
                else {$invitee_auth = 0;}
            }
        }

        if(empty($errors))
        { 
            $thread_id = newMailThread($user_id, $post_array['new_thread']);
            newMailMessage($user_id, $thread_id, $post_array['nt_message'], 1);
            newChapter($thread_id, 1, 1, 1, $new_chapter);
            newThreadAuths($thread_id, $user_id, $your_auth);
            newThreadAuths($thread_id, $invite_id, $invitee_auth);
            // This could be more complicated - the link might include the current sort and all that
            exit(header("Location: index.php?a=mail&t={$thread_id}"));
        }
    }
}

// We want this stuff for building links; it's lifted from gPress
$sort_table = array("thread_name", "created", "updated");
if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_table)){ $sort = $_GET['sort'];} else {$sort = "last_post_updated";}
if($sort === "updated"){$sort = "last_post_updated";}
if(isset($_GET['dir']) && $_GET['dir'] === "on")
{
    if($sort == "thread_name"){ $dir = "DESC";}
    else {$dir = "ASC";}
}
else 
{
    if($sort == "thread_name"){ $dir = "ASC";} 
    else $dir = "DESC";
}
if(isset($_GET['c']) && is_numeric($_GET['c']))
{
    $chapter = $_GET['c'];
} else {$chapter = 1;}

// All the remaining post actions require an auth check, so:

if(isset($post_array['door']) && isset($_GET['t']) && is_numeric($_GET['t']))
{
    // I could do some kind of super-switch for every single post action, but this is easier: if it takes a post, it takes
    // an auth check
    $sql = "SELECT `t1`.*, `t2`.`auth_level`, `t3`.`chapter_num`, (SELECT count(`local_msg_id`) from `msgr_msgs` where `thread_id` = `t1`.`thread_id`) as last_msg_id, (SELECT created from `msgr_msgs` where `thread_id` = `t1`.`thread_id` and `local_msg_id` = `last_msg_id`) as `last_post_updated` FROM `msgr_threads` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` LEFT JOIN `msgr_chapters` as `t3` ON `t1`.`thread_id` = `t3`.`thread_id` WHERE `t1`.`thread_id` = :thread_id AND `t2`.`auth_level` > -2 AND `t2`.`member_id` = :member_id ORDER BY `t3`.`chapter_num` DESC LIMIT 1;";
    $sth = $pdo->prepare($sql);
    $sth->execute([
        ':thread_id' => $_GET['t'],
        ':member_id' => $user_id
    ]);
    $thread = $sth->fetch();
}
if(!empty($thread))
{
// 2. New Message
// In this section, we're trying to post a message to an *existing* thread, which requires authorization, and for that
// thread to actually exist.
if(isset($post_array['message']) && strlen($post_array['message']) > 1) 
{
    if(!empty($thread) && $thread['auth_level'] > -1)
    {
        $new_msg_id = $thread['last_msg_id'] + 1;
        error_log("auth level: {$thread['auth_level']}, {$thread['thread_id']}");
        newMailMessage($user_id, $thread['thread_id'], $post_array['message'], $new_msg_id);
        // This _needs_ to be more complicated - the link needs to include the most recent chapter
        exit(header("Location: index.php?a=mail&t={$thread['thread_id']}&c={$thread['chapter_num']}&sort=$sort&dir=$dir"));
    }
    else 
    {
        $errors['new_message']['invalid_thread_choice'] = true;
    }
}
// 3. Far advanced past its original vision, this handles everything for the member list.
// This is complicated and hard to sort out, because of all the cases.
if(isset($post_array['target_member']))
{
    if($thread['auth_level'] > 1)
    {
        // Check if username is valid, same as with ULT invite
        $target_id = get_userid($post_array['target_member']);
        error_log("target id = $target_id");
        if(empty($target_id))
        {
            $errors['edit_member']['bad_invite'] = true;
        }
        else 
        { // The user exists, so now see if they are already in this thread
            $sql = "SELECT * from `msgr_thread_auths` WHERE `thread_id` = :thread_id AND `member_id` = :member_id";
            $sth = $pdo->prepare($sql);
            $sth->execute([
                ':thread_id' => $thread['thread_id'],
                ':member_id' => $target_id
            ]);
            $target = $sth->fetch();
            error_log("target = $target");
            if(!empty($post_array['target_position']) 
            && is_numeric($post_array['target_position'])
            && $post_array['target_position'] > -4
            && $post_array['target_position'] < 3)
            {
                $target_rank = $post_array['target_position'];
                error_log("target rank = $target_rank");
                if($target_rank >= $thread['auth_level'])
                {
                    $errors['edit_member']['weak_access'] = true;
                    error_log("target rank higher than auth 1");
                }
            }
            else{$target_rank = 0;
                error_log("target rank made 0");
            }
            if(empty($target) && empty($errors) && $target_rank > -3)
            { // The only thing to be done now is to add the user
                newThreadAuths($thread['thread_id'], $target_id, $target_rank);
                error_log("new user added");
            }
            elseif($thread['auth_level'] < $target['auth_level'])
            {
                $errors['edit_member']['weak_access'] = true;
            }
            else
            {
                /*
                Several possibilities here:
                You are a mod and want to delete
                You aren't a mod and want to delete (x)
                You are a mod and want to promote someone else to a mod
                You are a op+ and want to promote or demote someone else below you
                */
                // If you're a mod and you intend to delete something, go ahead -
                // we know he doesn't outrank you because it would have just failed above
                if($target_rank == -3)
                {
                    if($thread['auth_level'] > 2)
                    {
                        error_log("attempting to del");

                    // Attempt to delete; it should work
                    $query = "DELETE from `msgr_thread_auths` WHERE `thread_id` = :thread_id AND `member_id` = :member_id";
                    $query = $pdo->prepare($query);
                    $query->execute([":thread_id" => $thread['thread_id'], ":member_id" => $target_id]);
                    }
                    else 
                    {
                        $errors['edit_member']['weak_access'] = true;
                    } // this takes care of the deleting
                }
                elseif($target_rank === 3 && $thread['auth_level'] === 3)
                { // since you are really sure you want to do this mod appointment
                    error_log("attempting to replace self as mod");

                    newThreadAuths($thread['thread_id'], $target_id, $target_rank);
                    newThreadAuths($thread['thread_id'], $user_id, $thread['auth_level']);
                }
                elseif($target_rank > -3 && $target_rank < 3 && $target_rank < $thread['auth_level'])
                { // you outrank the target and it is not 3
                    error_log("general auth level edit");
                    newThreadAuths($thread['thread_id'], $target_id, $target_rank);
                }
                else {$errors['edit_member']['weak_access'] = true;}
            }
        }
    }
    else
    {
        $errors['edit_member']['insufficient_access'] = true;
    }
}
// 4. Edit Post
if(isset($_GET['e_id']) && $thread['auth_level'] > 0 && isset($post_array['e_msg']) && strlen($post_array['e_msg']) > 1)
{
    // Figure out two things - where the post is and who made it
    $sql = "SELECT * from `msgr_msgs` WHERE `thread_id` = :thread_id AND `local_msg_id` = :e_id";
    $sql = $pdo->prepare($sql);
    $sql->execute([
        ":thread_id" => $thread['thread_id'],
        ":e_id" => $_GET['e_id']
    ]);
    $result = $sql->fetch();
    if($result['thread_id'] === $thread['thread_id'] && ($result['sender_id'] === $user_id || $thread['auth_level'] > 1))
    {
        editMailMessage($result['local_msg_id'], $post_array['e_msg']);
    }
    else
    {
        $errors['e_msg']['weak_access'] = true;
    }
}
// 5. Add chapter
if(isset($post_array['add_chapter']) && $thread['auth_level'] > 0 && isset($post_array['first_post']) && isset($post_array['last_post']))
{
    // Scrub the $post_arrays
    if(!is_numeric($post_array['add_chapter']) || $post_array['add_chapter'] <= 0 || $post_array['add_chapter'] > 255 || ($post_array['add_chapter'] > $thread['chapter_num'] + 1))
    {
        $add_chapter_id = $thread['chapter_num'] + 1;
    }
    else
    {
        $add_chapter_id = $post_array['add_chapter'];
    }
    // Now to scrub the first/last posts - these should be numbers
    if(!is_numeric($post_array['first_post']) || $post_array['first_post'] <= 0)
    {
        $errors['new_chapter']['bad_first_post'] = true;
    }
    if(!is_numeric($post_array['last_post']) || $post_array['last_post'] <= 0 || $post_array['last_post'] < $post_array['first_post'])
    {
        $errors['new_chapter']['bad_last_post'] = true;
    }
    if(isset($post_array['chapter_name']))
    {
        $chapter_name_length = strlen($post_array['chapter_name']);
        if($chapter_name_length < 1 || $chapter_name_length > 40)
        { $chapter_name = "New Chapter";}
        else 
        { 
            $chapter_name = $post_array['chapter_name'];
        }
    }
    else {$chapter_name = "New Chapter";}
    if(empty($errors['new_chapter']))
    {
        // What happens with the numbers for all the other chapters?
        // I have a solution for that.
        $query = "UPDATE `msgr_chapters` SET `chapter_num` = `chapter_num` + 1 WHERE `msgr_chapters`.`chapter_num` >= :chapter_num AND `msgr_chapters`.`thread_id` = :thread_id";
        $query = $pdo->prepare($query);
        $query->execute(
            [
            ":thread_id" => $thread['thread_id'], 
            ":chapter_num" => $add_chapter_id
            ]
        );
        $query = $pdo->prepare("INSERT INTO `msgr_chapters` 
        (`thread_id`, `chapter_num`, `first_post`, `last_post`, `chapter_name`) 
        VALUES (:thread, :chapter_num, :first_post, :last_post, :chapter_name  )");
        $query->execute(
        [
        ':thread' => $thread['thread_id'],
        ':chapter_num' => $add_chapter_id,
        ':first_post' => $post_array['first_post'],
        ':last_post' => $post_array['last_post'],
        ':chapter_name' => $chapter_name
        ]
        );
    }
}
else
{
    $errors['new_thread']['weak_access'] = true;
}

// 5. Edit chapter
if(isset($post_array['edit_chapters']) && $thread['auth_level'] > 0)
{
    // This is all taken more or less verbatim from the month filter
    $checks_array = array();
    // An alternate technique was described by JvdBerg - "php - finding keys in an array that match a pattern"
    $types_array = ["nb" => true, "fp" => true, "lp" => true, "nm" => true, "dl" => true];
    foreach($post_array as $key => $value)
    {
        $del_count = 0;
        if(str_starts_with($key, "c_edit_"))
        {
            $check = substr($key, 7);
            // nb, fp, lp, nm
            $type = substr($check, 0, 2);
            if(!isset($types_array[$type]))
            {
                unset($post_array[$key]); continue;
            }

            $id = substr($check, 3);

            if(!is_numeric($id) || $id > 255 || $id < 0)
            {
                unset($post_array[$key]); continue;
            }
            if($type === 'dl')
            {
                ++$del_count;
            }
            $checks_array[$id][$type] = $value;
            unset($post_array[$key]);
        }
    }
    $count_checks = count($checks_array);
    $debug['countchecks'] = $count_checks;
    $debug['del_count'] = $del_count;
    if($del_count === $count_checks){$errors['edit_chapters']['cantdelall'] = true;}
    if(empty($errors['edit_chapters']))
    {
    foreach($checks_array as $key => $value)
    {
        $num = $key + 1;
        if(isset($value['dl']))
        { // this section needs to be made more efficient, so we don't change chap nums for things more than once,
          // or for things that are about to be deleted anyway
          // We also need something to keep the user from deleting all the chapters!
            $query = "DELETE from `msgr_chapters` WHERE `thread_id` = :thread_id AND `chapter_num` = :chapter_num";
            $query = $pdo->prepare($query);
            $query->execute([":thread_id" => $thread['thread_id'], ":chapter_num" => $num]);
            // Here's a problem - what happens with the numbers for all the other chapters?
            // I have a solution for that.
            $query = "UPDATE `msgr_chapters` SET `chapter_num` = `chapter_num` - 1 WHERE `msgr_chapters`.`chapter_num` > :deleted_chapter AND `msgr_chapters`.`thread_id` = :thread_id";
            $query = $pdo->prepare($query);
            $query->execute(
                [
                ":thread_id" => $thread['thread_id'], 
                ":chapter_num" => $num
                ]
            );
        }
        else
        {
        // Now to scrub the first/last posts - these should be numbers
        if(!is_numeric($value['fp']) || $value['fp'] <= 0)
        {
            $errors['new_chapter']['bad_first_post'] = true;
        }
        if(!is_numeric($value['lp']) || $value['lp'] <= 0 || $value['lp'] < $value['fp'])
        {
            $errors['new_chapter']['bad_last_post'] = true;
        }
        if(isset($value['nm']))
        {
            $chapter_name_length = strlen($value['nm']);
            if($chapter_name_length < 1 || $chapter_name_length > 40)
            { $chapter_name = "New Chapter";}
            else 
            { 
                $chapter_name = $value['nm'];
            }
        }
        if(empty($errors['new_chapter']))
        {
            $query = "UPDATE `msgr_chapters` SET `first_post` = :first_post, `last_post` = :last_post, `chapter_name` = :chapter_name WHERE `msgr_chapters`.`chapter_num` = :edited_chapter AND `msgr_chapters`.`thread_id` = :thread_id";
            $query = $pdo->prepare($query);
            $query->execute(
                [
                    ':thread_id' => $thread['thread_id'],
                    ':edited_chapter' => $num,
                    ':first_post' => $value['fp'],
                    ':last_post' => $value['lp'],
                    ':chapter_name' => $value['nm']
                ]
            );
        }
    }
    }
}
}
else
{
    $errors['new_thread']['weak_access'] = true;
}
} // end thread

// First level
/* 
    This is more like the second level of gPress, because we can sort threads, etc., 
    while there's not so many options for actually viewing threads. We get the page
    count first, work out different params for pages, and then, in one great query,
    get all the threads
*/
$working_date = DateTimeImmutable::createFromFormat("U", $_SERVER['REQUEST_TIME']); 
$max_year = date_format($working_date, "Y");
if(isset($_GET['yrt']) && is_numeric($_GET['yrt']))
{
    if($_GET['yrt'] > $max_year || $_GET['yrt'] < 2025)
    {
    }
    else
    {
        $working_date = DateTimeImmutable::createFromFormat("Y", $_GET['yrt']);
    }
}

if(isset($_GET['mnt']))
{
    if($_GET['mnt'] > 12 || $_GET['mnt'] < 1)
    {
        $begin_time = $working_date->modify("midnight, first day of this month");
        $final_time = $working_date->modify("midnight, first day of next month");
    }
    else
    {
        $begin_time = $working_date->modify("{$_GET['mnt']}/1, midnight, first day of this month");
        $final_time = $working_date->modify("{$_GET['mnt']}/1, midnight, first day of next month");
    }
}
else 
{
    $begin_time = $working_date->modify("midnight, first day of this month");
    $final_time = $working_date->modify("midnight, first day of next month");
}
// Count

// $sql = "SELECT count(`t1`.`thread_id`) as count FROM `msgr_threads` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t2`.`member_id` = :member_id";
/*  This version gets a list of months, with the idea of giving you a select menu you can use
$sql = 'SELECT (SELECT date_format(`updated`, "%Y-%m") from `msgr_msgs` where `thread_id` = `t1`.`thread_id` ORDER BY `updated` DESC LIMIT 1) as `last_post_updated` FROM `msgr_threads` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t2`.`member_id` = :member_id GROUP BY `last_post_updated` ORDER BY `last_post_updated` DESC';
$sth = $pdo->prepare($sql);
$sth->execute(
    [
        ':member_id' => $user_id,
    ]);
$valid_months = $sth-fetch();
*/
/* page count version
$total_threads = $sth->fetch();
$total_threads = $total_threads['count'];
$max_pages = ceil($total_threads / page_count);

if(isset($_GET['p']))
{
    $intval = intval($_GET['p']);
    if($intval <= 1){ $page = 1;}
    elseif($intval > $max_pages){ $page = $max_pages;}
    else {$page = $intval;}
} else {$page = 1;}
$offset = ($page * page_count) - page_count;

$sql = "SELECT `t1`.*, `t2`.`auth_level`, 
(SELECT `chapter_num` FROM `msgr_chapters` WHERE `thread_id` = `t1`.`thread_id` ORDER BY chapter_num DESC LIMIT 1) 
as last_chapter, 
(SELECT `sender_id` FROM `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id` ORDER BY `created` DESC LIMIT 1) 
as last_sender, 
(SELECT count(`local_msg_id`) as `message_count` from `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id`) 
as message_count, 
(SELECT created from `msgr_msgs` where `thread_id` = `t1`.`thread_id` ORDER BY `updated` DESC LIMIT 1) 
as `last_post_updated` FROM `msgr_threads` as `t1` 
LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` 
WHERE `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 
ORDER by `$sort` $dir LIMIT $offset,".page_count."";
*/
// the date version, hopefully this will be good for release, although I think it has got to be improvable
$up_date = date_format($final_time, "Y-m-d H:i:s");
$down_date = date_format($begin_time, "Y-m-d H:i:s");
$yrt = date_format($begin_time, "Y");
$mnt = date_format($begin_time, "m");
$sql = "SELECT `t1`.*, `t2`.`auth_level`, 
(SELECT `chapter_num` FROM `msgr_chapters` WHERE `thread_id` = `t1`.`thread_id` ORDER BY chapter_num DESC LIMIT 1)
as last_chapter, 
(SELECT `sender_id` FROM `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id` ORDER BY `created` DESC LIMIT 1) 
as last_sender, 
(SELECT count(`local_msg_id`) as `message_count` from `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id`) 
as message_count, 
(SELECT created from `msgr_msgs` where `thread_id` = `t1`.`thread_id` ORDER BY `created` DESC LIMIT 1) 
as `last_post_updated` FROM `msgr_threads` as `t1` 
LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` 
WHERE `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 
HAVING `last_post_updated` < :up_date AND `last_post_updated` >= :down_date
ORDER BY `$sort` $dir";
// page count version
//$sql = "SELECT `t1`.*, `t2`.`auth_level`, (SELECT `chapter_num` FROM `msgr_chapters` WHERE `thread_id` = `t1`.`thread_id` ORDER BY chapter_num DESC LIMIT 1) as last_chapter, (SELECT `sender_id` FROM `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id` ORDER BY `created` DESC LIMIT 1) as last_sender, (SELECT count(`local_msg_id`) as `message_count` from `msgr_msgs` WHERE `thread_id` = `t1`.`thread_id`) as message_count, (SELECT created from `msgr_msgs` where `thread_id` = `t1`.`thread_id` ORDER BY `updated` DESC LIMIT 1) as `last_post_updated` FROM `msgr_threads` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 ORDER by `$sort` $dir LIMIT $offset,".page_count."";
$sth = $pdo->prepare($sql);
$sth->execute([
    ":member_id" => $user_id,
    ":up_date" => $up_date,
    ":down_date" => $down_date
]);
$eligible_threads = $sth->fetchAll(PDO::FETCH_ASSOC);
$first_level_array = [];
if(empty($eligible_threads))
{
    /* 
    We don't want finding information to turn into 'guess the last eligible month', so we help the user
    by finding the next and last months where things happened
    Thanks to Pius Aboyi at InfluxData for reminding me date_format is a thing
    */
    $sql = "SELECT
(SELECT
    (SELECT date_format(updated, '%Y-%m') from `msgr_msgs` where `thread_id` = `t1`.`thread_id` AND `updated` < :down_date ORDER BY `updated` DESC LIMIT 1) 
as `last_post_updated` FROM `msgr_threads` as `t1` 
LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` 
WHERE `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 
ORDER BY `last_post_updated` LIMIT 1) as last_post_month,
(SELECT
    (SELECT date_format(updated, '%Y-%m') from `msgr_msgs` where `thread_id` = `t1`.`thread_id` and `updated` > :up_date ORDER BY `updated` DESC LIMIT 1) 
as `last_post_updated` FROM `msgr_threads` as `t1` 
LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` 
WHERE `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 
ORDER BY `last_post_updated` LIMIT 1) as next_post_month";
$sth = $pdo->prepare($sql);
$sth->execute([
    ":member_id" => $user_id,
    ":up_date" => $up_date,
    ":down_date" => $down_date
]);
$debug['up_date'] = $up_date;
$debug['down_date'] = $down_date;
$near_months = $sth->fetch();
$debug['near_months'] = $near_months;
if(!empty($near_months['last_post_month']))
{
    $last_post_month = explode("-", $near_months['last_post_month']);
}
if(!empty($near_months['next_post_month']))
{
    $next_post_month = explode("-", $near_months['next_post_month']);
}
}
else {
$count = count($eligible_threads);
for($i = 0; $i < $count; $i++)
{
    $eligible_threads[$i]['last_sender_name'] = get_username($eligible_threads[$i]['last_sender']);
    $first_level_array[$eligible_threads[$i]['thread_id']] = $eligible_threads[$i];
}
}
// end first level

// second level
if(isset($_GET['t']) && !isset($thread))
{
    if(!array_key_exists($_GET['t'], $first_level_array))
    {
        $sql = "SELECT `t1`.* from `msgr_threads` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t1`.`thread_id` = :thread_id AND `t2`.`auth_level` > -2";
        $sth = $pdo->prepare($sql);
        $sth->execute([
            ':thread_id' => $_GET['t']
        ]);
        $thread = $sth->fetch();
    }
    else {$thread = $first_level_array[$_GET['t']];}
}
if(isset($thread))
{
/* Now to get the posts, but because we're using the chapter system, things are a little more complicated than otherwise.
*/

$sql = "SELECT * from msgr_chapters WHERE `thread_id` = :thread_id";
$sth = $pdo->prepare($sql);
$sth->execute([
    ':thread_id' => $thread['thread_id']
]);
$chapters = $sth->fetchAll(PDO::FETCH_ASSOC);
$last_chapter = $chapters[array_key_last($chapters)];
if(isset($_GET['c']) && is_numeric($_GET['c']))
{
    $chapter = $_GET['c'];
    if($chapter > $last_chapter['chapter_num'])
    {
        $chapter = $last_chapter['chapter_num'];
    }
    elseif($chapter < 1)
    {
        $chapter = 1;
    }
} else {$chapter = 1;}
$main_chapter = $chapters[$chapter-1];

if($main_chapter['chapter_num'] === $last_chapter['chapter_num'])
{ // The last chapter doesn't need a last post; we want to show all the last posts remaining
    $sql = "SELECT `t1`.* from `msgr_msgs` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t1`.`thread_id` = :thread_id AND `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 AND `local_msg_id` >= :first_post";
    $sth = $pdo->prepare($sql);
    $sth->execute([
        ':thread_id' => $thread['thread_id'],
        ':member_id' => $user_id,
        ':first_post' => $main_chapter['first_post']
    ]);
}
else 
{
    $sql = "SELECT `t1`.* from `msgr_msgs` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t1`.`thread_id` = :thread_id AND `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 AND `local_msg_id` >= :first_post AND `local_msg_id` <= :last_post";
    $sth = $pdo->prepare($sql);
$sth->execute([
    ':thread_id' => $thread['thread_id'],
    ':member_id' => $user_id,
    ':first_post' => $main_chapter['first_post'],
    ':last_post' => $main_chapter['last_post']
]);
}

$messages = $sth->fetchAll(PDO::FETCH_ASSOC);
$local_site_name = "gPress: {$first_level_array[$thread['thread_id']]['thread_name']}";

}
/* third level: used for three different sections
1. The chapter editor - a form, similar to the FitB question editor
2. The member list - contains a list of active members, their join dates and their ranks
3. The post editor
*/
if(isset($_GET['e_id']))
{ // this needs: a message to edit, confirmation the msg belongs to this thread, and that you can edit it -
    $sql = "SELECT `t1`.* from `msgr_msgs` as `t1` LEFT JOIN `msgr_thread_auths` as `t2` ON `t1`.`thread_id` = `t2`.`thread_id` WHERE `t1`.`thread_id` = :thread_id AND `t2`.`member_id` = :member_id AND `t2`.`auth_level` > -2 AND `local_msg_id` >= :e_id";
    $sth = $pdo->prepare($sql);
$sth->execute([
    ':thread_id' => $thread['thread_id'],
    ':member_id' => $user_id,
    ':e_id' => $_GET['e_id'],
]);
    $target_msg = $sth->fetch();
    if(!empty($target_msg))
    {
        $third_level = true;
        $third_level_txt = "Edit Message";
    }
}

elseif(isset($_GET['m_list']))
{ // this alone needs nothing but the thread id, but editing member access levels needs a rank
    $third_level = true;
    $third_level_txt = "Memberlist";
    $sql = "SELECT `t1`.*, `t2`.`username` from `msgr_thread_auths` as `t1` LEFT JOIN `home_accounts` as `t2` ON `t1`.`member_id` = `t2`.`id` WHERE `t1`.`thread_id` = :thread_id ORDER BY auth_level DESC, updated ASC, username ASC";
    $sth = $pdo->prepare($sql);
    $sth->execute([
        ':thread_id' => $thread['thread_id'],
    ]);
    $members = $sth->fetchAll(PDO::FETCH_ASSOC);
    
}

elseif(isset($_GET['c_edit']))
{
    $third_level = true;
    $third_level_txt = "Edit Chapters";
}

?>