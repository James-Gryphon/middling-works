<?php $res = "thehunt/play.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<div class="title_header center_text"><h3>The Hunt</h3></div><br><br>
<div class="main_box_content single">
<div class="game_box">
<div class="left_wing">
<div class="map_box"><img src="thehunt/boards/map.php"></img></div>
<div class="info_box" tabindex="-1">
<span class='z6'><b><?=$game_data['game_name']?></b></span><span class="hidden"><span id="game_id"><?=$game_data['game_id']?></span></span><br>
<?php if(isset($act_player)): ?>
<span class='z3'>Game Code: <?=$game_data['access_code']?> <?php if($game_data['private'] == 1){ echo "<i>(private game)</i>"; }?></span><br>
<?php endif; ?>
<span class='z3'>Created: <?=qdateBuilder($game_data['created'], true)?></span><br>
<span class='z3'>Last Activity: <?=qdateBuilder($game_data['updated'], true)?></span> <span class="hidden">(<span id='last_active'><?=$game_data['timestamp']?></span>)</span><br>
<span class='z3'>Board: <?=$board_data['board_name']?></span><br>
<span class='z3'>Status: <span id='game_status'><?=$game_data['game_status']?></span></span>
</div>
</div>

<div class="right_wing">
<?php
if($game_data['game_status'] !== "open" && $act_player != 0):
?>
<div class="location_blurb" tabindex="-1">
<div class="region_box">
<?php
echo "<span class='z2'><b>";
echo $continent_data[$Territories[$player_data[$act_player-1]['location']]['continent_id']-1]['continent_name'];
echo "</b></span><br><span class='z3'>";
echo $continent_data[$Territories[$player_data[$act_player-1]['location']]['continent_id']-1]['continent_blurb'];
echo "</span>";
?>
</div>
<div class="territory_box">
<?php
echo "<b>";
echo $Territories[$player_data[$act_player-1]['location']]['territory_name'];
echo "</b><br>";
echo $Territories[$player_data[$act_player-1]['location']]['territory_blurb'];
?>
</div>
</div>
<?php
else:
    if($act_player != 0)
    {
    echo "<div class='location_blurb'><i>The game hasn't started yet.</i></div>";
    }
    else
    {
    echo "<div class='location_blurb'><i>You are a spectator in this game.</i></div>";
    }
endif;
?>
<div class="orders_box">
<?php 
if($game_data['game_status'] == "active")
{
echo "<span id='turns_left'>{$player_data[$game_data['current_player']-1]['username']} has: {$game_data['current_energy']} turns</span><br>";
}
// JS helper - are you the active player or not
echo "<span class='hidden' id='active_player'>"; if($game_data['current_player'] === $act_player){echo "1";} else {echo "0";} echo "</span>";
?>
<div class="card_box" tabindex="-1">
<form name="card_player" method="POST">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<select name="card_chooser" id="card_chooser" multiple="" <?php if($act_player !== $game_data['current_player']){ echo " disabled";}?>>
<?php
if(!empty($player_data[$act_player-1]['inventory']['cards'])){
foreach($player_data[$act_player-1]['inventory']['cards'] as $card_type => $card_count){
echo "<option value='{$card_type}'>{$card_types[$card_type-1]['card_name']} ({$card_count})</option>";
}
}
else {echo "<option value='none' disabled>No cards in hand</option>";}
?>
</select><br><input type="submit" value="Play Card" <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo " disabled";}?>></form></div><div class="move_box" tabindex="-1">
<form name="loc_chooser" method="POST">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<select name="loc_chooser" id="loc_chooser" multiple=""
<?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo " disabled";}?>>
<?php
// insert foreach option here
if($game_data['game_status'] === "active" && $act_player != 0): // insert sorter here
$link_selects = array();
foreach($Territories[$player_data[$act_player-1]['location']]['links'] as $id => $link)
{
    $link_selects[$Territories[$link['dest']]['territory_name']] = $link['dest'];
}
ksort($link_selects);

