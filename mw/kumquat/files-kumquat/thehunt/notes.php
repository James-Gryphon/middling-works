<?php

if(isset($_POST['start_game']) && isset($_SESSION['id']))
{
    // Leave out stuff to check if game exists, because that should be done earlier in play.php
    if($_SESSION['id'] == $player_data[0]['user_id'])
    {
        if($game_data['game_status'] != "open")
        {
            $errors['td']['game_not_open'] = true;
        }
        else
        {
            // Rewrite all this to take account of Territories/Continents arrays
            $territory_count = count($Territories);
            $player = random_int(1, $Territories);
            while(!isset($mastermind) || $mastermind == $player)
            {
            $mastermind = random_int(1, $territory_count);
            }
            $agents = array();
            foreach($Continents as $key => $value)
            {
                $Continent_Count = count($key);
                while(!isset($agent) || $agent == $mastermind || $agent == $player)
                {
                    $rand_territory = random_int(1, $Continent_Count);
                    $agent = $key[$rand_territory];
                }
                $agents[$key] = $agent;
            }
            var_see($player, "Player");
            var_see($mastermind, "Mastermind");
            var_see($agents, "Agents");
            // Now we should have all the locations set, so get ready to start the game.
            /*
            $sth = $pdo->prepare("UPDATE `thnt_games` SET game_status =:game_status WHERE `game_id` = :game_id");
            $sth->execute([
                ':user_id' => $user_id,
                ':ip' => $game_id,
            ]);
            */
        }
    }
    else 
    {
        $errors['td']['not_game_host'] = true;
    }
    // Make sure 
}
/*
For closing a game, leaving a game, or joining a game, the code should be the same as used in the lobby.

What happens when the game starts:
    Detect $_POST data for "game_start"
    Check the game ID, player ID, etc., and make sure that it is valid to start the game, and if so, that the player ID matches that of the host.
    If it is invalid, then toss appropriate error message - "game has already started" or, "you're not the host", etc.
    If it is valid, then:
        Pick random starting points. You need one for the mastermind, one for the players, and one for each secret agent. These will be "place_actor" moves from local_id 0. m_param controls the type of agent, s_param the territory ID. These should all be secret 1 moves.
        Prepare the SQL queries. There should be two sets. 
        Set 1 will update the game entry (thnt_games) status to "active", instead of "open".
        Set 2 will insert moves into the move_log, each move a "place_actor" command.
    At this point, the game should be started.

What happens when a move is detected:
    Detect $_POST data for "loc_chooser".
        Check the game ID, etc., and make sure the game is valid, and that the player has the right to move, that the player ID matches that of the current player, and that the two territories are connected.
        If invalid, toss approp. error message - 'game doesn't exist', 'you don't have the right to move', 'these territories aren't linked'
        If it is valid, then:
        Prepare a SQL query to update the move log. m_param is the territory being moved to, op_cost should be 1.

What happens when a card_player move is detected:
    Detect $_POST data for "card_player".
    Check the game ID, etc., and make sure the game is valid, and that the player has the right to move, and that the card is in that player's hand, and that the player ID matches that of the current player.
    If it is invalid, toss appropriate error message - "game doesn't exist", "you don't have the right to move", "you don't own that card"
    If it is valid, then:
        Call function that is associated with that card. For the "search" card, for instance, you will want to use the pathfinding logic, with the mastermind and current player's location as variables, and return the distance.
        Prepare an SQL query to update the move log.



Functions per card:
    For the "Search for" card, call the pathfinding logic, with mastermind and current player as variables.
    For "Capture", check if the territory that the current player is on is equal to the territory the Mastermind is on.
    For "Point to", adapt the pathfinding logic - it should return a territory one space away.
    For "Bug Another Agent", same as "Search for", but with random other player as a variable.
    For "Global Jump", pick a random territory on the continent that is deemed to be opposite of the current one - or calculate furthest territory away?



*/




?>