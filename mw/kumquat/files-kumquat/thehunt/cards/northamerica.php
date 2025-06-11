<?php

/*
Card Index
Search for/Capture Mastermind - 16 cards
Point to Mastermind - 12 cards (28)
Close Borders - 4 cards (32)
Open Borders - 4 cards (36)
Local Lockdown - 4 cards (40)
Take Another Turn - 4 cards (44)
Charter Flight - 8 cards (52)
Bug Another Agent - 4 cards (56)
*/

$i = 0;
while($i < 16){
$Deck[] = clone $card_types['search'];
$i += 1;
}

$i = 0;
while($i < 12){
$Deck[] = clone $card_types['point'];
$i += 1;
}

$i = 0;
while($i < 4){
$Deck[] = clone $card_types['close_national_borders'];
$i += 1;
}

$i = 0;
while($i < 4){
$Deck[] = clone $card_types['open_borders'];
$i += 1;
}

$i = 0;
while($i < 4){
$Deck[] = clone $card_types['close_local_borders'];
$i += 1;
}

$i = 0;
while($i < 4){
$Deck[] = clone $card_types['take_another_turn'];
$i += 1;
}

$i = 0;
while($i < 8){
$Deck[] = clone $card_types['charter_flight'];
$i += 1;
}

$i = 0;
while($i < 4){
$Deck[] = clone $card_types['bug_agent'];
$i += 1;
}

?>