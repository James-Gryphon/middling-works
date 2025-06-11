<?php
require_once("stdio.php");

/*
Trigger system:
At the beginning of the game, add an empty "trigger" array to the column.

Certain cards will activate triggers. In the regular game, that includes "Close Borders", "Monolingual", and such like.  
These change the moves that players are allowed to make.
There are several types of restrictions:
1. By link type. This is the simplest type and corresponds directly to the restrictions in the original game. Whenever a move is normally possible, the link type is checked; if it matches, then the move is forbidden. "Close Roads" blocks road-type links (cars and motorcycles), "Close Airports" prevents jets.
2. By region. If the region is blocked, then nobody can move into this region from another region, and nobody can move out of it to another region.
3. By link type *AND* region. Combine these two.
4. By link. This specifically looks for a certain link and flips it - if it does exist, it is made unusable; if it doesn't, it is made real. This should probably be used selectively.

The norm for a trigger is that it ends after a full cycle (when the player who played it gets to move again), not counting "Take Another Turn".
However, we might imagine cases where it is desirable to have triggers that end at other times.


Layout of the trigger array:

trigger = array (this is what gets json_encoded)
(
	[0] => array
		(
			player => "1",
			link_styles => array("car", "motorcycle"), // "", or "no link style", must be specifically mentioned here
			to_regions => array("1"), // prevents travel to this region from another region
			from_regions => array("2"), // prevents travel from this region to another region
			in_regions => array("1, 2"), // applies link_styles to all travel inside this region - meaning, to any territory in the region, whether it goes to or out of the region
			link => array
			(
				[0] => array(1, 2), // 1-2 is prevented, and if it is a two-way link, so will 2-1 be so
				[1] => array(3, 4)
			)
		)
)

The trigger array is checked each turn, and at the end of turns, to determine when a trigger is expiring. The effects of a trigger should be noted at the beginning and end of each trigger, to avoid confusion. The trigger array is updated when cards that cause triggers are played, and when one expires.
*/

?>