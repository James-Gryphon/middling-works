<?php
// old move processor - defunct? saved just to be safe

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
$pres_mover = 1;
$last_mover = count($player_data);
$r = 1;
$o = 0;
foreach($player_move_data as $move)
{
	if($o >= 2)
	{
		/*
		The problem:
		We need to check every time if there is a resigned player or not.
		We also need to check if we're about to go over the last_mover
		If every player has resigned, this will cause an infinite loop. Make absolutely sure that this is not possible.
		*/
		$q = $last_mover;
		while($q > 0)
		{
			$pres_mover += 1;
			if($pres_mover > $last_mover)
			{
				$pres_mover = 1;
				$r += 1;
			}
			if(empty($actors['players'][$pres_mover]['resign']) && $pres_mover <= $last_mover)
			{
				// We should be good to proceed.
				break;
			}
			$q -= 1;
		}
		if($q === 0){ echo "Infinite resignation loops shouldn't be possible."; die;}
		$o = 0;
	}
	switch($move['action'])
	{
		case "move":
			$actors['players'][$move['local_id']]['location'] = $move['m_param'];
		break;

		case "draw_card":
			if(isset($actors['players'][$move['local_id']]['cards'][$move['m_param']]))
			{ 
				$actors['players'][$move['local_id']]['cards'][$move['m_param']] += 1; 
			}
			else 
			{ 
				$actors['players'][$move['local_id']]['cards'][$move['m_param']] = 1;
			}
		break;

		case "play_card":
		// shouldn't need to actually do anything but remove a card - the power should be performed as part of another move
		if(isset($actors['players'][$move['local_id']]['cards'][$move['m_param']]))
		{
			$actors['players'][$move['local_id']]['cards'][$move['m_param']] -= 1; 
			if ($actors['players'][$move['local_id']]['cards'][$move['m_param']] === 0)
			{
				unset($actors['players'][$move['local_id']]['cards'][$move['m_param']]);
			}
		}
		break;

		case "close_route":
		// Needs to be able to close routes to/from: 1) continents, 2) certain types of links, and 3) individual territories.
		// m_param is type, s_param is the ID or type of thing being closed. It should only apply within a range of moves.
		// Right now, this is not implemented
		$bad_links[] = array($move['move_id'], $move['m_param'], $move['s_param']);
		break;

		case "place_actor":
			if($move['m_param'] == "mastermind")
			{
				$actors[$move['m_param']]['location'] = $move['s_param'];
				break;
			}
			elseif($move['m_param'] == "player")
			{
				$i = 1;
				while($i <= $last_mover)
				{
					$actors['players'][$i]['location'] = $move['s_param'];
					// do player location assignment here
					$i += 1;
				}
			}
			else 
			{
				$actors[$move['m_param']]['location'] = $move['s_param'];
			}		
	}
	$o += $move['op_cost'];
}
?>