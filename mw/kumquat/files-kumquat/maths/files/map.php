<?php
// The map file, used for showing skills and their relationships
$query = $pdo->prepare("SELECT * from `maths_cats`");
$query->execute(
    [
    ]
);
$cats = $query->fetchAll();
$math_max_cats = count($cats);
$_SESSION['maths_max_cats'] = $math_max_cats;

// Also get needful user data, if available
if(isset($_SESSION['id']))
{
    if(!isset($_SESSION['last_q']))
    { 
        $query = $pdo->prepare("SELECT `cat_id` FROM `maths_problems` WHERE `user_id` = ? ORDER BY `created_time` desc LIMIT 1");
        $query->execute(
            [
                $_SESSION['id']
            ]
        );
        $last_time_q = $query->fetch();
        if(empty($last_time_q)){$_SESSION['last_q'] = 0;} else
        {$_SESSION['last_q'] = $last_time_q['cat_id'];}    
    }
    $query = $pdo->prepare("SELECT COUNT(*) as count, `cat_id`, SUM(`correct`) as `correct_total`, SUM(`tooktime`) as `total_time` FROM `maths_problems` WHERE `user_id` = ? GROUP BY `cat_id` ORDER BY `cat_id` asc, `inc_id` desc;");
    $query->execute(
        [
            $_SESSION['id']
        ]
    );
    $user_cats_sql = $query->fetchAll();
    $user_cats = [];
}
if(isset($user_cats_sql))
{
    foreach($user_cats_sql as $key => $value)
    {
        $user_cats[$value['cat_id']] = $value;
        $user_cats[$value['cat_id']]['avg_time'] = round($value['total_time'] / $value['count'],0);
    }
}

$local_site_meta = "The actual Maths Map map page."

?>