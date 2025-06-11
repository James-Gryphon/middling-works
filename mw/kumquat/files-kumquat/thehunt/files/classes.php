<?php

class Continent {
public string $name;
public array $territories;
public string $agent_spot;

public function __construct(string $name = "Continent", array $territories = ["Alberta"]){
global $Continents;
    $this->name = $name;
    $this->territories = $territories;
    $Continents[] = $this;
    } // end Construct function
} // end class Continent

class Territory
{
public string $name;
public array $links;

public function __construct(string $name = "Territory", array $links = ["Nowhere"]){
global $Board;
    $this->name = $name;
    $this->links = $links;
    $Board[$name] = $this; // thx to hakre and "Array of objects within class in PHP" for inspiration
    } // end Construct function
} // end class Territory

class Game {
public string $name;
public array $players;
public string $mastermind;
public array $agents;
public array $deck;
public array $turns;
public array $board;
public array $continents;
}

class Player{
public string $name;
public string $password;
public string $location;
public array $cards;
public array $agents_found;
}

class Card
{
public string $suit; // Suit - Hearts, Clubs, etc.
public string $symbol; // a unicode symbol that goes well with the card, e.g. ♣
public int $value; // Trick-taking value
public string $name; // full name - 'King', 'Queen'
public string $short; // short name - 'K', 'Q'
public string $desc; // description

public function __construct(string $suit = "None", $symbol = "★", int $value = 0, string $name = "Zero", string $short = "0", string $desc = "This is a generic card."){
global $Deck;
    $this->suit = $suit;
    $this->value = $value;
    $this->name = $name;
    $this->short = $short;
    $this->desc = $desc;
    } // end Construct function
} // end class Card


?>