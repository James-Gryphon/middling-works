<?php

// Make sure the category type is a valid category
if(isset($_GET['q']) && is_numeric($_GET['q']))
{
    $query = $pdo->prepare("SELECT * from `maths_cats` WHERE cat_id = ?");
    $query->execute(
        [
         $_GET['q']
        ]
        );
    $cat = $query->fetch();
    if(isset($cat['cat_id'])){$q = $_GET['q'];}
}
else {$q = 0;
    $query = $pdo->prepare("SELECT * from `maths_cats` WHERE cat_id = ?");
    $query->execute(
        [
         $_GET['q']
        ]
        );
    $cat = $query->fetch();
}

// Since this is your stats, we definitely need to check the database
if(isset($_SESSION['id']))
{
    $_SESSION['last_q'] = $q;
    $results = $query->fetch();
    $query = $pdo->prepare("SELECT * from `maths_problems` WHERE user_id = :user_id AND cat_id = :cat_id ORDER BY inc_id ASC");
    $query->execute(
        [
        ':user_id' => $_SESSION['id'],
        ':cat_id' => $q, // this is to prevent an exploit
        ]
        );
    $results = $query->fetchAll();
    $query = $pdo->prepare("SELECT sum(tooktime) as total_time from `maths_problems` WHERE user_id = :user_id AND cat_id = :cat_id");
    $query->execute(
        [
        ':user_id' => $_SESSION['id'],
        ':cat_id' => $q
        ]
        );
    $total_stats_time = $query->fetch();
    $results_count = count($results);
    if($results_count > 0){
    $final_stats_time = round($total_stats_time['total_time'] / $results_count,2); }
    $results_correct_total = 0;
    $missed_array = [];
    $i = 1;
    foreach($results as $key => $value)
    {
        if($value['correct'] == 1)
        {
            $results_correct_total += 1;
        } 
        else 
        {
            $missed_array[] = $i;
        } 
        $i += 1;
    }
    if(!empty($results)){
    $_SESSION['maths_cat_count'] = $results_count;
    } else {$_SESSION['maths_cat_count'] = 0;}
    $_SESSION['maths_cat_id'] = $q;}

// for minor box display
if(isset($_SESSION['id']) && !empty($results)){
$percent = 100*(round($results_correct_total / $results_count, 2));
$percentage = "{$percent}%";
if($results_count >= 30){ $adjust = 1;} else {
    $adjust = ($results_count - 1) / $results_count; }
$score = $percent * round($adjust,2,PHP_ROUND_HALF_DOWN);
if($score == 100){$percentage = "√";}
if($score >= 90){ $score_class = "sound";}
elseif($score >= 80){ $score_class = "good";}
elseif($score >= 70){ $score_class = "middling";}
else{ $score_class = "weak";}}
else{$percentage = ""; $score_class = "untested"; $results_correct_total = 0; $results_count = 0;}

?>