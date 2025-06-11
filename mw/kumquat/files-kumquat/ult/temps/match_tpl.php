<div class="title_header"><h3>Ultimate</h3></div><br><br>
<div class="main_box_content single center">
          <?php
      if($in_match != -1)
      : $debug['mod_match_form'] = "true"; ?>
		<form method='post' action=''>
                    <input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
                        <?php
      endif;
      ?>
  <table class="full_width">
    <tbody>
      <tr>
        <td class="host equal"><?=$hostname[1]?></td><td class="neutral equal">versus</td><td class="guest equal"><?=$guestname[1]?></td>
      </tr><tr>
        <td colspan="3"><b><u>Best of <?=$match_data['match_length']?></u></b><br>
	First mover: 
	<?php 
	if($match_data['mover'] === "standard")
	{
		if($match_data['match_length'] == 1){echo "Random <i class='z3'>(Standard)</i>";}
		else{echo "Alternating <i class='z3'>(Standard)</i>";}
	}
	elseif($match_data['mover'] === "host"){echo "Host";}
	elseif($match_data['mover'] === "random"){echo "Random";}
	else{echo "Guest";}
	?>
	<br>Clock: <?php
	if($match_data['clock'] == "30min"){echo "30 minutes per player";}
	elseif($match_data['clock'] == "15min"){echo "15 minutes per player";}
	else{echo "1 year per player";}
	if($match_data['ot'] === 1){echo "<br><b>Overtime</b> enabled";}
  if($match_data['private'] === 1){echo "<br>Private match";} else {echo "<br>Public match";}
	?>
	</td>
      </tr>
    <tr>
        <th>Host</th><th>Draws</th><th>Guest</th>
      </tr>
      <tr>
        <td class="host"><?=$host_wins?></td><td><?=$draws?></td><td class="guest"><?=$guest_wins?></td>
      </tr></tbody>
  </table>  
  <table class="border_adjust full_width">
    <tbody>
          <tr>
        <th>#</th><th>Date</th><th>Leader</th><th>Outcome</th><th>Score</th>
      </tr>
    <?php
    $debug['test_time'] = $game;
    foreach($game as $id => $item)
    { 
        /* 
         * FOUR, not three, states:
         * - : Not needed - used for extra games in a match after it's finished, or for empty fields
         * Single time - shown after a game starts, or when both players agree, but to guests
         * Two times - One for host, one for guest - used for players in the match, before game starts
         * Also, text-only, to spectators
         */
        if(($item['status'] !== "" && $item['status'] !== "inactive") || (($item['status'] == "") && $in_match === -1 && $item['host_time'] == $item['guest_time']) && $item['host_time'] != 0)
        { // For single-time display - shows when the game starts, and to guests when players agree
          if($match_data['clock'] !== 'none'){  
          $date = dateBuilder($item['host_time']);}
          else {$date = "Pending";}
        }
    else
    { // This covers the rest
    if($match_data['status'] === "completed" && $item['status'] === "N")
    {
        $date = "Not needed";
    }
		elseif((empty($item['status']) || $item['status'] === "inactive") && $in_match != -1)
		{
      if($match_data['clock'] === 'none')
      {
        $date = "Pending*";
      }
      else 
      {
      if($in_match === "guest")
      {
        if(!empty($item['host_time']))
        {
          $their_time = dateBuilder($item['host_time']);
			  }
			  else {$their_time = "Unset";}
        if(!empty($item['guest_time']))
        {
            $your_time = getdate($item['guest_time']);
        }
        else {$your_time = getdate(883162800);}
        $your_time['mday'] = str_pad($your_time['mday'], 2, "0", STR_PAD_LEFT);
        $your_time['mon'] = str_pad($your_time['mon'], 2, "0", STR_PAD_LEFT);
        $your_time['hours'] = str_pad($your_time['hours'], 2, "0", STR_PAD_LEFT);
        $your_time['minutes'] = str_pad($your_time['minutes'], 2, "0", STR_PAD_LEFT);

      }
      else
      {
          if(!empty($item['guest_time']))
          {
                  $their_time = dateBuilder($item['guest_time']);
          }
          else {$their_time = "Unset";}
          $debug['host_time'] = $item['host_time'];
          if(!empty($item['host_time']))
          {
              $your_time = getdate($item['host_time']);
          }
          else {$your_time = getdate(883162800);}
          $your_time['mday'] = str_pad($your_time['mday'], 2, "0", STR_PAD_LEFT);
          $your_time['mon'] = str_pad($your_time['mon'], 2, "0", STR_PAD_LEFT);
          $your_time['hours'] = str_pad($your_time['hours'], 2, "0", STR_PAD_LEFT);
          $your_time['minutes'] = str_pad($your_time['minutes'], 2, "0", STR_PAD_LEFT);

      }
                $debug['your_time'] = $your_time;
                $your_time_date = $your_time['year'] . "-" . $your_time['mon'] . "-" . $your_time['mday'];
                $your_time_time = $your_time['hours'] . ":" . $your_time['minutes'];
          if($item['host_time'] == $item['guest_time'] && !empty($item['host_time'])){$their_time = " 🗸";} else 
          {
            $their_time = " vs. <div class='their_time'>{$their_time}<input type='checkbox' name='mod_game_mbox_{$id}'></div>";
          }

		  $date = "<div class='time_block'><input type='date' id='mod_game_date_{$id}' name='mod_game_date_{$id}' value='$your_time_date'><input type='time' id='mod_game_time_{$id}' name='mod_game_time_{$id}' value='$your_time_time'></div>{$their_time}</div>";
		}
  }
                
		else // This is for spectators
                {       
                  if($match_data['clock'] === 'none')
                  {
                    $date = "Pending";
                  }
                  else
                  {
                    if(isset($item['host_time']))
                    {
                        $host_time = dateBuilder($item['host_time']);
                    } 
                    else 
                    {
                        $host_time = "Unset";
                    }
                    if(isset($item['guest_time']))
                    {
                        $guest_time = dateBuilder($item['guest_time']);
                    }
                    else 
                    {
                        $guest_time = "Unset";
                    }
                    if($host_time === $guest_time){$guest_time = "";
                      if($host_time == "Unset"){ $host_time = "Both unset";}}
                    $date = "<div class='time_block'>{$host_time}</div> vs. <div class='time_block their_time'>{$guest_time}</div>";
                }
              }
    }
		  echo "<tr><td class='left'>$id</td><td>{$date}</td><td>{$item['leader']}</td>";
      if($outcomes[$id-1]['outcome'] === $players[0]){$outcomecolor = "host";}
      elseif($outcomes[$id-1]['outcome'] === $players[1]){$outcomecolor = "guest";}
      else{$outcomecolor = "neutral";}
      echo "<td class='$outcomecolor'>{$item['outcome']}</td>";
      $leadcolor = "neutral";
      if ($item['status'] != "N" && $item['status'] != "inactive" && $item['status'] != "" && $item['status'] != "active")
      {
        if($outcomes[$id-1]['host_score'] > $outcomes[$id-1]['guest_score'])
        {
          $leadcolor = "host";
        }
        elseif($outcomes[$id-1]['guest_score'] > $outcomes[$id-1]['host_score'])
        {
          $leadcolor = "guest";
        }
      }
    echo "<td class='$leadcolor'>{$item['score']}</td></tr>";
    }
    ?>
    </tbody>
  </table>
