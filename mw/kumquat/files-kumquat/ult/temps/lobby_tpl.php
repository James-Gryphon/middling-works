<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="first_box_checker" class="title_header"><h2>Ultimate Tic-Tac-Toe</h2></label>
<input type="radio" id="second_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="second_box_checker" class="title_header"><h2>Public Matches</h2></label>
<input type="radio" id="third_box_checker" name="cat_buttons" class="hidden box_checker" checked>
<label for="third_box_checker" class="title_header"><h2>Your Matches (<?=$player_match_count?> out of <?=$player_match_limit?>)</h3></label>
<input type="radio" id="fourth_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="fourth_box_checker" class="title_header"><h2>Your Invites (<?=$player_invite_count?> out of <?=$player_match_limit?>)</h3></label>

<br><br>
<div class="main_box_content cbox first">
<div class="mini_box">
<b>Create a Match</b><br>
<?php 
if($nonewmatches === true):
?>
<span class="z3"><i>You can't create or join matches because you're
<?php if($unregistered === true): ?> not logged into an account. <a href='index.php?a=register&z'>Create an account</a> or <a href='index.php?a=login&z'>log in</a> to use this feature.</i></span><br>
<?php else:?>already in <?=$player_match_count?> active matches.</span><br>
<?php
if($member_auth_level <= 0 && $player_match_count < $max_match_limit):?>
<span class="z3">Finish a match, or become a  <a href='index.php?s=ult&a=whysubscribe'>subscribing member</a>, to start a new one.</i></span><br>
<?php else:?>
<span class="z3">Finish some of those, and then you'll be able to start a new one!</i></span><br>
<?php
endif;
endif;
endif;
?>
<form name="create_match" method="post" action='index.php?s=ult&a=lobby'>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="hidden" id="create_match" name="create_match" value="1">
<label for="match_length">Best of x matches:</label><br>
<select id="match_length" name="match_length" <?php if ($nonewmatches === true){echo 'disabled';}?>>
<option value='1'>1</i></option>
<option value='2'>2</i></option>
<option value='4'>4</i></option>
<option value='6'>6</i></option>
<option value='8'>8</i></option>
<option value='10'>10</i></option>
<option value='12'>12</i></option>
<option value='16' <?php if($member_auth_level <= 0){echo "disabled";}?>>16</i></option>
<option value='20' <?php if($member_auth_level <= 0){echo "disabled";}?>>20</i></option>
<option value='24' <?php if($member_auth_level <= 0){echo "disabled";}?>>24</i></option>
<option value='32' <?php if($member_auth_level <= 0){echo "disabled";}?>>32</i></option>
<option value='40' <?php if($member_auth_level <= 0){echo "disabled";}?>>40</i></option>
</select><br>
<label for="clock">Clock</label><br>
<select id="clock" name="clock" <?php if ($nonewmatches === true){echo 'disabled';}?>>
<option value='none'>1yr for each</option>
<option value='standard' <?php if($member_auth_level <= 0){echo "disabled";}?>>30min for each</i></option>
<option value='short' <?php if($member_auth_level <= 0){echo "disabled";}?>>15min for each</i></option>
</select><hr>
<details>
<summary>First Move</summary>
<input type="radio" name="mover" value='standard' id='standard_mover' checked <?php if($member_auth_level <= 0 || $nonewmatches === true){echo "disabled";}?>>
<label for='standard_mover'>Standard</label><br>
<input type="radio" name="mover" value='guest' id='guest_mover' <?php if($member_auth_level <= 0 || $nonewmatches === true){echo "disabled";}?>>
<label for='guest_mover'>Guest</label><br>
<input type="radio" name="mover" value='random' id='rand_mover' <?php if($member_auth_level <= 0 || $nonewmatches === true){echo "disabled";}?>>
<label for='rand_mover'>Random</label><br>
<input type="radio" name="mover" value='host' id='host_mover' <?php if($member_auth_level <= 0 || $nonewmatches === true){echo "disabled";}?>>
<label for='host_mover'>Host*</label><br>
<i class="z3">* Available in private matches only.</i><br>
</details><hr>
<label for="ot">Overtime/Tiebreaks</label>
<input type="checkbox" id="ot" name="ot" <?php if ($nonewmatches === true){echo 'disabled';}?>><br>
<label for="private">Private?</label>
<input type="checkbox" id="private_match" name="private_match" checked <?php if ($nonewmatches === true){echo 'disabled';}?>>
<br>
Invite a Player?<br>
<input type="text" name="invite_name" size="20" <?php if(isset($errors['invite_fail'])){echo "class='error_wrap' ";} if ($nonewmatches === true){echo 'disabled';}?>><br>
<?php if(isset($errors['invite_fail'])){echo "<span class='z3 notice_text red'>", notice("bad_invite"), "</span><br>";}?>
<input type="submit" value="Start Game" <?php if ($nonewmatches === true){echo 'disabled';}?>><br>
</form>
</div>
<hr>
<div class="mini_box">
<form name="view_game" method="GET" action="">
<input type="hidden" name="s" value="ult">
<input type="hidden" name="a" value="play">
<b>View a Private Match</b><br>
<label for="match_code">Match Code</label><br>
<input type="text" name="match_code" size="17"><br>
<input type="submit" value="View Match"><br>
</form>
</div>
<hr>
<div class="mini_box">
    <?php
    if($member_auth_level === 1)
    {
        echo "<span class='z3'>Thanks for your support!</span><br>";
    }
    ?>
    <a class='z3' href='index.php?s=ult&a=whysubscribe'>About subscribing</a><br>
    <a class='z3' href='index.php?a=subscribe'>Subscription Manager</a>
