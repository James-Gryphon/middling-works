<div class="title_header center_text"><h3>Ultimate</h3></div><br>
<div class="main_box_content single">
<div class="game_box">
<div class="left_wing ult">

<?php
echo "<table id='game_table'>";
// Start with 7 board, 7 cell
// Then, add two - when get to 3,
// Add 1 board, subtracting cell by 2 (to make it 7)
// When get to 9 board, then subtract 3 board, then 
// 77 78 79 87 88 89 97 98 99
// 74 75 76 84 85 86 94 95 96
// 71 72 73 81 82 83 91 92 93
$b = 7; $c = 7;
$newrow = 1;
$newboard = 1;
$count = 1;
$done = 1;
while($c > 0 && $b > 0)
{
	echo "<tr>";
	while($newboard != 4)
	{
		while($count != 4)
		{
			$td = "<td class='";
			$valcolor = ColorChooser($last_move['piece'], "O", "X", true);
			$valcolor .= "v";
			if(isset($valid_moves[$b][$c]) && empty($valid_moves[$b][$c])){$td .= "valid {$valcolor}";}
			if(!empty($board_array[10][$c])){ $td .= " freeMove";}
			if(!empty($board_array[10][$b])) // This checks for won boards
			{
				$valcolor = ColorChooser($board_array[10][$b], "X", "O");
				$valcolor .= "w";
				$td .= " {$valcolor}";
			}
			if(!empty($board_array[$b][$c])) // Existing piece
			{
				$valcolor = ColorChooser($board_array[$b][$c], "X", "O");
				$td .= " {$valcolor}p";
				if($b == $last_move['board'] && $c == $last_move['cell'])
				{
					$td .= " lastMove";
				}
                                $td .= " 'id=cell_{$b}{$c}>{$board_array[$b][$c]}";
			}
			else{$td .= "'>{$b}{$c}";}
			$td .= "</td>";
			echo $td;
			++$count; ++$c;
		} // 8, 9 - 5, 6 - 2, 3
		$count = 1; $c -= 3; // 7 - 4 - 1
		++$newboard; ++$b; // 2-8, 3-9
	}
	++$newrow; // 2
	$newboard = 1;
	$c -= 3; // 4, 1
	$b -= 3; // 7
	echo "</tr>";
	if($newrow == 4){$newrow = 1; $b -= 3; $c = 7;}
}
echo "</table>";
?>
</div>
    
