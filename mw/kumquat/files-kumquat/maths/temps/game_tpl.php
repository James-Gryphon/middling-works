<div class="title_header">
<?php
echo "{$cat['cat_name']}: {$cat['cat_subtitle']}</div><br><div class='main_box_content single half_width minor'>
<div class='con_box left'>
";
if(isset($res_string))
{
    echo "<div class='outcome'><details>$res_string</details></div>";
}
echo "<span id='problem' class='z7'>{$prob['string']}</span><br>";
?>
<form method="POST" action="" id="solution_form" name="solution_form">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="text" id="solution" name="solution" autocomplete="off" inputmode="decimal">
<input type="submit" value="Post">
</form>
<?php if(isset($cat['format'])){echo "<i id='format_guide' class='z3'>{$cat['format']}</i>";}?><br>
<a href="index.php?s=maths&a=map">Back to the map</a>
</div>
</div><br><br>
<div class="main_box_content single half_width minor <?=$score_class?>">
<div class="box"><?php echo $percentage; if(isset($_SESSION['id'])){ echo "({$results_correct_total} out of {$results_count})";}?></div>
<?php
if(isset($_SESSION['id'])): ?>
<span class="perf_eval <?=$score_class?>">Your performance is <em class="perf_eval <?=$score_class?>"><?=$score_class?></em>.<br><?php
if($score_class === "sound"){if($results_correct_total >= 30){echo "You've aced this drill! ";} echo "<a href='index.php?s=maths&a=map'>You're ready to move on any time you like.</a>";}
if($score_class === "good"){echo "Keep practicing to improve your score, <a href='index.php?s=maths&a=map'>or change categories if you want.</a>";}
if($score_class === "middling"){echo "You're getting the hang of it! Practice makes perfect.";}
elseif($score_class === "weak" || $score_class === "untested"){echo "You have some work to do to conquer this category.";}
if($results_count === 30 && $results_correct_total !== $results_count){ echo "<br><span class='z3'>You need to solve {$missed_array[0]} more before your score will improve.</span>";}
?>
</span>
<hr><a href="index.php?s=maths&a=stats&q=<?=$q?>">Check your stats.</a>
<?php
else: ?>
<span class="guest_mode">If you were <a href="index.php?a=register&z">registered</a> and <a href="index.php?a=login&z">logged in</a>, this box would show your score for this category. No guest statistics are saved.</span>
<?php endif; ?>
</div><br><br>
<?php if(!empty($cat['guide_text'])): ?>
<div class="main_box_content single half_width tips left"><div class="con_box left"><details><summary>Guide for this category...</summary><?=$cat['guide_text']?></details></div></div><?php endif;?>
<?php $res = "maths/play_support.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>