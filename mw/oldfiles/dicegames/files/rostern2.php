<?php
$rostertexts = array(
    "name" => "Balanced",
    "desc" => "A set that is configured so that all the competitors should all have roughly equal chances to win. Matches are played to 5 points.",
    "vers" => "1.0.0-R2",
    "length" => "5");

$contestants = array(
$red = new Dice(
    1, 11, 0,
name: 'Nautilus',
color: '#223377',
blurb: 'This contestant has emerged from the deep sea to compete for the championship.',
class: "nautilus"
    ),
$blue = new Dice(
    2, 5, 0,
name: 'Mint Marauder',
color: '#77DDAA',
blurb: 'Mint Marauder is on the move. He hopes to use his star power to promote toothpaste and good dental hygiene.',
class: "mint-marauder"
    ),
$green = new Dice(
    3, 3, 0,
name: 'Beige Blast',
color: '#CCBB77',
blurb: 'Anyone who says beige is boring has not seen this competitor in action. He happens to be Beige Beauty\'s cousin.',
class: "beige-blast"
    ),
$yellow = new Dice(
    4, 2, 0,
name: 'Cone of Doom',
color: '#EE7700',
blurb: 'Much traffic has been stopped when someone tried to run over <i>this</i> cone!',
class: "cone-of-doom"
    ),
$purple = new Dice(
    1, 9, 1,
name: 'Fogstorm',
color: '#CCCCCC',
blurb: 'Many have lost their way trying to find a path to victory by this mysterious contestant.',
class: "fogstorm"
    ),
$orange = new Dice(
    2, 3, 1,
name: 'Ketchup King',
color: '#EE3344',
blurb: 'Can you catch up to Ketchup? Many have found it is not as easy as it sounds!',
class: "ketchup-king"
    ),
    $cyan = new Dice(
    1, 7, 2,
name: 'Man of War',
color: '#553322',
blurb: 'This battleship means to sail his way right to the end of the tournament.',
class: "man-of-war"
    ),
    $beige = new Dice(
    2, 2, 0, 2,
name: 'Thunderbug',
color: '#AA8800',
blurb: 'She may make honey in the off-season, but her sting is a force to be reckoned with in the tournaments.',
class: "thunderbug"
    ),
    $maroon = new Dice(
    1, 5, 0, 2,
name: 'Glassy',
color: '#55AA33',
surge: 0,
slump: 0,
blurb: 'Sharp and ready for action, he\'s ready to cut through the competition.',
class: "glassy"
    ),
    $rose = new Dice(
    1, 13, -1,
name: 'Blue Night',
color: '#556688',
surge: 0,
slump: 0,
blurb: 'He\'s blue, and there\'s a fair chance that his competition will be too.',
class: "blue-night"
    ),
    $tan = new Dice(
    2, 7, -1,
name: 'Pink Pachyderm',
color: '#FFAABB',
surge: 0,
slump: 0,
blurb: 'She never forgets, and she\'s rather hard to forget herself.',
class: "pink-pachyderm"
    ),
    $steel = new Dice(
    3, 3, 0,
name: 'River Ruler',
color: '#FA8072',
surge: 0,
slump: 0,
blurb: 'You might think it\'s fishy, but he\'s on a journey upstream to win the prize.',
class: "river-ruler"
    ),
    $potato = new Dice(
    2, 5, 0,
name: 'Power Plum',
color: '#8822BB',
surge: -1,
slump: -1,
blurb: 'Win or lose, the competition is always plum tired after a gruelling matchup with this contestant.',
class: "power-plum"
    ),
    $aluminum = new Dice(
    1, 11,
name: 'Algae Attack',
color: '#888800',
surge: 1,
slump: 1,
blurb: 'Stop him quick and there\'s nothing to worry about. Let him alone and he\'ll take the match over.',
class: "algae-attack"
    ),
    $stone = new Dice(
    1, 3, 0, 3,
name: 'Dark Red',
color: '#880000',
blurb: 'Dark Red is hoping that if he wins enough championships, he can find a fan who can suggest a better name!',
class: "dark-red"
    ),
    $teal = new Dice(
    2, 3, 1,
name: 'Dark Theme',
color: '#222222',
blurb: 'He might be a bit overrated, but he still has the capacity to darken any opponent\'s day.',
class: "dark-theme"
    ),
    );
?>