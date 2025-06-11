<div class="title_header">
<?php
echo "{$cat['cat_name']}: {$cat['cat_subtitle']}</div><br><div class='main_box_content single half_width stats'>
<div class='con_box left'>
";
if(isset($_SESSION['id'])):
if(!empty($total_stats_time['total_time'])): ?>
    <h4>Statistics</h4><br>
    Total Time:
    <?php
        echo "<b>{$total_stats_time['total_time']}s</b><br>";
        echo "Average Time: <b>{$final_stats_time}s</b><br><br>";
    $i = 1;
    foreach($results as $key => $value)
    {
        echo "<b><u>{$i}</u></b>. <br>{$value['prob_string']}<br>Your answer: {$value['user_answer']}<br>";
        if($value['correct'] === 0){echo "Correct answer: {$value['real_answer']}<br>";}
        echo "Answered in {$value['tooktime']}s.<br><br>";
        ++$i;
    }
else: ?>
<i>No problems have been solved in this category yet...</i><br>
<a href="index.php?s=maths&a=game&q=<?=$q?>">Solve some problems.</a><br>
<?php
endif;
?>
<a href="index.php?s=maths&a=map">Back to the map</a>
</div>
</div>
</div>
<br>
<br>
<div class="main_box_content single half_width minor <?=$score_class?>">
<div class="box"><?php echo $percentage; if(isset($_SESSION['id'])){ echo "({$results_correct_total} out of {$results_count})";}?></div>
<span class="perf_eval <?=$score_class?>">Your performance is <em class="perf_eval <?=$score_class?>"><?=$score_class?></em>.<br><?php
if($score_class === "sound"){if($results_correct_total >= 30){echo "You've aced this drill! ";} echo "<a href='index.php?s=maths&a=map'>You're ready to move on any time you like.</a>";}
if($score_class === "good"){echo "Keep practicing to improve your score, <a href='index.php?s=maths&a=map'>or change categories if you want.</a>";}
if($score_class === "middling"){echo "You're getting the hang of it! Practice makes perfect.";}
elseif($score_class === "weak" || $score_class === "untested"){echo "You have some work to do to conquer this category.";}
if($results_count === 30 && $results_correct_total !== $results_count){ echo "<br><span class='z3'>You need to solve {$missed_array[0]} more before your score will improve.</span>";}
?>
<hr><a href="index.php?s=maths&a=game&q=<?=$q?>">Solve more problems.</a>
</span>
<?php
else: ?>
<span class="guest_mode">If you were <a href="index.php?a=register&z">registered</a> and <a href="index.php?a=login&z">logged in</a>, this box would show your score for this category. No guest statistics are saved.</span>
<?php endif; ?>
</div>
</div>
<?php if(!empty($cat['guide_text'])): ?>
<br><br><div class="main_box_content single half_width tips left"><div class="con_box left"><details><summary>Guide for this category...</summary><?=$cat['guide_text']?></details></div></div><?php endif;?>
<?php $res = "maths/play_support.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>