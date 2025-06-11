<a id="most_specific" class="skip">Content</a>
<?php
echo "<b>Session: </b>"; var_dump($_SESSION); echo "<br>";
echo "<b>Post: </b>"; var_dump($_POST); echo "<br>";
echo "<b>Get: </b>"; var_dump($_GET); echo "<br>";
echo "<br>";
echo "<hr>";
$errors = array();
if(empty($_SESSION['thnt_username'])){
if(isset($_POST['thnt_login'])){
if(!empty($_POST['thnt_username'])){ $prep_thnt_username = $_POST['thnt_username'];
if(mb_strlen($prep_thnt_username, "UTF-8") > 32){$username = ""; $errors['thnt_login']['user_length'] = true;}
} else {$errors['thnt_login']['empty_username'] = true;}
if(!empty($_POST['thnt_password'])){ $prep_thnt_password = $_POST['thnt_password'];
} else {$errors['thnt_login']['empty_password'] = true;}
if(empty($errors['thnt_login'])){
$query = $pdo->prepare("SELECT * FROM thnt_accounts WHERE username = ?");
$query->execute([$prep_thnt_username]);
$thnt_account = $query->fetch(PDO::FETCH_ASSOC);
if(!empty($thnt_account)){
	$auth = false;
		$password_ver = password_verify($password, $thnt_account['password']);
		if($password_ver === true) {$auth = true; } else { $errors['thnt_login']['failed_authentication'] = true;}
} else {
// Don't whine; create a new account!
$password_hash = $prep_thnt_password;
if(!empty($password_hash)){$password_hash = password_hash($password_hash, PASSWORD_DEFAULT); }
$query = $pdo->prepare("INSERT INTO `thnt_accounts` (`username`, `password`) VALUES (:username, :password)");
$query->execute(
	[
	':username' => $prep_thnt_username,
	':password' => $password_hash
	]
);
$auth = true; // if you're good enough to make a user, you're good enough to post as that user
}
}
}
if(isset($auth)){
$_SESSION['thnt_username'] = $_POST['thnt_username'];
}
}

if(isset($_SESSION['thnt_username'])){ $thnt_username = $_SESSION['thnt_username'];} else {$thnt_username = "";}
if(isset($_SESSION['id'])){$user_id = $_SESSION['id'];} else {$user_id = 0;}

if(!empty($thnt_username)){$acc_var = "<a href='index.php?s=trackdown&a=logout'>Logout</a>";} else
{
$acc_var = "<form method='POST' name='thnt_temp_login' action=''>
<input type='hidden' id='thnt_login' name='thnt_login'>
<label for='thnt_username'>Temporary Username</label>
<input type='text' id='thnt_username' name='thnt_username'>
<label for='thnt_password'>Temporary Password</label>
<input type='password' id='thnt_password' name='thnt_password'>
<input type='submit'>
</form>";
}
if($_GET['a'] == "lobby"):
$query = $pdo->query("SELECT * from thnt_boards");
$boards_listing = $query->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * from thnt_games WHERE game_status = 'open' AND private = 0; SELECT game_id from thnt_players WHERE ";
if(!empty($thnt_username)){$query_var = $thnt_username;} else {$query_var = $user_id;}
if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
$query .= " AND local_id > 1; ";
$query .= "SELECT game_id from thnt_players WHERE ";
if(!empty($thnt_username)){$query .= "username = :query_var";} else {$query .= "user_id = :query_var";}
$query .= " AND local_id = 1;";
$query = $pdo->prepare($query);

