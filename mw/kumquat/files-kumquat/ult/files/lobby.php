<?php

$errors = [];
if(isset($_GET['match']) && is_numeric($_GET['match'])){ $match_id = $_GET['match'];}
if(isset($_GET['invite']) && is_numeric($_GET['invite'])){ $invite_id = $_GET['invite'];}

if(isset($_GET['leave']) && $user_id > 0)
{
    if(isset($match_id)){ leave_handler();}
    elseif(isset($invite_id)){ cancel_handler();}
}

// First, get some of the games
// idea to use IN provided by Google search results for 'match array' - eduCBA?
$query = $pdo->query("SELECT * from ult_matches WHERE match_id IN (SELECT match_id from ult_matches WHERE host = $user_id OR guest = $user_id) AND status = 'active'");
$active_matches = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from ult_matches WHERE match_id IN (SELECT match_id from ult_matches WHERE guest = $user_id) AND status = 'open'");
$joined_matches_list = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from ult_matches WHERE match_id IN (SELECT match_id from ult_matches WHERE host = $user_id) AND status = 'open'");
$hosted_matches_list = $query->fetchAll(PDO::FETCH_ASSOC);

// Invites
$query = $pdo->query("SELECT * from ult_invites WHERE host = $user_id ORDER BY updated DESC");
$invites_from_you = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("SELECT * from ult_invites WHERE guest = $user_id ORDER BY updated DESC");
$invites_to_you = $query->fetchAll(PDO::FETCH_ASSOC);

$player_invite_count = count($invites_from_you);
$player_match_count = count($active_matches) + count($hosted_matches_list) + count($joined_matches_list) + $player_invite_count;

$max_match_limit = 12;
if($member_auth_level === 1)
{
    $player_match_limit = $max_match_limit;
    $match_length_limit = 40;
} 
elseif($member_auth_level === 0)
{
    $player_match_limit = 3;
    $match_length_limit = 12;
}
else 
{
    $player_match_limit = 0;
    $match_length_limit = 0;
    $unregistered = true;
}
if($player_match_count >= $player_match_limit){ $nonewmatches = true;} else {$nonewmatches = false;}

if($nonewmatches === false && (isset($_GET['join']) || isset($_POST['join']))){ $join = true;}
if(isset($join) && $user_id > 0 && isset($_GET['match']))
{
    $join = join_handler();
    if($join === 1){ exit(header("Location: index.php?s=ult&a=play&match=$match_id"));}
}

if($nonewmatches === false && isset($_GET['accept']) && $user_id > 0 && isset($_GET['invite']))
{
    $invite = invite_handler();
    // There's no way this should come up since both the invite and game handler have exits, but...
    if($invite === 1){ exit(header("Location: index.php?s=ult&a=play&match=$match_id"));}
}

