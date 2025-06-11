<?php
// Game Builder - used to initialize games
echo "Loaded";
/*
Process of making a game
1. Decide if it's online, hotseat, or live
Online games involve online players from different computers, and you need stuff to allow people to join online games. Hotseat or live are run locally, but you still want to save a file for people to access, in case they need to change machines, their current one restarts, etc.

2a. If it's an online game, the host decides the rules, name of the game, etc, and joins the game himself. The game begins when he's ready. The guest can only join and wait for the host to do something.
2b. If it's a local game, the host decides the rules, agents, name of the game, and the game begins as soon as he's done configuring it.
2bª. If it's a live game, it is similar to a local game. The main difference is that the live game doesn't directly keep track of player cards.

In any case, the game needs a save file. The save file contains the full state of the game. The $_SESSION only contains your currently loaded game, and perhaps any variables that aren't yet (but are about to be) in the game save file.

Game Save File Contents (Sample)
Name: "The Sample Game"
Type: "Local"
Host: "Silverwing"
Players: Array(
0 => array(
"name" => "Silverwing",
"password" => "encrypted_string",
"cards" => array(),
"agents_found" => array(
"North",
"East"
),
"location" = "Nacogdoches",
)
);
Mastermind: "San Antonio",
Agents: Array(
"North": 
array(
location => "Wichita Falls",
founds => 2),
"East": array(
location => "Lufkin",
founds => 2),
"West": array(
location => "Midland",
founds => 3), // for 4 players, 3 is the 'found' number. 0 is after all players found it.
"South": array(
location => "Galveston",
founds => 3
),
),
Deck: Array(
[0] => new Card(
symbol: "🔎",
name: "Search for/Capture Mastermind",
short: "S/C",
desc: "You can either search for the Mastermind, or attempt to capture him. A search reveals your current distance from the Mastermind in steps. A capture attempt will win the game if the Mastermind is in the same territory as you."
    ),
[1] => new Card(
symbol: "🔎",
name: "Search for/Capture Mastermind",
short: "S/C",
desc: "You can either search for the Mastermind, or attempt to capture him. A search reveals your current distance from the Mastermind in steps. A capture attempt will win the game if the Mastermind is in the same territory as you."
    ),
[2] => new Card(
symbol: "☚",
name: "Point to Mastermind",
short: "PNT",
desc: "This card shows you an adjacent territory that is one step closer to the Mastermind."
    ),
),
Turns: Array(
// This is the main new thing written here so far
[-2] => array(
player: "Goldbug",
type: "card"
name: "search_capture",
result: "Goldbug plays 'Capture Mastermind'. The Mastermind is not in Colorado Springs",
secret: ""
),
[-1] => array(
player: "Goldbug",
type: "move"
name: "Albuquerque",
result: "Goldbug moves to Albuquerque.",
secret: "The covert agent is 2 links away."
),
[0] => array(
player: "Silverwing",
type: "move"
name: "Dallas"
result: "Silverwing moves to Dallas."
secret: "The covert agent has been found. You will receive 4 cards upon leaving the region."
),
[1] => array(
player: "Silverwing",
type: "card"
name: "search"
result: "Silverwing searches for the Mastermind."
secret: "The Mastermind is 5 links away."
),
[2] => array(
player: "Blackfire",
type: "card",
name: "point",
result: "Blackfire plays 'Point to Mastermind'.",
secret: "Branson is one step closer to the Mastermind."
)
[3] => array(
player: "Blackfire",
type: "card",
name: "charter",
result: "Blackfire plays 'Charter Flight' and is brought to Sacramento.",
secret: ""
),
[4] => array(
player: "Whitewolf",
type: "card",
name: "take_another_turn",
result: "Whitewolf plays 'Take Another Turn'.",
secret: ""
),
[5] => array(
player: "Whitewolf",
type: "card",
name: "bug_agent",
result: "Whitewolf plays 'Bug Another Agent'.",
secret: "Blackfire is 5 links away from the Mastermind."
),
[6] => array(
player: "Whitewolf",
type: "card",
name: "close_national_borders",
result: "Whitewolf closes all of the national borders.",
secret: ""
),
[7] => array(
player: "Whitewolf",
type: "card",
name: "close_local_borders",
result: "Whitewolf closes the borders to the Northwest region.",
secret: ""
),
[8] => array(
player: "Goldbug",
type: "card",
name: "search",
result: "Goldbug searches for the Mastermind."
secret: "The Mastermind is in your territory.",
),
[9] => array(
player: "Goldbug",
type: "card",
name: "search_capture",
result: "Goldbug begins an operation to capture the Mastermind... and is almost successful! Goldbug would have won the game... but the Mastermind has performed a global jump!!!",
secret: ""
), // next two are for current turns
[10] => array(
player: "Silverwing",
type: "",
name: "",
result: "",
secret: "",
),
[11] => array(
player: "Silverwing",
type: "",
name: "",
result: "",
secret: "",
)
)
// end save file sample

The save file is built when the host goes thru the initial setup process. It can be destroyed by the host, or else it will naturally expire after a set period of time of not being updated, e.g. one week or one month.

After this, the game would consist of reading the save file to determine where things are, processing actions, and reading card powers and exception lists to determine if commands are valid.

*/





