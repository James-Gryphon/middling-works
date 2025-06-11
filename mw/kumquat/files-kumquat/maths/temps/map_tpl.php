<div id="math_map">
<?php $res = "stuff/maths_map.svg"; ?>
<img src="<?=$res?>?vers=<?=filemtime("$res")?>"></img>
<?php if(isset($_SESSION['id'])):?>
<input type="checkbox" id="stat_box" name="stat_box">
<label for="stat_box" id="stats_label">Statistics</label>
<?php
endif;
// Add something to axe oldest problem on record if it gets over 30.
foreach($cats as $key => $value)
{
    if(isset($user_cats[$key]))
    {
    $percent = 100*(round($user_cats[$key]['correct_total'] / $user_cats[$key]['count'], 2));
    $percentage = "{$percent}%";
    if($user_cats[$key]['count'] >= 30){ $adjust = 1;} else {
    $adjust = ($user_cats[$key]['count'] - 1) / $user_cats[$key]['count']; }
    $score = $percent * round($adjust,2,PHP_ROUND_HALF_DOWN);
    if($score == 100){$percentage = "√";}
    if($score >= 90){ $score_class = "sound";}
    elseif($score >= 80){ $score_class = "good";}
    elseif($score >= 70){ $score_class = "middling";}
    else{ $score_class = "weak";}
    }
    else {$percentage = " - "; $score_class = ""; $user_cats[$key]['avg_time'] = "";    }
    $user_cats[$key]['score_class'] = $score_class;
    $user_cats[$key]['percentage'] = $percentage;
}

if(isset($_SESSION['last_q'])){$user_cats[$_SESSION['last_q']]['score_class'] .= " last_q";}
foreach($cats as $key => $value)
{
    echo "<span tabindex='-1' id='q{$value['cat_id']}_site' class='site {$user_cats[$key]['score_class']}'>
    <span class='box'><span class='main_type'>{$user_cats[$key]['percentage']}</span><span class='stat_type'>{$user_cats[$key]['avg_time']}</span></span>
    <a class='main_type' id='q{$value['cat_id']}_link' href='index.php?s=maths&a=game&q={$value['cat_id']}'>{$value['cat_name']}</a><a class='stat_type' id='q{$value['cat_id']}_link' href='index.php?s=maths&a=stats&q={$value['cat_id']}'>{$value['cat_name']}</a></span>";
}
?>
</div>
<?php $res = "maths/map_support.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>