/* $query = $pdo->prepare("SELECT * from thnt_games WHERE game_status = 'open' AND private = 0;
SELECT game_id from thnt_players WHERE username = :thnt_username OR user_id = :user_id AND local_id > 1;
SELECT game_id from thnt_players WHERE (username = :thnt_username OR user_id = :user_id) AND local_id = 1
"); */
$query->execute([
":query_var" => $query_var
]);
$public_game_list = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$games_user_joined = $query->fetchAll(PDO::FETCH_COLUMN);
$query->nextRowset();
$games_user_hosting = $query->fetchAll(PDO::FETCH_COLUMN);
$games_user_joined = implode(",", $games_user_joined);
$games_user_hosting = implode(",", $games_user_hosting);
$games_user_in = $games_user_joined .= $games_user_hosting;
// we need active games we're in, games we're hosting that haven't started yet, games we've joined that haven't started yet, and finished games
if(!empty($games_user_in)){
// If this is completely empty, then there's no point in checking anything
if(!empty($games_user_joined)){
// idea to use IN provided by Google search results for 'match array' - eduCBA?
$query = $pdo->prepare("SELECT * from thnt_games WHERE game_id IN (:games_user_joined) AND game_status = 'open'");
$query->execute([
	":games_user_joined" => $games_user_joined
	]
);
$joined_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
}

if(!empty($games_user_hosting)){
$query = $pdo->prepare("SELECT * from thnt_games WHERE game_id IN (:games_user_hosting) AND game_status = 'open'");
$query->execute([":games_user_hosting" => $games_user_hosting]);
$hosted_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
}

$query = $pdo->prepare("SELECT * from thnt_games WHERE game_id IN (:games_user_in) AND game_status = 'active';
SELECT * from thnt_games WHERE game_id IN (:games_user_in) AND game_status = 'completed';
");
$query->execute([
":games_user_in" => $games_user_in
]);
$active_games = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$completed_games = $query->fetchAll(PDO::FETCH_ASSOC);
}

?>
<div class="main_box">
<div class="title_header"><h3>The Hunt</h3></div><br>
<div class="main_box_content">
<div class="mini_box">
<b>Create a Game</b><br>
<label for="game_name">Game Name</label><br>
<input type="text" id="game_name"><br>
<label for="player_count">Maximum Players</label><br>
<input type="number" id="player_count"><br>
<label for="board_name">Board</label><br>
<select id="board_name">
<option>Alphabet Soup</option>
<option>North America</option>
<option>Texas</option>
<option>The City</option>
</select><br>
<label for="private">Private?</label>
<input type="checkbox" id="private_game">
<br>
<input type="submit" value="Start Game"><br>
</div>
<hr>
<div class="mini_box">
<b>Join a Private Game</b><br>
<label for="join_game">Game Code</label><br>
<input type="text" id="game_code"><br>
<input type="submit" value="Join Game"><br>
</div>
<hr>
</div></div>

<div class="main_box">
<div class="title_header"><h3>Public Games</h3></div><br>
<div class="main_box_content lobby_box">
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
if(!empty($public_game_list)){
foreach($public_game_list as $public_game){
	$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
	$query->execute([
	$public_game['game_id']
	]);
	$public_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
	if(isset($public_game_players[0]['username'])){ $public_game_host = say($public_game_players[0]['username']);}
	else {$public_game_host = fetchPlayerName($public_game_players[0]['user_id']);}
	$player_count = count($public_game_players);
echo
"<tr><td>", say($public_game['game_name']), "</td><td>$public_game_host</td>", "<td>$player_count out of {$public_game['player_count']}</td><td>", $boards_listing[$public_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($public_game['created']), "</td></tr>";
}
}
?>

</tbody></table>
</div>
</div>

<div class="main_box">
<div class="title_header"><h3>Your Games</h3></div><br>
<div class="main_box_content your_games">
<b>Active Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($hosted_games_list)){
	foreach($hosted_games_list as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$hosted_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td>", say($active_game['game_name']), "</td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
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
if(!empty($active_games)){
	foreach($active_games as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td>", say($active_game['game_name']), "</td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
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
	foreach($joined_games_list as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td>", say($active_game['game_name']), "</td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>
</tbody></table><b>Your Completed Games</b><table>
<tbody><tr>
<th>Name</th>
<th>Host</th>
<th>Player Count</th>
<th>Board</th>
<th>Created</th>
</tr>
<?php
if(!empty($completed_games)){
	foreach($completed_games as $active_game){
		$query = $pdo->prepare("SELECT * from thnt_players WHERE game_id = ?");
		$query->execute([
		$active_game['game_id']
		]);
		$active_game_players = $query->fetchAll(PDO::FETCH_ASSOC);
		if(isset($active_game_players[0]['username'])){ $active_game_host = say($active_game_players[0]['username']);}
		else {$active_game_host = fetchPlayerName($active_game_players[0]['user_id']);}
		$active_player_count = count($active_game_players);
	echo
	"<tr><td>", say($active_game['game_name']), "</td><td>$active_game_host</td>", "<td>$active_player_count out of {$active_game['player_count']}</td><td>", $boards_listing[$active_game['board_id']-1]['board_name'], "</td><td>", qdateBuilder($active_game['created']), "</td></tr>";
	}
	}
?>
</tbody></table>
</div>
</div>

<?php

elseif($_GET['a'] == "play"):
if(isset($_GET['gm']) && is_numeric($_GET['gm'])){ $game_id = $_GET['gm'];}
// include something here for invalid IDs
$query = $pdo->prepare("SELECT * FROM thnt_games WHERE game_id = ?");
$query->execute([$game_id]);

$game_data = $query->fetch(PDO::FETCH_ASSOC);
	$query = $pdo->prepare(
	"SELECT * FROM thnt_boards WHERE board_id = :board_id;
	SELECT * FROM thnt_continents WHERE board_id = :board_id;
	SELECT * FROM thnt_territories WHERE board_id = :board_id;
	SELECT * FROM thnt_territory_links WHERE board_id = :board_id;
	SELECT * FROM thnt_players WHERE game_id = :game_id;
	SELECT * from thnt_move_log WHERE game_id = :game_id;
	SELECT * from thnt_card_types;
	");
	$query->execute(
	[
	":game_id" => $game_data['game_id'],
	":board_id" => $game_data['board_id']
	]
	);
	// Thanks to edmondscommerce: https://stackoverflow.com/questions/21485868/php-pdo-multiple-select-query-consistently-dropping-last-rowset
	$board_data = $query->fetch(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$continent_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$territory_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$territory_link_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$player_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$player_move_data = $query->fetchAll(PDO::FETCH_ASSOC);
	$query->nextRowset();
	$card_types = $query->fetchAll(PDO::FETCH_ASSOC);

	// Permission/login stuff
	if(!empty($thnt_username) || ($user_id > 0)){
		foreach($player_data as $player){
		if($thnt_username === $player['username'] || $user_id === $player['user_id']){ $act_player = $player['local_id']; break;}
		}}
		if(!isset($act_player)){$act_player = 0;}
	
		echo "Act Player: ", $act_player, "<br>";

	$Continents = array();
	$Territories = array();
	foreach($territory_data as $title => $territory_row){
	$Territories[$territory_row['territory_id']] = $territory_row;
	$Continents[$territory_row['continent_id']][] = $territory_row['territory_id'];
	}
	
	/* Build territory link table - for use in pathfinder */
	foreach($territory_link_data as $territory_link){
	$Territories[$territory_link['territory_one_id']]['links'][] = $territory_link['territory_two_id'];
	if($territory_link['one_way'] == 0){
	$Territories[$territory_link['territory_two_id']]['links'][] = $territory_link['territory_one_id'];
	}
	}

	// Stores information about mastermind, agents, and players
	$actors = array();
	// Keeps track of types of links that aren't available, according to closure cards
	$bad_links = array();

	// now to process the moves
	/* The structure of a move log entry:
	game_id: denotes what game this belongs to
	local_id: who made the move? 1 and up are players; 0 is reserved for automatic game functions.
	move_id: shows the chronological order the moves took place in, although 'acted' would probably suffice.
	acted: a timestamp for when the action was performed.
	secret: whether the move is visible to all players or not. 0 is yes, 1 is visible only to local id, 2 is always hidden. secrets are revealed at end of game. (thus, secret moves with local_id 0 are hidden until the end of the game.)
	op_cost: "turn cost" for an action -
	action: What type of action is going to be performed in this move. There are only a few options, including:
	1. Moving
	2. Playing a card
	3. Drawing a card
	4. Placing the mastermind/agent/player (reserved, of course, to local_id 0)
	5. Flip travel options (allowing or preventing future moves by players) - whether by continents, territories, or path types

	Card types:
	Search/Capture
	Point
	Take Another Turn
	Close Roads
	Close Airports
	Open Roads and Airports
	Place Trap* (not present, or used, in our personal copy)
	Bug Another Agent
	Global Jump
	*/

	/* The thing to remember about this section is that most of these actions are relatively inert; they don't change game state, but rather load it into memory. */
	foreach($player_move_data as $move){
	switch($move['action']){
	case "move":
	$actors[$move['local_id']]['location'] = $move['m_param'];
	break;
	case "draw_card":
	if(isset($actors[$move['local_id']]['cards'][$move['m_param']])){ $actors[$move['local_id']]['cards'][$move['m_param']] += 1; }
	else { $actors[$move['local_id']]['cards'][$move['m_param']] = 1;}
	break;
	case "play_card":
	// shouldn't need to actually do anything but remove a card - the power should be performed as part of another move
	if(isset($actors[$move['local_id']]['cards'][$move['m_param']])){
	$actors[$move['local_id']]['cards'][$move['m_param']] -= 1; 
	if ($actors[$move['local_id']]['cards'][$move['m_param']] === 0){unset($actors[$move['local_id']]['cards'][$move['m_param']]);}
	}
	break;
	case "close_route":
	// Needs to be able to close routes to/from: 1) continents, 2) certain types of links, and 3) individual territories.
	// m_param is type, s_param is the ID or type of thing being closed. It should only apply within a range of moves.
	$bad_links[] = array($move['move_id'], $move['m_param'], $move['s_param']);
	break;
	case "place_actor":
		if($move['m_param'] != "player"){
		$actors[$move['m_param']]['location'] = $move['s_param'];
		break;
		}
		else {
		$i = count($player_data);
		$start_point = $move['s_param'];
		while($i >= 0){
		$actors[$i]['location'] = $move['s_param'];
		// do player location assignment here
		$i -= 1;
		}
		}
	}

	}

    ?>
	<div class="main_box full_width">
	<div class="title_header"><h3>Trackdown</h3></div><br>
	<div class="main_box_content">
    <div class="game_box">
	<div class="left_wing">
    <div class="map_box"><img src="demo_map.png" alt="demo"></div>
    <div class="info_box">
	<span class='z6'><b><?=$game_data['game_name']?></b></span><br>
	<?php if(isset($act_player)): ?>
    <span class='z3'>Game Code: <?=$game_data['access_code']?> <?php if($game_data['private'] == 1){ echo "<i>(private game)</i>"; }?></span><br>
	<?php endif; ?>
    <span class='z3'>Created: <?=qdateBuilder($game_data['created'])?></span><br>
    <span class='z3'>Last Activity: <?=qdateBuilder($game_data['updated'])?></span><br>
    <span class='z3'>Board: <?=$board_data['board_name']?></span><br>
    <span class='z3'>Status: <?=$game_data['game_status']?></span>
    </div>
	</div>

	<div class="right_wing">
	<div class="location_blurb">
	<div class="region_box">
	<?php
	echo "<span class='z2'><b>";
	echo $continent_data[$territory_data[$actors[$act_player]['location']]['continent_id']-1]['continent_name'];
	echo "</b></span><br><span class='z3'>";
	echo $continent_data[$territory_data[$actors[$act_player]['location']]['continent_id']-1]['continent_blurb'];
	echo "</span>";
	?>
	</div>
	<div class="territory_box">
	<?php
	echo "<b>";
	echo $territory_data[$actors[$act_player]['location']-1]['territory_name'];
	echo "</b><br>";
	echo $territory_data[$actors[$act_player]['location']-1]['territory_blurb'];
	?>
	</div>
	</div>

    <div class="orders_box">
	<div class="card_box">
	<form name="card_player" method="POST">
	<select name="card_chooser" id="card_chooser" multiple="">
	<?php
	if(!empty($actors[$act_player]['cards'])){
	foreach($actors[$act_player]['cards'] as $card_type => $card_count){
	echo "<option value='{$card_type}'>{$card_types[$card_type-1]['card_name']} ({$card_count})</option>";
	if($card_type === 1){
	// "Search for Mastermind" and "Capture Mastermind" are a combo card
	echo "<option value='{$card_type}'>{$card_types[$card_type]['card_name']} ({$card_count})</option>";
	}
	}
	}
	else {echo "<option value='none' disabled>No cards in hand</option>";}
	?>
    </select><br><input type="submit" value="Play Card"></form></div><div class="move_box">
    <form name="loc_chooser" method="GET">
    <select name="loc_chooser" id="loc_chooser" multiple="">
    <?php
    // insert foreach option here
	foreach($Territories[$actors[$act_player]['location']]['links'] as $id => $link){
	echo "<option value='$link'>{$Territories[$link]['territory_name']}</option>";
	}
    ?>
    </select><br><input type="submit" value="Move"></form></div>
	<div class="pass_box"><label for="pass_turn">Pass Turn?</label><input type="checkbox" name="pass_turn" id="pass_turn"><input type="submit" value="Pass"></div>
    </div>
    <div class="log_box">
	<div class="move_info"><z5><b>Move Log</b></z5><br>
	<div class="move_log">
<?php
foreach($player_move_data as $move){
	// If not secret, or if local id is you, or if game is over, show action
	if(
		$move['secret'] == 0 ||
		((($move['local_id'] == $act_player && $act_player != 0) || $game_data['game_status'] == "complete") && $move['secret'] == 1)
		){
	switch($move['action']){
		case "move":
		echo fetchPlayerName($move['local_id']), " moves to <b>", $Territories[$actors[$move['local_id']]['location']]['territory_name'], "</b>";
		break;
		case "message":
		if(!empty($move['sec_message']) && $move['local_id'] === $act_player || $game_data['game_status'] === "complete"){echo "<i>", say($move['sec_message']), "</i>";}
		else {echo say($move['message']);}
		break;
		case "play_card":
		echo fetchPlayerName($move['local_id']), " plays <b><span class='z3'>", $card_types[$move['m_param']-1]['card_name'], "</span></b>";
		break;
		case "place_actor":
		if($move['m_param'] == "player"){ echo "Players start at <b>", $Territories[$move['s_param']]['territory_name'], "</b>";}
		break;
	}
echo "<br>";
}
}
?>
</div>
</div>
</div>

<div class="roster_box">
<div class="game_info"><z5><b>Agents</b></z5><br>
<?php
foreach($player_data as $player){
if(!empty($player['username'])){say($player['username']);}
else {echo "<u>"; echo say(get_username($player['user_id'])); echo "</u>";}
echo "{$player['username']} - ", $Territories[$actors[$player['local_id']]['location']]['territory_name'], " <i>(cards: ";
if(!empty($actors[$player['local_id']]['cards'])){ echo count($actors[$player['local_id']]['cards']); } else {echo "0";}
echo ")</i><br>";
}
?>
<br>
</div>
</div>
</div>
</div>

<footer class="thehunt">
<?=$acc_var?>
</footer>
<?php
endif;
	// going to need to check on the div closures and line break up above.
function fetchPlayerName($local_id){
global $player_data;
$adj_id = $local_id - 1;
if(!empty($player_data[$adj_id]['username'])){ $username = say($player_data[$adj_id]['username']);}
else {$username = "<i>"; $username .= say(get_username($player_data[$adj_id]['user_id'])); $username .= "</i>";}
return $username;
}

/* 
	Card types:
	Search/Capture
	Point
	Take Another Turn
	Close Roads
	Close Airports
	Open Roads and Airports
	Place Trap* (not present, or used, in our personal copy)
	Bug Another Agent
	Global Jump
*/
?>