foreach($link_selects as $name => $id){
echo "<option value='{$id}'";
if(isset($bad_links[$id])){echo " disabled";}
echo ">{$name}</option>";
}
endif;
?>
</select><br><input type="submit" value="Move" <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo " disabled";}?>></form></div>
<?php
/* Pass should only be available when it is your turn
Start is only available to the host, when the game is 'open'
Join is only available to non-hosts, when the game is 'open'
Leave game is available to non-hosts when the game is open, or to anyone when the game is active
Stop game is only available to the host, when the game is 'open'
*/
?>
<div class="order_box" tabindex="-1"><form name="order_form" method="POST">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<div class="button_panel">
    <div class="button_box">
        <button id="capture" name="order_button" value="capture"
        <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo "disabled"; } ?>
        >Capture
        </button><br>
        <input type="checkbox" name="capture_box"
        <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo "disabled"; } ?>
        >
    </div>
    <div class="button_box">
        <button id="start" name="order_button" value="start"
        <?php if($act_player !== 1 || $game_data['game_status'] !== "open"){ echo "disabled"; } ?>
        >Start</button><br>
        <input type="checkbox" name="start_box"
        <?php if($act_player !== 1 || $game_data['game_status'] !== "open"){ echo "disabled"; } ?>
        >
    </div>
    <div class="button_box">
        <button id="pass" name="order_button" value="pass"
        <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo "disabled"; } ?>
        >Pass</button><br>
        <input type="checkbox" name="pass_box"
        <?php if($act_player !== $game_data['current_player'] || $act_player === 0){ echo "disabled"; } ?>
        >
    </div>
    <div class="button_box">
        <button id="join" name="order_button" value="join"
        <?php if($act_player !== 0 || $game_data['game_status'] !== "open" || $nonewgames === true){ echo "disabled"; } ?>
        >Join</button><br>
        <input type="checkbox" name="join_box"
        <?php if($act_player !== 0 || $game_data['game_status'] !== "open" || $nonewgames === true){ echo "disabled"; } ?>
        >
    </div>
    <div class="button_box">
        <button id="leave" name="order_button" value="leave"
        <?php if($game_data['game_status'] === "completed" || $act_player === 0 || ($game_data['game_status'] === "active" && $act_player !== $game_data['current_player'])){ echo "disabled"; } ?>
        >Leave</button><br>
        <input type="checkbox" name="leave_box"
        <?php if($game_data['game_status'] === "completed" || $act_player === 0 || ($game_data['game_status'] === "active" && $act_player !== $game_data['current_player'])){ echo "disabled"; } ?>
        >
    </div>
</div>
</form>
</div>
</div>
<div class="log_box" tabindex="-1">
<div class="move_info"><z5><b>Move Log</b></z5><br>
<div class="move_log">
<?php
// NEW MOVE LOG HANDLER
foreach($game_messages as $message)
{
    if(
        $message['secret'] == 0 ||
        ((($message['recipient'] == $act_player && $act_player != 0) || $game_data['game_status'] == "complete") && $message['secret'] == 1)
        )
    {
        echo $message['message'];
    }
}

/* OLD MOVE LOG HANDLER
foreach($player_move_data as $move){
// If not secret, or if local id is you, or if game is over, show action
if(
    $move['secret'] == 0 ||
    ((($move['local_id'] == $act_player && $act_player != 0) || $game_data['game_status'] == "complete") && $move['secret'] == 1)
    ){
switch($move['action']){
    case "move":
    echo say($player_data[$move['local_id']-1]['username']), " moves to <b>", $Territories[$actors['players'][$move['local_id']]['location']]['territory_name'], "</b>";
    break;
    case "message":
    if(!empty($move['sec_message']) && $move['local_id'] === $act_player || $game_data['game_status'] === "complete"){echo "<i>", say($move['sec_message']), "</i>";}
    else {echo say($move['message']);}
    break;
    case "play_card":
    echo say($player_data[$move['local_id']-1]['username']), " plays <b><span class='z3'>", $card_types[$move['m_param']-1]['card_name'], "</span></b>";
    break;
    case "place_actor":
    if($move['m_param'] == "player"){ echo "Players start at <b>", $Territories[$move['s_param']]['territory_name'], "</b>";}
    break;
}
echo "<br>";
}
}
*/
?>
</div>
</div>
</div>

<div class="roster_box" id="players" tabindex="-1">
<div class="game_info" tabindex="-1"><z5><b>Players</b></z5><br>
<?php
foreach($player_data as $player){
echo say($player['username']);
if($game_data['game_status'] !== "open"){
 echo " - <u>", $Territories[$player_data[$player['move_order']-1]['location']]['territory_name'], "</u> <i>(cards: ";
 if(!empty($player_data[$player['move_order']-1]['inventory']['cards'])){ echo array_sum($player_data[$player['move_order']-1]['inventory']['cards']); } else {echo "0";}
 echo ")</i>";
 if($game_data['current_player'] === $player['move_order'] && $game_data['game_status'] === "active"){ echo " - <span class='z3'><b>moving</b></span>";}
}
else {}
echo "<br>";
}
?>
</div>
</div>
<div class="roster_box" id="clues" tabindex="-1">
<div class="game_info" tabindex="-1"><z5><b>Clues</b></z5><br>
<div class="z3">
<?php
foreach($continent_data as $continent){
// Have you discovered this agent or not? That makes a difference here.
echo say($continent['agent_name']), " - <b>", say($continent['continent_name']), "</b>";
if($game_data['game_status'] !== "open" && $act_player != 0)
{   // A bit inefficient; oh well
    echo " - <i>";
    echo match ($player_data[$act_player-1]['inventory']['agents'][$continent['continent_id']])
    {
    -5, -4, -3, -2, -1 => "discovered",
    1 => "unknown", // you don't find out about the agent until the turn ends
    3 => "revealed",
    default => "unknown",
    };
    echo "</i>";
    }
echo "<br>";
}
// This will hold names and locations for agents, and reveal which ones have been discovered
// Program now?
?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php $res = "thehunt/play.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>
