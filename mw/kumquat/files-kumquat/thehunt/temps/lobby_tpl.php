<?php $res = "thehunt/lobby.css"; ?>
<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="first_box_checker" class="title_header"><h2>The Hunt</h2></label>
<input type="radio" id="second_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="second_box_checker" class="title_header"><h2>Public Games</h2></label>
<input type="radio" id="third_box_checker" name="cat_buttons" class="hidden box_checker" checked>
<label for="third_box_checker" class="title_header""><h2>Your Games (<?=$player_game_count?> out of <?=$player_game_limit?>)</h3></label>
<br><br>
<div class="main_box_content cbox first">
<div class="mini_box">
<b>Create a Game</b><br>
<?php 
if($nonewgames === true):
?>
    <span class="z3"><i>You can't create new games because you're
    <?php if($unregistered === true): ?> not logged into an account. <a href='index.php?a=register&z'>Create an account</a> or <a href='index.php?a=login&z'>log in</a> to use this feature.</i></span><br>
    <?php else:?>already in <?=$player_game_count?> active games.</span><br>
    <?php
    if($member_auth_level <= 0 && $player_game_count < $max_game_limit):?>
    <span class="z3">Finish a game, or become a  <a href='index.php?s=ult&a=whysubscribe'>subscribing member</a>, to start a new one.</i></span><br>
    <?php else:?>
    <span class="z3">Finish some of those, and then you'll be able to start a new one!</i></span><br>
    <?php
endif;
endif;
endif;
?>
<form name="create_game" method="post" action='index.php?s=thehunt&a=lobby'>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<input type="hidden" id="create_game" name="create_game" value="1">
<label for="player_count">Maximum Players</label><br>
<input type="number" id="player_count" name="player_count" value="4" size="2" max="4" <?php if ($nonewgames === true){echo 'disabled';}?>><br>
<label for="board_name">Board</label><br>
<select id="board_name" name="board_name" <?php if ($nonewgames === true){echo 'disabled';}?>>
<?php
foreach($boards_listing as $board)
{
echo "<option value='{$board['board_id']}'>{$board['board_name']}</option>";
}
?>
</select><br>
<label for="private">Private?</label>
<input type="checkbox" id="private_game" name="private_game" checked <?php if ($nonewgames === true){echo 'disabled';}?>>
<br>
<input type="submit" value="Start Game" <?php if ($nonewgames === true){echo 'disabled';}?>><br>
</form>
</div>
<hr>
<div class="mini_box">
<form name="view_game" method="GET" action="">
<input type="hidden" name="s" value="thehunt">
<input type="hidden" name="a" value="play">
<b>View a Private Game</b><br>
<label for="game_code">Game Code</label><br>
<input type="text" name="game_code" size="17"><br>
<input type="submit" value="View Game"><br>
</form>
<div class="mini_box">
    <?php
    if($member_auth_level === 1)
    {
        echo "<span class='z3'>Thanks for your support!</span><br>";
    }
    ?>
    <a class='z3' href='index.php?s=thehunt&a=whysubscribe'>About subscribing</a><br>
    <a class='z3' href='index.php?a=subscribe'>Subscription Manager</a>
</div>
</div>
<hr>
</div>

<div class="main_box_content lobby_box lbox second">
<table>
<thead>
<tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($public_game_list))
{
foreach($public_game_list as $public_game)
{
    $query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
    $query->execute([
    $public_game['game_id']
    ]);
    $public_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
    $public_game_host = get_username($public_game_players[0]['user_id']);
    $player_count = count($public_game_players);
    echo
    "<tr><td><a href='index.php?s=thehunt&a=play&gm={$public_game['game_id']}'>", say($public_game['game_name']), "</a>";
    $in_game = false;
    foreach($public_game_players as $player)
    {
        if($user_id == $player['user_id']){ $in_game = true;}
    }
    if($in_game === false && $user_id != 0 && $nonewgames === false){ echo " <a href='index.php?s=thehunt&a=lobby&gm={$public_game['game_id']}&join'>(join)</a>";}
    echo "</td><td>{$public_game_host[1]}</td>", "<td>$player_count out of {$public_game['player_count']}</td><td>", $boards_listing[$public_game['board_id']]['board_name'], "</td><td>", qdateBuilder($public_game['created'], true), "</td></tr>";
}
}
else 
{
     echo "<tr><td colspan='5' class='center_text'><i>There are no open public games at present.</i></td></tr>";
}
?>

</tbody></table>
</div>

<div class="main_box_content your_games lbox third">
<b>Active Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($active_games)){
    foreach($active_games as $active_game){
        $query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
        $query->execute([
        $active_game['game_id']
        ]);
        $active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
       $active_game_host = get_username($active_game_players[0]['user_id']);
        $active_player_count = count($active_game_players);
    echo
    "<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a></td><td>{$active_game_host[1]}</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't playing any games.</i></td></tr>";}
?>	
</tbody></table><b>Hosted Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($hosted_games_list)){
    foreach($hosted_games_list as $active_game)
    {
        $query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
        $query->execute([
        $active_game['game_id']
        ]);
        $active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
        $active_game_host = get_username($active_game_players[0]['user_id']);
        $active_player_count = count($active_game_players);
    echo
    "<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a> <a href='index.php?s=thehunt&a=lobby&gm={$active_game['game_id']}&leave'>(stop)</a></td><td>{$active_game_host[1]}</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't hosting any games.</i></td></tr>";}
?>
</tbody></table><b>Joined Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($joined_games_list)){
    foreach($joined_games_list as $active_game)
    {
        $query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
        $query->execute([
        $active_game['game_id']
        ]);
        $active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
        $active_game_host = get_username($active_game_players[0]['user_id']);
        $active_player_count = count($active_game_players);
    echo
    "<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), " <a href='index.php?s=thehunt&a=lobby&gm={$active_game['game_id']}&leave'>(leave)</a></a></td><td>{$active_game_host[1]}</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You aren't joined to anyone else's game.</i></td></tr>";}
?>
</tbody></table><b>Your Recent Completed Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($completed_games)){
    foreach($completed_games as $active_game)
    {
        $query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
        $query->execute([
        $active_game['game_id']
        ]);
        $active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
        $active_game_host = get_username($active_game_players[0]['user_id']);
        $active_player_count = count($active_game_players);
    echo
    "<tr><td><a href='index.php?s=thehunt&a=play&gm={$active_game['game_id']}'>", say($active_game['game_name']), "</a></td><td>{$active_game_host[1]}</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='5' class='center_text'><i>You haven't completed any games recently.</i></td></tr>";}
?>
</tbody></table>
</div>
<?php $res = "thehunt/lobby.js"; ?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>
