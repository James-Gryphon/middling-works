<?php
require_once("problems.php");

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

if(isset($_SESSION['maths_solution']) && isset($_POST['solution']))
{
    // seems inefficient because we're going to get the time again later; eh
    $new_time = microtime(true);
    $difference = round($new_time - $_SESSION['maths_time'],2);
    if($_POST['solution'] == $_SESSION['maths_solution']){$win = 1; $res_string = "<summary>Correct!</summary>The problem was: <br>{$_SESSION['maths_last_problem']}<br> Your answer was: <br><b class='z3'>{$_POST['solution']}</b>";}
    else {$win = 0; $old_solution = $_SESSION['maths_solution']; $res_string = "<summary>Sorry.</summary>The problem was: <br>{$_SESSION['maths_last_problem']}<br> Your answer was: <br><b class='z3'>{$_POST['solution']}</b><br> The right answer is: <br><b class='z3'><u>{$old_solution}</u></b><br>";}
    if(isset($_SESSION['id']))
    {
        /*
        Two things to do here.
        First, we need to add the problem itself to the problems table.
        Second, we need to get rid of the oldest problem
        */
        $query = $pdo->prepare("INSERT INTO `maths_problems` (`user_id`, `cat_id`, `prob_string`, `real_answer`, `user_answer`, `correct`, `tooktime`) VALUES (:user_id, :cat_id, :prob_string, :real_answer, :user_answer, :correct, :tooktime)");
        $query->execute(
        [
        ':user_id' => $_SESSION['id'],
        ':cat_id' => $_SESSION['maths_q'], // this is to prevent an exploit
        ':prob_string' => $_SESSION['maths_last_problem'],
        ':real_answer' => $_SESSION['maths_solution'],
        ':user_answer' => $_POST['solution'],
        ':correct' => $win,
        ':tooktime' => $difference
        ]
        );
        if($_SESSION['maths_cat_count'] >= 30)
        { // delete oldest problem
            $query = "DELETE from `maths_problems` WHERE `cat_id` = :cat_id AND `user_id` = :user_id ORDER BY `inc_id` ASC LIMIT 1";
            $query = $pdo->prepare($query);
            $query->execute([":cat_id" => $_SESSION['maths_q'], ":user_id" => $_SESSION['id']]);
        }
    }
}
// We now have to check the database every time, unfortunately.
if(isset($_SESSION['id']))
{
    $_SESSION['last_q'] = $q;
    /*
    $query = $pdo->prepare("SELECT COUNT(*) as count, `cat_id`, SUM(`correct`) as `correct_total` from `maths_problems` WHERE user_id = :user_id AND cat_id = :cat_id GROUP BY `cat_id` ORDER BY inc_id ASC");
    $query->execute(
        [
        ':user_id' => $_SESSION['id'],
        ':cat_id' => $q, // this is to prevent an exploit
        ]
        );
    */
    $results = $query->fetch();
    $query = $pdo->prepare("SELECT * from `maths_problems` WHERE user_id = :user_id AND cat_id = :cat_id ORDER BY inc_id ASC");
    $query->execute(
        [
        ':user_id' => $_SESSION['id'],
        ':cat_id' => $q, // this is to prevent an exploit
        ]
        );
    $results = $query->fetchAll();
    $results_count = count($results);
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

$prob = [];
if(isset($_SESSION['maths_last_problem']))
{
$prob['string'] = $_SESSION['maths_last_problem'];
while ($prob['string'] == $_SESSION['maths_last_problem']){ $prob = "prob_{$q}"(); }
}
else {$prob = "prob_{$q}"();}
$_SESSION['maths_last_problem'] = $prob['string'];
$_SESSION['maths_q'] = $q;
$_SESSION['maths_time'] = microtime(true);

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