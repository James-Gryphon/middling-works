<?php
$rostertexts = array(
    "name" => "Teams",
    "desc" => "Fancy teams hailing from fantastic cities in a fabulous world. Calculating their strength is complicated, but the results are fun to watch. Matches are played to 4 points.",
    "vers" => "1.5.0-R3",
    "length" => "4");
    
$contestants = array(
$red = new Dice(
    3, 6, 0,
name: 'Rocham Rockets',
color: '#fff',
background: '#550000',
blurb: 'Based in Rocham, where the Burrell Cosmos Center is located, the Rockets have established a reputation as one of the league\'s hardest teams to outpace.',
class: "rocham-rockets"
    ),
$blue = new Dice(
    1, 20, 0,
name: 'Harvey Harriers',
color: '#ff0',
background: '#99f',
blurb: 'The rural community of Harvey has an uncanny ability to produce, develop, and find talent, and this keeps them a force to be reckoned with in a competitive world.',
class: "harv-harriers",
    ),
$green = new Dice(
    7, 2, 0,
name: 'Magtaw Machine',
color: '#999',
background: '#333',
blurb: 'Once the industrial capital of the country, Magtaw has fallen upon hard times. However, this team has not, and strive every tournament to bring glory back to their home.',
class: "mag-machine"
    ),
$yellow = new Dice(
    2, 9, 0,
name: 'Zip City Martins',
color: '#9f3',
background: '#224',
blurb: 'Originally from Inora, the team has been relocated to the up-and-coming metropolis of Zip City, a place eager to see the excitement of the dice tournaments. The enthusiasm of their new fans is matched only by the disgust of their old.',
class: 'zip-martins'
),
$purple = new Dice(
    4, 4, 0,
name: 'Walloon Wasps',
color: '#ff0',
background: '#505',
blurb: 'The hard-working Wasps have managed to carve out a niche for themselves in the mid-nation city of Walloon.',
class: 'wall-wasps'
    ),
$orange = new Dice(
    2, 5, 1, 1,
name: 'Alpine Timber Wolves',
color: '#ff6',
background: '#070',
slump: -1,
blurb: 'Although famous for its lumber, the mountainous city of Alpine is now better known as a growing tech center and popular vacation area. The Timber Wolves may not be the most talented team, but play a gritty game that appeals to their base, and learn from every loss.',
class: 'alpine-wolves'
    ),
    $cyan = new Dice(
    2, 2, 2, 2, "high", 1,
name: 'Big Town Bolts',
color: '#fff',
background: '#009',
surge: 1,
blurb: 'The old Big Town team used to electrify their audiences, but have been grounded the last few seasons as they\'ve gone through a rebuild. However, they have a lot of potential, and when charged up, can blast through the opposition. They have a bitter rivalry with the Magtaw Machine, but lately always seem to come out behind.',
class: 'big-city-bolts'
    ),
    $beige = new Dice(
    3, 11, 0, 1, "low", 1,
name: 'Inora Icicles',
color: '#ddf',
background: '#003',
blurb: 'Left out in the cold when the Martins flew the coop, Inora waited a long time for a new team. Now the Icicles, or the \'Ikes\' as they are affectionately called, are in town. Inexperienced, they tend to freeze up in the playoffs.',
class: 'inora-icicles'
    ),

$quick = new Dice(
    6, 6, 0, 1, "high", 4,
name: 'Quicksey Counters',
blurb: 'Everything old and somewhat used seems to come to Quicksey, but don\'t count these Counters out! Their world-class coaching always seems to be able to find the right lineup, just in time to lock out the opposition.',
class: 'quick-count'
    ),

$defense = new Dice(
    2,6,0,1,
name: 'Preston Protagonists',
blurb: 'Perennial plucky underdogs, the \'Pros\' have acquired a reputation for winning tight games in overtime. Their fans have come to expect exciting and decisive games every time they play.',
class: 'pres-pros',
boost: 2,
tiebreak: 7
),

$jellies = new Dice(
    5,3,0,1,
    name: "Seaport Stingers",
    blurb: 'Named for the abundance of jellyfish that can be seen just a little way off the coast, the Stingers are well-balanced and in a good position to become a successful expansion team.',
    class: 'sea-stingers',
),

$chokes = new Dice(
    5,4,0,1,
name: 'Mandril Millionaires',
blurb: 'Although strong on paper, and sometimes accused (facetiously and otherwise) of trying to buy their way to a championship, this team has developed a reputation for choking when it counts. Will this be the season things turn around?',
class: 'man-mills',
surge: -1,
slump: 1,
tiebreak: -1,
),

/*,
$scouts = newDice(

)
$puffins = new Dice(
    3, 6, 0, 1, "high", 1,
name: 'Newport Puffins',
color: '#000',
background: '#ddd',
blurb: 'blurb',
class: 'newport-puffins'
),
$green_leaves = new Dice(
    5, 3, 0,
name: 'Long Lake Leaves',
color: '#9f9',
background: '#070',
blurb: 'blurb',
class: 'longlake-leaves'
),*/
/*
southcounty scouts
collinghook counters
quickton counters
*/

    );
?>