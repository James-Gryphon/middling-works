<?php

$Suits = array("Hearts", "Spades", "Clubs", "Diamonds");
$Values = array(1,2,3,4,5,6,7,8,9,10,11,12,13);
$Names = array("Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Jack", "Queen", "King", "Ace");
$Shorts = array("2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A");

$Deck_Stack = array();
foreach($Suits as $Suit){
$i = 0;
while ($i < 13){
$Deck_Stack[] = new Card($Suit, $Values[$i], $Names[$i], $Shorts[$i]);
$i += 1;
}
}

$Deck_Stack = cr_shuffle($Deck_Stack);
$hand = array_slice($Deck_Stack, 0, 13);
var_see($hand, "Hand");

class Card
{
public string $suit;
public int $value;
public string $name;
public string $short;

public function __construct(string $suit = "None", int $value = 0, string $name = "Zero", string $short = "0"){
    $this->suit = $suit;
    $this->value = $value;
    $this->name = $name;
    $this->short = $short;
    } // end Construct function
} // end class Card

function var_see($var, $name){ // thanks to Edward Yang @ Manual for <pre> idea
    echo "$name: <pre>"; var_dump($var); echo "</pre><br>";
    }

function cr_shuffle($array){
$new_array = array();
$c = count($array);
while($c > 0){
$rand = random_int(0, $c);
$new_array[] = array_slice($array, $rand, 1, true);
array_splice($array, $rand, 1);
$c -= 1;
}
return $new_array;
}
    
?>