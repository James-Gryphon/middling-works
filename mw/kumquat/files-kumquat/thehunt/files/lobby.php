<?php

// Select board listing so that we can use the names, etc., later
if(isset($_GET['gm']) && is_numeric($_GET['gm'])){$game_id = $_GET['gm'];}
$query = $pdo->query("SELECT * from thnt_boards WHERE active = 1");
$board_listing = $query->fetchAll(PDO::FETCH_ASSOC);
$boards_listing = array();
// Using the fetch by itself wouldn't work because it's reading the PHP array ID, while it needs to read the *real* board ID.
// See if I can come up with a better solution later.
foreach($board_listing as $key => $value)
{
    $boards_listing[$value['board_id']] = $value;
}
unset($board_listing);

// First, get some of the games
// idea to use IN provided by Google search results for 'match array' - eduCBA?
$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id) AND game_status = 'active'");
$active_games = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id AND local_id > 1) AND game_status = 'open'");
$joined_games_list = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id AND local_id = 1) AND game_status = 'open'");
$hosted_games_list = $query->fetchAll(PDO::FETCH_ASSOC);

$player_game_count = count($active_games) + count($hosted_games_list) + count($joined_games_list);
        
if(isset($_GET['leave']) && $user_id > 0 && isset($game_id))
{
    $leave = leave_handler();
    if($leave === "host")
    { // refetch - not very efficient, but we need an update
        $query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id AND local_id = 1) AND game_status = 'open'");
        $hosted_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
        $player_game_count -= 1;
    }
    elseif($leave === "guest")
    {
        $query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id AND local_id > 1) AND game_status = 'open'");
        $joined_games_list = $query->fetchAll(PDO::FETCH_ASSOC);
        $player_game_count -= 1;
    }
}

$max_game_limit = 7;

$player_game_limit = match($member_auth_level)
{
    1 => $max_game_limit,
    0 => 2,
    -1 => 0,
    default => 0,    
};

if($player_game_count >= $player_game_limit)
{ 
    $nonewgames = true;
} 
else {$nonewgames = false;}
if($member_auth_level === -1){$unregistered = true;}

if($nonewgames === false && (isset($_GET['join']) || isset($_POST['join']))){ $join = true;}
if(isset($join) && $user_id > 0 && isset($_GET['gm']))
{
    $join = join_handler();
    if($join === 1){ exit(header("Location: index.php?s=thehunt&a=play&gm=$game_id"));}
}

    if(isset($_POST['create_game']) && $user_id > 0 && $nonewgames === false)
{
    /* Creating a game */
    // Thanks to atrandafirc at yahoo dot com from the manual for this key tip, especially on how to use %s
    $create_game_name = $_SESSION['username'];
    $create_game_name .= "'s ";
    $create_game_name = "%$create_game_name ";

    $adjectives =
    [
    "Cool","Neat","Fine","Okay","Good","Rosy","Apt","Fair","Fancy","Great","Crazy","Zesty","Nifty","Bright",
    "Peachy","Lovely","Stellar","Superb","Grand","Decent","Choice","Capital","Special","Famous","Lucid",
    "Clear","Lively","Merry","Cheery","Upbeat","Jovial","Acute","Sound","Vivid","Vibrant","Sharp",
    "Smart","Clever","Notable"
    ];
    $adjective = array_rand($adjectives);
    $create_game_name .= "{$adjectives[$adjective]} ";

    $nouns =
    [
        "Adventure", "Expedition", "Voyage", "Tour", "Outing", "Excursion", "Game", "Journey", "Quest", "Event", "Race"
    ];
    $noun = array_rand($nouns);
    $create_game_name .= $nouns[$noun];
    $create_game_name .= "%";

    $sth = $pdo->prepare("SELECT game_name FROM `thnt_games` WHERE game_name LIKE :game_name ORDER BY `created` DESC LIMIT 1;");
    $sth->execute(
                [
                    ":game_name" => $create_game_name
                ]
                );

    $last_game_name = $sth->fetch(PDO::FETCH_ASSOC);

    if(!empty($last_game_name))
    {
        $name_array = explode("#", $last_game_name['game_name']);
        $number = array_key_last($name_array);
        $number += 1;
    }
    else {$number = 1;}
    $create_game_name = trim($create_game_name, "%");
    $create_game_name .= " #{$number}";
    if(isset($_POST['maximum_players']) && $_POST['maximum_players'] >= 1 && $_POST['maximum_players'] <= 4){ $create_game_players = $_POST['maximum_players']; } else {$create_game_players = 4;}
    if(isset($_POST['private_game']) || $create_game_players === 1){$create_private_game = 1; } else {$create_private_game = 0;}
    if(isset($_POST['board_name']) && isset($boards_listing[$_POST['board_name']])){$create_board_id = $_POST['board_name'];} else {$create_board_id = 1;}
    $current_code = "";
    $access_code = "";
    while($current_code == $access_code)
    {
        $access_code = bin2hex(random_bytes(32));
        $sth = $pdo->prepare("SELECT access_code FROM `thnt_games` WHERE access_code = :access_code;");
        $sth->execute(
                    [
                        ":access_code" => $access_code
                    ]
                    );
        $current_code = $sth->fetch(PDO::FETCH_ASSOC);  
    }

    $query = $pdo->prepare("INSERT INTO `thnt_games` (`game_name`, `board_id`, `player_count`, `private`, `access_code`, `game_status`) VALUES (:game_name, :board_id, :player_count, :private, :access_code, :game_status)");
    $query->execute([
    ":game_name" => $create_game_name,
    ":board_id" => $create_board_id,
    ":player_count" => $create_game_players,
    ":private" => $create_private_game,
    ":access_code" => $access_code,
    ":game_status" => "open"
    ]);
    $new_game_id = $pdo->lastInsertId();
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = $pdo->prepare("INSERT INTO `thnt_players` (`game_id`, `user_id`, `ip`, `local_id`, `move_order`) VALUES (:game_id, :user_id, :ip, 1, 0)");
    $query->execute([
    ":game_id" => $new_game_id,
    ":user_id" => $user_id,
    ":ip" => $ip
    ]);

    exit(header("Location: index.php?s=thehunt&a=play&gm=$new_game_id"));
}

// Put these at the end, where they won't bother anything
$query = $pdo->query("SELECT * from thnt_games WHERE game_id IN (SELECT game_id from thnt_players WHERE user_id = $user_id) AND game_status = 'completed' ORDER BY updated DESC LIMIT 7");
$completed_games = $query->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * from thnt_games WHERE game_status = 'open' AND private = 0";
$query = $pdo->query($query);
$public_game_list = $query->fetchAll(PDO::FETCH_ASSOC);

// require("".path."/thehunt/temps/lobby_tpl.php");
?>