/*
Information to keep track of:
1 Build game board - use generic function, and load special game board
2 Make card deck - use generic function, and load special card deck
3 Set player state - generate starting position for players, assumed to be same for everyone
4 Set Mastermind state
5 Set local agent state
6 Set turn order, who is moving
7 State of players, and their cards
8 State of card deck
*/

// Step 1 - build game board
$Board = array();
$Continents = array();
$board_name = "northamerica"; // add option later for acceptable array and all that
require_once("boards/$board_name.php");
// at this point, $Board and $Continents should both be populated

// Step 2 - make card deck
$Deck = array();
require_once("cards/types.php");
require_once("cards/$board_name.php");
// at this point, $Deck should contain all the cards you need (although not their powers)

// Step 3 - set player state
$rand = random_int(0,count($Continents)-1);
$continent = $Continents[$rand];
$country = random_int(0,count($continent->territories)-1);
$country = $continent->territories[$country];
$player = $country;
// at this point, the player starting position should be set

// Step 4 - set Mastermind state
while(!isset($mastermind) || $mastermind == $player){
$rand = random_int(0,count($Continents)-1);
$continent = $Continents[$rand];
$country = random_int(0,count($continent->territories)-1);
$country = $continent->territories[$country]; 
$mastermind = $country;
}
// At this point, the Mastermind's location should be set

// Step 5 - set local agent states
foreach($Continents as $Continent){
while(!isset($agent) || $agent == $mastermind || $agent == $player){
$country = random_int(0,count($Continent->territories)-1);
$agent = $Continent->territories[$country];}
$Continent->agent_spot = $agent;
unset($agent);
} // At this point, each Continent *object* should have an agent set


// Read the game file. What game file? There isn't one - it's time to fix that.

/* Step x - build game object
The Game class, for reference -
public string $name;
public array $players;
public string $mastermind;
public array $agents;
public array $deck;
public array $turns;
public array $board;
public array $continents;
*/

// First the name
if(isset($_POST['game_name']) && !empty($_POST['game_name'])){
// set up unique ID for games
$name = $_POST['game_name']; } else {
$name = "A Mysterious Game";
}
$name .= " - #"; $name .= time();

// Next the type
$gametypes = array("online", "hotseat", "live"); // keep bad gametypes out
if(isset($_POST['gametype']) && in_array($_POST['gametype'], $gametypes)){
$gametype = $_POST['gametype'];
} else {$gametype = "live";}
    
// Now the players - is this a local/live game, or an online game?
$agent_array = array();
if($gametype == "online"){
// do stuff for online array - add player object
$agent_array[] = newPlayer($_POST['username'], $_POST['password']);
} else {
/* Adding agents for a local or live game - this wouldn't work for an online game! */
$i = 1;
while ($i < 8){
if(isset($_POST["agent$i"]) && !empty($_POST["agent$i"])){ 
    $agent_array[] = newPlayer($_POST["agent$i"], "");
}
$i += 1;
}
/* end thing to build agent array for a local game */
}

// Turns array doesn't really need to be filled out until later, when game is being played.
$turns = array();

/* Step x - build game object
The Game class, for reference -
public string $name;
public array $players;
public string $mastermind;
public array $agents;
public array $deck;
public array $turns;
public array $board;
public array $continents;
*/

$Game = new Game(
name: $name,
players: $agent_array,
mastermind: $mastermind,
deck: $Deck,
turns: $turns,
board: $Board,
continents: $Continents
);

print_r($Game);


function newPlayer($name, $password){
if(strlen($name) == 0){ // need to make sure it's more than 0 chars
$name = "Secret Agent";} // might add cutesy anonymous names later
$password = password_hash($password, PASSWORD_DEFAULT);

// build player object now
$player = new Player(
name: $name,
password: $password,
cards: array(),
agents_found: array(),
location: ""
);
return $player;
}

?>
