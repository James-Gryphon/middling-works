<center>
<a id="most_specific" class="skip">Content</a>
<div class="title_header"><h3>The Plus-Minus Game</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box center">
<span class="z5"><b>Game #<?=$list['game_id']?></b></span>
<div class="score_box"><span class="z6 score"><span class="z2"><?=$list['minusmin']?></span> ... <b><?=$list['count']?></b> ... <span class="z2"><?=$list['plusmax']?></span></span></div>
<?php if(!empty($errors['plusminus'])){ foreach($errors['plusminus'] as $error => $value){
echo notice($error, "autoinline");
}}
?>
Last mover: <br><?php echo $callsign; if($list['last_plus_or_minus'] == 1){echo " <b>incremented</b>";}elseif($list['last_plus_or_minus'] == -1) {echo " <i>decremented</i>";}?><br>To increase or decrease the score, type in the number that is one greater or smaller than the current one, which is shown in <b>bold</b>.<br>
<form method="POST" name="game_form" action="">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="number" id="number" name="number" autocomplete="off">
<br>
<input type="submit" value="Submit">
</form>
<hr>
<b>Roster for Game #</b>
<span class="inliner"><form method="GET" name="roster_form"><input type="hidden" name='s' value='plusminus'><input type="hidden" name='a' value='game'><input type="number" value="<?=$roster_game_id?>" id="gr" name="gr">
<input type="submit" value="View"></form></span>
<br>
<table>
<thead><tr><th>Player</th><th>Impact</th></tr></thead>
<tbody>
<?php
if(!empty($scores_for_game)):
    foreach($scores_for_game as $score)
    {
        // Process the local_id
        $localer_id = $pdo->prepare("SELECT * from plus_minus_movers WHERE local_id = :local_id");
        $localer_id->execute([
            ':local_id' => $score['local_id']
        ]);
        $localer_id = $localer_id->fetch(PDO::FETCH_ASSOC);
        $local_callsign = "#";
        $local_callsign .= $localer_id['local_id'];
        if(!empty($localer_id['user_id']))
        {
            $localer_name = $pdo->prepare("SELECT username from home_accounts WHERE id = :id");
            $localer_name->execute([
            ':id' => $localer_id['user_id']]);
            $localer_name = $localer_name->fetch(PDO::FETCH_ASSOC);
            $local_callsign .= " ";
            $local_callsign .= $localer_name['username'];
        }
        echo "<tr><td>$local_callsign</td><td>{$score['impact']}</td></tr>";
    }
endif;
?>
</tbody></table>

</form>
</div>
</div>
</center>