<?php
if(!empty($in_match)):
?>
  <input type="submit" value="Update Times" id="update" <?php if($match_data['status'] === "completed"){echo " disabled";}?>><br>
<?php
endif;
?>
  <label for="match_status_box"><b>Status: </b><?=$match_data['status']?></label><br>
<?php
if(!empty($in_match)):
?>
<div class="startbox">
    <?php // rewrite this section to change the start checkbox based on whether you drew, and to switch ? and ! ?>
  <input type="checkbox" id="start_status_box" name="start_status_box" 
      <?php $punctuation = "?"; if(($match_data['start_status'] == 1 && $act_player == $players[0]) || $match_data['start_status'] == 2 && $act_player == $players[1])
      {
          echo " checked";
      } 
      elseif(($match_data['start_status'] == 1 && $act_player == $players[1]) || ($match_data['start_status'] == 2 && $act_player == $players[0])){$punctuation = "!";}
      if($cur_move){echo " disabled";}
      ?>
         ><input type="submit" id="start_status" name="start_status" value="Start Now<?=$punctuation?>" <?php if(!$cur_mover){echo " disabled";}?>></div>
  <br>
<div class="resignbox">
<input type="submit" value="Resign Match" id="resign" <?php if($match_data['status'] !== "active"){echo " disabled";}?>>
<input type="checkbox" id="resign1" <?php if($match_data['status'] !== "active"){echo " disabled";}?>>
<input type="checkbox" id="resign2" <?php if($match_data['status'] !== "active"){echo " disabled";}?>>
</div><br>
<input type="submit" value="Leave Match" <?php 
if($match_data['status'] != "open"){ echo "disabled ";}
?>
id="leave">
<?php
?>
</form>
<?php
endif;
?>
<div class="info_box" tabindex="-1">
<?php if(isset($in_match)): ?>
<span class='z3'>Match Code: <?=$match_data['access_code']?> <?php if($match_data['private'] == 1){ echo "<i>(private match)</i>"; }?></span><br>
<?php endif; ?>
<span class='z3'>Created: <?=qdateBuilder($match_data['created'], true)?></span><br>
<span class='z3'>Last Activity: <?=qdateBuilder($match_data['updated'], true)?></span><br>
</div>
</div>