</div>
<hr>
</div>
<div class="main_box_content lbox second">
<div class="lobby_box">
<table class="full_width">
<thead>
<tr>
<th>Host</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($public_match_list))
{
foreach($public_match_list as $public_match)
    {
    $public_match_host = get_username($public_match['host'], "ult");
    echo
    "<tr><td>", $public_match_host[1];
    $in_match = false;
    if($user_id == $public_match['host'] || $user_id == $public_match['guest']){$in_match = true;}
    if($in_match === false && $user_id != 0 && $nonewmatches === false){ echo " <a href='index.php?s=ult&a=lobby&match={$public_match['match_id']}&join'>(join)</a>";}
    echo "</td><td>{$public_match['match_length']}</td><td>", RulesDisplay($public_match), "</td><td>", qdateBuilder($public_match['created']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='4' class='center_text'><i>There are no open public matches at present.</i></td></tr>";}
?>

</tbody></table>
</div>
</div>
<div class="main_box_content lbox third">
<div class="your_matches">
<b>Active Matches</b><table class="full_width">
<tbody><tr>
<th>Opponent</th>
<th>Score</th>
<th>Rules</th>
<th>Next Game</th>
</tr>
<?php
if(!empty($active_matches))
{
    foreach($active_matches as $active_match)
    {
        $query = $pdo->query("SELECT `game_id`, `host_score`, `guest_score`, `status`, UNIX_TIMESTAMP(`host_start`) as `host_time`, UNIX_TIMESTAMP(`guest_start`) as `guest_time`,
        (SELECT count(`game_id`) from `ult_games` WHERE `match_id` = {$active_match['match_id']}) as `count`
         FROM `ult_games` WHERE `match_id` = {$active_match['match_id']} and (`status` = 'active' OR status = '')
         ORDER BY `game_id` ASC LIMIT 1"
         );
        // This doesn't seem very efficient; oh well...
        $cur_game = $query->fetch(PDO::FETCH_ASSOC);
        $count = $cur_game['count'];
        if($count * 2 > $active_match['match_length'] * 2){$length = $count * 2; $debug['clength'] = $length;} 
        else {$length = $active_match['match_length'] * 2; $debug['mlength'] = $length;}
        $temp = $length - $cur_game['host_score'] - $cur_game['guest_score'];
        if($user_id == $active_match['host'])
        {
            $opponent['username'] = get_username($active_match['guest'], "ult");
            $opponent['id'] = $active_match['guest'];
            $your_score = $cur_game['host_score'];
            $their_score = $cur_game['guest_score'];
        }
        else 
        {
            $opponent['username'] = get_username($active_match['host'], "ult");
            $opponent['id'] = $active_match['host'];
            $their_score = $cur_game['host_score'];
            $your_score = $cur_game['guest_score'];
        }
        echo
        "<tr><td>", $opponent['username'][1], "</td><td><a href='index.php?s=ult&a=match&match={$active_match['match_id']}'>{$your_score}-{$their_score} ($temp)</a></td><td>", RulesDisplay($active_match), "</td><td>";
        if($cur_game['status'] === 'active')
        {
            echo "<a href='index.php?s=ult&a=play&match={$active_match['match_id']}&gm={$cur_game['game_id']}'>In progress...";
            $temp = [$active_match['host'], $active_match['guest']];
            if(in_array($user_id, $temp))
            { // So you can show if it's your active move
                $first_mover = FirstMoverFinder($cur_game['game_id'], $temp, $active_match);
                $query = $pdo->query("SELECT move_id from `ult_moves` WHERE `match_id` = {$active_match['match_id']} AND `game_id` = {$cur_game['game_id']} ORDER BY `move_id` DESC LIMIT 1");
                $cur_move = $query->fetch(PDO::FETCH_ASSOC);
                if(empty($cur_move)){$cur_move = 0;} else {$cur_move = $cur_move['move_id'];}
                $temp = $cur_move % 2;
                if(($temp === 1 && $first_mover !== $user_id) || ($temp === 0 && $first_mover === $user_id))
                { 
                    echo " (move!)";
                }
            }
            echo "</a>";
        }
        else
        {
            if($cur_game['host_time'] == $cur_game['guest_time'])
            {
                echo dateBuilder($cur_game['host_time']);
            }
            else 
            {
                echo "Negotiating";
            }
        }
        echo "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't playing any matches.</i></td></tr>";}
?>	
</tbody></table><b>Hosted Matches</b><table class="full_width">
<tbody><tr>
<th>Guest</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
</tr>
<?php
if(!empty($hosted_matches_list))
{
    foreach($hosted_matches_list as $active_match)
    {
        $active_match_guest = get_username($active_match['guest'], "ult");
	if($active_match_guest == ""){ $active_match_guest[1] = "None yet";}
    echo
    "<tr><td>", $active_match_guest[1], "</a></td><td>{$active_match['match_length']} <a href='index.php?s=ult&a=match&match={$active_match['match_id']}'>(view)</a></td>", "<td>", RulesDisplay($active_match), "</td><td>", qdateBuilder($active_match['created']), " <a href='index.php?s=ult&a=lobby&match={$active_match['match_id']}&leave'>(stop)</a></td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't hosting any matches.</i></td></tr>";}
?>
</tbody></table><b>Joined Matches</b><table class="full_width">
<tbody><tr>
<th>Host</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
</tr>
<?php
if(!empty($joined_matches_list))
{
    foreach($joined_matches_list as $active_match)
    {
        $active_match_host = get_username($active_match['host'], "ult");
    echo
    "<tr><td>", $active_match_host[1], "</a></td><td>{$active_match['match_length']} <a href='index.php?s=ult&a=match&match={$active_match['match_id']}'>(view)</a></td>", "<td>", RulesDisplay($active_match), "</td><td>", qdateBuilder($active_match['created']), " <a href='index.php?s=ult&a=lobby&match={$active_match['match_id']}&leave'>(stop)</a></td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't joined to anyone else's match.</i></td></tr>";}
?>
</tbody></table><b>Your Last 7 Completed Matches <a href="index.php?s=ult&a=archive&filter=yours">(view all)</a></b><table class="full_width">
<tbody><tr>
<th>Opponent</th>
<th>Score</th>
<th>Rules</th>
<th>Finished</th>
</tr>
<?php
if(!empty($completed_matches))
{
    foreach($completed_matches as $active_match)
    {
        $query = $pdo->query("SELECT game_id, host_score, guest_score FROM `ult_games` WHERE `match_id` = {$active_match['match_id']} AND ISNULL(`host_score`) = 0 ORDER BY `game_id` DESC");
        // This doesn't seem very efficient; oh well...
        $cur_game = $query->fetchAll(PDO::FETCH_ASSOC);
        $count = count($cur_game);
        $debug['mcount'] = $count;
        $debug['mlength'] = $active_match['match_length'];
        if($count * 2 > $active_match['match_length'] * 2){$length = $count * 2; $debug['clength'] = $length;} 
        else {$length = $active_match['match_length'] * 2; $debug['mlength'] = $length;}
        $temp = $length - $cur_game[0]['host_score'] - $cur_game[0]['guest_score'];
        $one_score = $cur_game[0]['host_score'];
        $two_score = $cur_game[0]['guest_score'];
        if($user_id == $active_match['host'])
        {
            $opponent['username'] = get_username($active_match['guest'], "ult");
            $opponent['id'] = $active_match['guest'];
        }
        else
        {
            $opponent['username'] = get_username($active_match['host'], "ult");
            $opponent['id'] = $active_match['host'];
            var_swap($one_score, $two_score);
        }
        if($one_score > $two_score){$outcome_string = "Won";}
        elseif($two_score > $one_score){$outcome_string = "Lost";}
        else{$outcome_string = "Drawn";}
    echo
    "<tr><td>", $opponent['username'][1], "</td><td><a href='index.php?s=ult&a=match&match={$active_match['match_id']}'>$one_score-$two_score ($temp)</a></td><td>", RulesDisplay($active_match), "</td><td><b>$outcome_string</b> ", qdateBuilder($active_match['updated']),"</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You haven't completed any matches recently.</i></td></tr>";}
?>
</tbody></table>
</div>
</div>
<div class="main_box_content lbox fourth">
<div class="your_invites">
<b>Invites You Sent</b><table class="full_width">
<tbody><tr>
<th>Guest</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
</tr>
<?php
if(!empty($invites_from_you))
{
    foreach($invites_from_you as $active_match)
    {
        $active_match_guest = get_username($active_match['guest'], "ult");
	if($active_match_guest == ""){ $active_match_guest[1] = "None yet";}
    echo
    "<tr><td>", $active_match_guest[1], "</td><td>{$active_match['match_length']}</td>", "<td>", RulesDisplay($active_match), "</td><td>", qdateBuilder($active_match['created']), " <a href='index.php?s=ult&a=lobby&invite={$active_match['match_id']}&cancel'>(cancel)</a></td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You haven't sent any active invites.</i></td></tr>";}
?>
</tbody></table><b>Invites To You</b><table class="full_width">
<tbody><tr>
<th>Host</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
</tr>
<?php
if(!empty($invites_to_you))
{
    foreach($invites_to_you as $active_match)
    {
        $active_match_host = get_username($active_match['host'], "ult");
    echo
    "<tr><td>", $active_match_host[1], "</td><td>{$active_match['match_length']}</td>", "<td>", RulesDisplay($active_match), "</td><td>", qdateBuilder($active_match['created']); 
    if($player_match_count < $player_match_limit)
    {
    echo "<a href='index.php?s=ult&a=lobby&invite={$active_match['match_id']}&accept'>(accept)</a>";
    }
    else
    {
        echo "<s>(accept></s>";
    }
    echo "<a href='index.php?s=ult&a=lobby&invite={$active_match['match_id']}&cancel'>(decline)</a>";
    echo "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You don't have any invites.</i></td></tr>";}
?>
</tbody></table>
</div>
</div>

<?php
// $res = "ult/lobby.js"; 
?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>
