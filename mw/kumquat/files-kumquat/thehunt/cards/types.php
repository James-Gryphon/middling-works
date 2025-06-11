<?php

/*
Card Index
Search for/Capture Mastermind
Point to Mastermind
Close Borders (country borders, used in North America)
Open Borders (country borders)
Local Lockdown (regions)
End Lockdown (regions)
Take Another Turn
Charter Flight (used in North America)
Bug Another Agent
*/

$card_types = array(
"search" => new Card(
symbol: "🔎",
name: "Search for/Capture Mastermind",
short: "S/C",
desc: "You can either search for the Mastermind, or attempt to capture him. A search reveals your current distance from the Mastermind in steps. A capture attempt will win the game if the Mastermind is in the same territory as you."
    ),
"point" => new Card(
symbol: "☚",
name: "Point to Mastermind",
short: "PNT",
desc: "This card shows you an adjacent territory that is one step closer to the Mastermind."
    ),
"close_national_borders" => new Card(
symbol: "🚫",
name: "Close National Borders",
short: "CNB",
desc: "This card closes the borders between each of the countries, so that regular travel is prohibited."
    ),
    
"open_borders" => new Card(
symbol: "✓",
name: "Open Borders",
short: "OB",
desc: "This card permits travel between all territories again, cancelling the effects of both Close Borders cards."
    ),
    
"close_local_borders" => new Card(
symbol: "🔒",
name: "Close Local Borders",
short: "CLB",
desc: "This card prohibits travel into or out of the region the player is in from another region."
    ),
    
"take_another_turn" => new Card(
symbol: "🔁",
name: "Take Another Turn",
short: "TK",
desc: "This card allows the player who played it to take a second turn, immediately following the turn he played it in."
    ),
    
"charter_flight" => new Card(
symbol: "✈",
name: "Charter Flight",
short: "CF",
desc: "This card transports a player to a random region not adjacent to their own. "
    ),
    
"bug_agent" => new Card(
symbol: "✆",
name: "Bug Another Agent",
short: "BG",
desc: "This card transports a player to a random region not adjacent to their own. "
    )
);

?>