<div class="right_wing">
  <table class="full_width" role="presentation">
    <tbody>
      <tr>
          <?php
              $first_mover_class = "host";
              $second_mover_class = "guest";
              $first_mover_string = '<td class="host equal" colspan="2">'; 
              $first_mover_string .= $hostname[1];
              $first_mover_string .= "</td>";
              $second_mover_string = '<td class="guest equal" colspan="2">';
              $second_mover_string .= $guestname[1];
              $second_mover_string .= "</td>";
              $first_mover_score_string = "<td class='host equal_half' colspan='3'>$host_display_score</td>";
              $second_mover_score_string = "<td class='guest equal_half' colspan='3'>$guest_display_score</td>";
              $first_mover_clock_string = "<td class='host equal_half' colspan='3' id='host_clock'>$host_clock_display</td>";
              $second_mover_clock_string = "<td class='guest equal_half' colspan='3' id='guest_clock'>$guest_clock_display</td>";
              if($first_mover_role === $players[1])
              {
                  var_swap($first_mover_string, $second_mover_string);
                  var_swap($first_mover_class, $second_mover_class);
                  var_swap($first_mover_score_string, $second_mover_score_string);
                  var_swap($first_mover_clock_string, $second_mover_clock_string);

              }
              ?>
        <?=$first_mover_string?><td class="neutral equal" colspan="2">versus</td><?=$second_mover_string?></tr>
      <tr>
          <td colspan="6"><b>Game <?=$game_id?></b><br><div class='z3'><b><a href='index.php?s=ult&a=match&match=<?=$match_data['match_id']?>'>Best of <?=$match_data['match_length']?> (view match)</a></b><br>
	First mover: 
	<?php 
	if($match_data['mover'] === "standard")
	{
		if($match_data['match_length'] == 1){echo "Random <i class='z3'>(Standard)</i>";}
		else{echo "Alternating<i class='z3'>(Standard)</i>";}
	}
	elseif($match_data['mover'] === "host"){echo "Host";}
	elseif($match_data['mover'] === "random"){echo "Random";}
	else{echo "Guest";}
	?>
	<br>Clock:<span id="clock_setting"> <?php
	if($match_data['clock'] === "30min"){echo "30 minutes per player";}
	elseif($match_data['clock'] === "15min"){echo "15 minutes per player";}
	else{echo "1 year per player";}
  ?></span>
  <span id='refresh_on' class='hidden'><?php
  if($game_data['status'] == "active")
  {
    if($cur_mover != 1 && $act_player != 0)
    {
      echo "yes";
    } else {echo "no";}
  } else {echo "inactive";}
  ?></span><span id='cur_mover' class='hidden'><?php
  echo "{$mover}"?></span><span id='last_active' class='hidden'><?=$last_move['move_time']?></span>
  <?php
	if($match_data['ot'] === 1){echo "<br><b>Overtime</b> enabled";}
  if($match_data['private'] === 1){echo "<br>Private match";} else {echo "<br>Public match";}
  if($game_data['outcome'] !== 0)
  {
    if($game_data['outcome'] == $players[0]){echo "<br> <span class='z5'><i><b>", $hostname[0];}
    elseif($game_data['outcome'] == $players[1]){echo "<br> <span class='z5'><i><b>", $guestname[0];}
  }
  switch($game_data['status'])
  {
    case "active": 
      if($game_data['draw_offer'] !== 0 && $act_player === $cur_mover)
      {
        echo "<br><span class='z5'><i><b>Your opponent offered a draw.</b></i></span>";
      } break;
    case "draw": echo "<br><span class='z5'><i><b>The game was drawn (=)</b></i></span>"; echo "<br><a target='_blank' href='https://uttt.ai/init?actions="; echo ActionExporter($move_data); echo "'>Analyze</a>"; break;
    case "mresign":
    case "resign": echo " won by resignation (--)</b></i></span>"; echo "<br><a target='_blank' href='https://uttt.ai/init?actions="; echo ActionExporter($move_data); echo "'>Analyze</a>"; break;
    case "time": echo " won by time (...)</b></i></span>"; echo "<br><a target='_blank' href='https://uttt.ai/init?actions="; echo ActionExporter($move_data); echo "'>Analyze</a>"; break;
    case "win": echo " won (#)</b></i></span>"; echo "<br><a target='_blank' href='https://uttt.ai/init?actions="; echo ActionExporter($move_data); echo "'>Analyze</a>"; break;
  }
  if($game_data['game_id'] > 1)
  {
    $prev_game = $game_data['game_id'] - 1;
    echo "<br><a href='index.php?s=ult&a=play&match={$match_data['match_id']}&gm={$prev_game}'>[previous game]</a>";
  }
  if(isset($next_game) && $game_data['status'] !== "active" && ($next_game['status'] !== "N" || $next_game['status'] !== "inactive"))
  {
    echo "<br><a href='index.php?s=ult&a=play&match={$match_data['match_id']}&gm={$next_game['id']}'>[next game]</a>";
  }
        ?></div>
	</td>
      </tr>
    <tr>
    <th colspan='6'>Score</th>
      </tr>
      <tr>
          <?=$first_mover_score_string, $second_mover_score_string?>
      </tr>
      <tr><td colspan="6">
              <form name="ult_mover" id='ult_mover' method="POST">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="text" id="ult_move" name="ult_move" <?php if(!$cur_mover){echo " disabled";}?>>
<input type="submit" id="move_submit" name="move_submit" value="Move" <?php if(!$cur_mover){echo " disabled";}?>>
<div class="drawbox">
    <?php // rewrite this section to change the draw checkbox based on whether you drew, and to switch ? and ! ?>
  <input type="checkbox" id="draw_offer_box" name="draw_offer_box" 
      <?php $punctuation = "?"; if(($game_data['draw_offer'] == 1 && $act_player == $players[0]) || $game_data['draw_offer'] == 2 && $act_player == $players[1])
      {
          echo " checked";
      } 
      elseif(($game_data['draw_offer'] == 1 && $act_player == $players[1]) || ($game_data['draw_offer'] == 2 && $act_player == $players[0])){$punctuation = "!";}
      if(!$cur_mover){echo " disabled";}
      ?>
         ><input type="submit" id="draw_offer" name="draw_offer" value="Draw<?=$punctuation?>" <?php if(!$cur_mover){echo " disabled";}?>></div>
  <br>
<div class="resignbox">
<input type="submit" value="Resign Game" id="resign" name="resign" <?php if(!$cur_mover){echo " disabled";}?>>
<input type="checkbox" id="resign1" name="resign1" <?php if(!$cur_mover){echo " disabled";}?>>
<input type="checkbox" id="resign2" name="resign2" <?php if(!$cur_mover){echo " disabled";}?>>
</div>
</form>
          </td></tr>
          <tr>
        <th colspan='6'>Clock</th>
      </tr>
      <tr>
          <?=$first_mover_clock_string, $second_mover_clock_string?>
      </tr>
      <tr>
        <th colspan='6'>Moves</th>
      </tr><tr>
        <td colspan="6"><div class="move_box">
            <?php
            $alt_yet = false;
            foreach($move_data as $move_id => $move_details)
            {
                $span = "<span class='";
                if($move_details['player'] == $players[0])
                {
                    $span .= "host";
                }
                else {$span .= "guest";}
                if($alt_yet === true){
                    $span .= " alt";
                }
                $span .= "'>{$move_details['move_id']}. {$move_details['move']} @ {$move_details['performed_string']}</span>";
                echo "$span";
                $alt_yet = !$alt_yet;
            }
            ?><a id="lma">
        </div></td>
      </tr></tbody>
  </table>  
    </div>
</div>
</div>
    

<?php $res = "ult/play.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>