$match_type = "ult_matches";
$invite_fail = false;
if(!empty($_POST['invite_name']))
{
    $invite_id = NULL;
    $match_type = "ult_invites";
    $invite_id = get_userid($_POST['invite_name']);
    if(empty($invite_id))
{
    $errors['invite_fail'] = true;
}
}
    if(isset($_POST['create_match']) && $user_id > 0 && $nonewmatches === false && !isset($errors['invite_fail']))
{
    /* Creating a game */
    // Thanks to atrandafirc at yahoo dot com from the manual for this key tip, especially on how to use %s
    $create_match_name = $_SESSION['username'];
    $create_match_name .= "'s ";
    $create_match_name = "%$create_match_name ";

//    $adjectives =
//    [
//    "Memorable", "Epic", "Ultimate", "Climatic", "", "Sound", "Fair", "Clear", "Sharp", "Notable", "Classic"
//    ];
//    $adjective = array_rand($adjectives);
//    $create_match_name .= "{$adjectives[$adjective]} ";

    if(isset($_POST['match_length']) && is_numeric($_POST['match_length']) && $_POST['match_length'] <= 40)
    {
	$match_length = $_POST['match_length'];
    }
    else {$match_length = 2;}
    switch($match_length)
    {
	case 1:
	$create_match_name .= "Game";
	break;
	case 2: case 3:
	$create_match_name .= "Duel";
	break;
	case 4: case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
	$create_match_name .= "Match";
	break;
	default:
	$create_match_name .= "Series";
	break;
    }
    $create_match_name .= "%";

    $sth = $pdo->prepare("SELECT match_name FROM `ult_matches` WHERE match_name LIKE :match_name ORDER BY `created` DESC LIMIT 1;");
    $sth->execute(
                [
                    ":match_name" => $create_match_name
                ]
                );

    $last_match_name = $sth->fetch(PDO::FETCH_ASSOC);

    if(!empty($last_match_name))
    {
        $name_array = explode("#", $last_match_name['match_name']);
        $number = array_key_last($name_array);
        $number += 1;
    }
    else {$number = 1;}
    $create_match_name = trim($create_match_name, "%");
    $create_match_name .= " #{$number}";
    if(isset($_POST['private_match'])){$create_private_match = 1; } else {$create_private_match = 0;}
    if(isset($_POST['rules']) && isset($rules_listing[$_POST['rules']])){$rules = $_POST['rules'];} else {$rule_mode = "ult";}
    $current_code = "";
    $access_code = "";
    while($current_code == $access_code)
    {
        $access_code = base64_encode(random_bytes(16));
        $sth = $pdo->prepare("SELECT access_code FROM `ult_matches` WHERE access_code = :access_code;");
        $sth->execute(
                    [
                        ":access_code" => $access_code
                    ]
                    );
        $current_code = $sth->fetch(PDO::FETCH_ASSOC);  
    }
    
    if($member_auth_level >= 1)
    {
        if(isset($_POST['mover']) && $create_private_match == 1 && $_POST['mover'] == "host")
        {
            $mover = "host";
        }
        elseif(isset($_POST['mover']) && $create_private_match == 1 && $_POST['mover'] == "random")
        {
            $mover = "random";
        }
        elseif(isset($_POST['mover']) && $create_private_match == 1 && $_POST['mover'] == "guest")
        {
            $mover = "guest";
        }
        else 
        {
            $mover = "standard";
        }
    }
    else 
    {
        $mover = "standard";
    }
    if(isset($_POST['ot']))
    {
        $ot = true;
    } 
    else 
    {
        $ot = false;
    }
    $rules = "ult";
    $clock_listing = array("none" => 0, "standard" => 1, "short" => 2);
    if(isset($_POST['clock']) && isset($clock_listing[$_POST['clock']]) && $member_auth_level >= 1)
    {
        if($_POST['clock'] == "standard"){$clock = "30min";}
        elseif($_POST['clock'] == "short"){$clock = "15min";}
        else{$clock = "none";}
    }
        else {$clock = "none";}
    $query = $pdo->prepare("
    INSERT INTO `$match_type` 
    (`match_name`, `host`, `guest`, `private`, `access_code`, `match_length`, `status`, `mover`, `ot`, `clock`, `rules`) 
    VALUES (:match_name, :host, :guest, :private, :access_code, :match_length, :status, :mover, :ot, :clock, :rules)");
    $query->execute([
    ":match_name" => $create_match_name,
    ":host" => $user_id,
    ":guest" => $invite_id,
    ":private" => $create_private_match,
    ":access_code" => $access_code,
    ":match_length" => $match_length,
    ":status" => "open",
    ":mover" => $mover,
    ":ot" => $ot,
    ":clock" => $clock,
    ":rules" => $rules,
    
    ]);
    $new_match_id = $pdo->lastInsertId();

    $new_games_array = [];
    $new_games_array['mover'] = $mover;
    $new_games_array['match_length'] = $match_length;
    $new_games_array['match_id'] = $new_match_id;

    if($match_type === "ult_matches")
    { // We don't build games for invites when the invite is created, but after it is accepted
        game_builder($new_games_array); // game_builder includes an exit, at least for now
    }
    // This would work in the case of invites - in the case of matches, it's already been exited
    exit(header("Location: index.php?s=ult&a=lobby"));
}

// Put these at the end, where they won't bother anything
// Completed matches
$query = $pdo->query("SELECT * from ult_matches WHERE match_id IN (SELECT match_id from ult_matches WHERE host = $user_id OR guest = $user_id) AND status = 'completed' ORDER BY updated DESC LIMIT 7");
$completed_matches = $query->fetchAll(PDO::FETCH_ASSOC);

// Public matches
$query = "SELECT * from ult_matches WHERE status = 'open' AND private = 0";
$query = $pdo->query($query);
$public_match_list = $query->fetchAll(PDO::FETCH_ASSOC);

// require("".path."/ult/temps/lobby_tpl.php");
?>
