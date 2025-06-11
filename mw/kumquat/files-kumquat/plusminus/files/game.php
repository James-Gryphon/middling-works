<?php
/*
1. Fetch needful data: plus_minus games
2. Fetch player data for player who last moved, or show that there is none
3. If player who moved last isn't you, and there is a $_POST, and the $_POST matches the right number, then process your data, creating a local player if one doesn't already exist. Otherwise, don't do any of this.
4. If $_POST is accepted, and threshold is reached, then conclude the existing game and create a new game.
5. Display current game.
*/

// Will need this later
$ip = $_SERVER['REMOTE_ADDR'];
$errors = array();

// 1. Fetch needful data
$list = GetGameData();

// 2. Fetch player data for player who last moved?
$last_player = LastPlayerFetch($list);
$callsign = CallsignFetch($list, $last_player);

/* 3. If player who moved last isn't you, and there is a $_POST, and the $_POST matches the right number, 
then process your data, creating a local player if one doesn't already exist.
Otherwise, don't do any of this.
*/

// Is there a post, and is it valid?
$count = $list['count'];
$mincount = $count - 1;
$maxcount = $count + 1;
if(isset($_POST['number']) && ($_POST['number'] == $mincount || $_POST['number'] == $maxcount)){ $post = $_POST['number']; } 
elseif(!empty($_POST)) { $errors['plusminus']['invalid_number'] = true;}
if(isset($post) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code'] && empty($errors))
{
    // 3a. Is the player who moved last you?
    // If the session ID matches the last player's ID, or if the IP matches the last player's IP
    // I'm not sure I like the possibility that a registered user can use an IP and separate guests on the same machine cannot
    // thus revealing that someone else played there, but we have to draw the line for security somewhere.
    // However, we might let registered users commandeer IP addresses (in place of current guests)
        if(isset($last_player['user_id']) && (isset($_SESSION['id']) && $_SESSION['id'] === $last_player['user_id']) || (!empty($last_player) && $ip === $last_player['ip'])){
        // either your IP matches, or your user_id matches
        $errors['plusminus']['you_played_last'] = true;
        // You can't play if you played last.
        }
        else 
        {
        // 3b. The player who moved last isn't you. That being the case, check if someone with either your IP or user_ID exists.
        /*
        There are multiple scenarios here:
        1. Nobody, with either the IP or ID, exists. In this case, create a new mover.
        2. Someone with your IP exists, but not someone with your user_ID:
        a. If the someone with your IP is a guest, then commandeer the IP. 
        b. If they have a user_ID too, then you're out of luck and can't play.
        3. Someone with your user_ID exists, but not your user IP. In this case, the person should be you. Update the IP address to match your new one.
        4. Someone with both your user ID and IP exists; this is you.
        5. *Two* movers, one with your ID and another one with your IP, exist. This should behave the same as scenario 2b.
        */
            if(!empty($_SESSION['id'])){$user_id = $_SESSION['id'];} else {$user_id = NULL;}
            // A bit hacky, but fixes the user_id and ip overlap problem. Learned case statement from https://learnsql.com/blog/order-by-specific-value/
            $query = $pdo->prepare("SELECT * from plus_minus_movers WHERE (user_id = :user_id OR ip = :ip) ORDER BY CASE when user_id = :user_id then 1 else 2 END");
            $query->execute([
                ':user_id' => $user_id,
                ':ip' => $ip,
            ]);
            $player_search = $query->fetchAll(PDO::FETCH_ASSOC);

            if(empty($player_search))
            {
                // Scenario 1 - nobody exists, so you need to create a new mover
                $query = $pdo->prepare("INSERT INTO `plus_minus_movers` (`user_id`, `ip`) VALUES (:user_id, :ip)");
                $query->execute([
                    ':user_id' => $user_id,
                    ':ip' => $ip,
                ]);
                $local_id = $pdo->lastInsertId();
            }
            else 
            {
            if(!empty($player_search[1])){$ip_not_mine = 1;}
            $player_search = $player_search[0];
            $local_id = $player_search['local_id'];
            // Now, scenarios 2a, 2b, 3, or 4
                if($player_search['ip'] === $ip)
                {
                    // Either 2 or 4
                    if(!empty($player_search['user_id']))
                    {
                        if($player_search['user_id'] === $user_id)
                        {
                            // Scenario 4 - nothing necessary
                        }
                        else
                        {
                            // Scenario 2b; out of luck
                            $errors['plusminus']['ip_address_taken'] = true;
                        }
                    }
                    else // player_id is 0 or null
                    {
                        // Scenario 2a - commandeer IP
                        $query = $pdo->prepare("UPDATE `plus_minus_movers` SET user_id = :user_id WHERE ip=:ip");
                        $query->execute([
                            ':user_id' => $user_id,
                            ':ip' => $ip,
                        ]);
                    }
                } // end successful IP search
                else // Scen 3: IP is not yours, so the user ID must be (because of how the SQL query works). Update your IP address.
                // What if you have an account with a different IP, and there's another account with your IP with no user ID? Do you commandeer theirs?
                {
                    if(!isset($ip_not_mine))
                    {
                    $query = $pdo->prepare("UPDATE `plus_minus_movers` SET ip = :ip WHERE user_id=:user_id");
                    $query->execute([
                        ':user_id' => $user_id,
                        ':ip' => $ip,
                    ]);
                    }
                    // If the IP search isn't empty, then move on without changing your IP, the same as if it were dead-on
                }
            } // end 'somebody exists' path
        } // end last 'player isn't you' path

    // Section 4: Now it's time to process the data - if there are no errors
    if(empty($errors['plusminus']))
    {
        $new_score = $post - $count;
        $query = $pdo->prepare("UPDATE `plus_minus_games` SET count = :count, last_player_id = :last_player_id, last_plus_or_minus = :last_plus_or_minus WHERE game_id=:game_id");
        $query->execute([
            ':count' => $post,
            ':game_id' => $list['game_id'],
            ':last_player_id' => $local_id,
            ':last_plus_or_minus' => $new_score
        ]);

        $query = $pdo->prepare("SELECT * from plus_minus_scores WHERE local_id = :local_id AND game_id = :game_id");
        $query->execute([
            ':local_id' => $local_id,
            ':game_id' => $list['game_id']
        ]);
        $player_score = $query->fetch(PDO::FETCH_ASSOC);

        // Need something here to add a plus_minus_score entry if there isn't one
        if(empty($player_score))
        {
        $query = $pdo->prepare("INSERT INTO `plus_minus_scores` (`game_id`, `local_id`, `impact`) VALUES (:game_id, :local_id, :impact)");
        $query->execute([
            ':impact' => $new_score,
            ':game_id' => $list['game_id'],
            ':local_id' => $local_id
        ]);
        }
        else // If there already is a plus_minus_score entry
        {
        $new_score = $player_score['impact'] + $new_score;
        $query = $pdo->prepare("UPDATE `plus_minus_scores` SET impact = :impact WHERE game_id=:game_id AND local_id=:local_id");
        $query->execute([
            ':impact' => $new_score,
            ':game_id' => $list['game_id'],
            ':local_id' => $player_score['local_id']
        ]);
        }
        // Section 4 - finish and create new game
        if($post == $list['minusmin']){ $winner = 2; $fin = 1;}
        if($post == $list['plusmax']){ $winner = 1; $fin = 1;}
        if(isset($fin))
        {
            $query = $pdo->prepare("UPDATE `plus_minus_games` SET winner = :winner WHERE game_id=:game_id");
            $query->execute([
                ':winner' => $winner,
                ':game_id' => $list['game_id'],
            ]);
            /*
            game_id
            team_plus_name
            team_minus_name
            started
            ended
            winner
            count
            minusmin
            plusmax
            last_player_id
            */
            $tp_name = "Pluses";
            $tm_name = "Minuses";
            $minusmin = random_int(0, 899);
            $count = $minusmin + 50;
            $plusmax = $minusmin + 100;

            $query = $pdo->prepare("INSERT INTO `plus_minus_games` (`team_plus_name`, `team_minus_name`, `count`, `minusmin`, `plusmax`) VALUES (:team_plus_name, :team_minus_name, :count, :minusmin, :plusmax)");
            $query->execute([
                ':team_plus_name' => $tp_name,
                ':team_minus_name' => $tm_name,
                ':count' => $count,
                ':minusmin' => $minusmin,
                ':plusmax' => $plusmax,
            ]);
        

        }
    } // end sections 3 and 4
    if(empty($errors['plusminus'])){
    // update for new games or moves
    $list = GetGameData();
    $last_player = LastPlayerFetch($list);
    $callsign = CallsignFetch($list, $last_player);
    }
}

// Section 5: Display the state, and errors, if any
if(isset($_GET['gr']))
{
$roster_game_id = intval($_GET['gr']);
$query = $pdo->prepare("SELECT * from plus_minus_games WHERE game_id = :game_id");
$query->execute([
    ':game_id' => $roster_game_id,
]);
$roster_game = $query->fetch(PDO::FETCH_ASSOC);
}
if(empty($roster_game)){$roster_game_id = $list['game_id'];}

$query = $pdo->prepare("SELECT * from plus_minus_scores WHERE game_id = :game_id");
$query->execute([
    ':game_id' => $roster_game_id,
]);
$scores_for_game = $query->fetchAll(PDO::FETCH_ASSOC);

// functions
function GetGameData() {
global $pdo;
    $query = $pdo->query("SELECT * from plus_minus_games ORDER BY `game_id` DESC LIMIT 1");
    // make sure this is always the last game...
    $list = $query->fetch(PDO::FETCH_ASSOC);
    return $list;
    }

function LastPlayerFetch($list){
    global $pdo;
    if(!empty($list['last_player_id']))
    {
        $query = $pdo->prepare("SELECT * from plus_minus_movers WHERE local_id = :local_id");
        $query->execute([
            ':local_id' => $list['last_player_id']
        ]);
        $last_player = $query->fetch(PDO::FETCH_ASSOC);
        return $last_player;
}
}

function CallsignFetch($list, $last_player){
    global $pdo;    
    if(!empty($last_player)){    
            if(!empty($last_player['user_id']))
            {
                $query = $pdo->prepare("SELECT * from home_accounts WHERE id = :id");
                $query->execute([
                    ':id' => $last_player['user_id']
                ]);
                $account_data = $query->fetch(PDO::FETCH_ASSOC);
                $callsign = "<u>";
                $callsign .= say($account_data['username']);
                $callsign .= "</u>";
                // ought to include some formatting - copy the stuff from SM?
            }
            else 
            {
                $callsign = "<i>#{$last_player['local_id']}</i>";
            }
        
        }
        else 
        {
            //
            $callsign = "<i>No player has moved yet.</i>";
        }
        return $callsign;
        }  
?>