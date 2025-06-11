<?php
$rostertexts = array(
    "name" => "Classic",
    "desc" => "The original set of 16 contestants. They are usually color-themed, show off a wide range of the game's features, and have punny descriptions.",
    "vers" => "1.2.5-R1",
    "length" => "7");
    
$contestants = array(
$red = new Dice(
name: 'Red Flag',
color: '#cc0000',
background: '#fceeee',
blurb: 'There’re good reasons for competitors to be alarmed by Red Flag. His considerable talents are balanced only by a fair amount of inconsistency. As a veteran used to the Dice Tournaments, however, he rarely waves or furls in  emotional turbulence. His oldest and strongest rivals include Blue Badger and Yellow Light.',
class: "red-flag"
    ),
$blue = new Dice(
    1, 4, 1,
name: 'Blue Badger',
color: '#000099',
background: '#eeeef8',
blurb: 'A born battler, Blue Badger is known to be tough for any opponent to sink their teeth into. He has been clawing his way up to the top ever since the beginning of the dice tournaments, and the advent of younger and apparently more talented opponents hasn’t diminished his determination to dig out a place for himself as a champion. He has a friendly rivalry with Red Flag, but seems to have a particular score to settle with Light Blue, who he feels has tried to steal his color.',
class: "blue-badger",
    ),
$green = new Dice(
    2, 2, 0,
name: 'Green Shield',
color: '#007700',
background: '#eef6ee',
blurb: 'Never rusty or bent out of shape, Green Shield’s extreme consistency tends to bely a lack of special athletic talent. Although he rarely has the offensive strength to win it all, many challengers’ attempts to make it to the Finals have been blocked by this old guard of the dice tournaments.',
class: "green-shield"
    ),
$yellow = new Dice(
    1, 8, -1,
name: 'Yellow Light',
color: '#999900',
background: '#f8f8ee',
blurb: 'Many who have gotten their expectations up have found themselves coming to a screeching halt after a battle with Yellow Light. This talented and experienced player hasn’t stopped in his drive to win championships. Rumor has it that he is only slowed down by a lingering injury, caused by a lack of caution when he was younger. He enjoys playing tough matches with old rival Red Flag, and wants to shine a little positivity into Tan Tumbler’s career.',
class: 'yellow-light'
),
$purple = new Dice(
    2, 4, 0, 1, "high", 1,
name: 'Super Grape',
color: '#550088',
background: '#f4eef7',
blurb: 'Although rarely soaring high like a bird or a plane, this diminutive player is known for his ability to bring his best to every game. Just when it seems like he might make a mistake, he always seems to find a way to turn it into a decent roll. He might be overwhelmed by bigger or better talent, but few do a better job of playing to potential. In the off-season, he serves as mascot for a grape seed extract company.',
class: 'super-grape'
    ),
$orange = new Dice(
    3, 12, 0, 1, "low", 2,
name: 'Orange Machine',
color: '#ff9900',
background: '#fff8ee',
blurb: 'Fortunately for his competitors, the Big O rarely brings his ‘O-game’. His relaxed attitude towards training and performance tend to make him an average competitor, but if he’s zesty, watch out - few in the roster can match his raw talent. He is well-known for exciting series with Light Blue.',
class: 'orange-machine'
    ),
    $cyan = new Dice(
    1, 3, -1, 3,
name: 'Light Blue',
color: '#0099ff',
background: '#eef8ff',
blurb: 'Light Blue’s sanguine personality and considerable talents have the potential to take him sky-high - that is, when he isn’t plunging beyond his depth! Blissfully blithe about the outcomes of all of his matches, he considers all of the competitors his friends, even though the feelings aren’t always mutual.',
class: 'light-blue'
    ),
    $beige = new Dice(
    3, 3, 0, 1, "low", 1,
name: 'Beige Beauty',
color: '#997755',
background: '#F8F6F4',
blurb: 'This old gal has been breaking barriers ever since her arrival in the league’s first expansion. An unassuming exterior, and a poor working memory for good rolls, conceals power capable of erasing most opponents’ chances of advancing. Her favorite activities are teaching Rose Reactor and trouncing Steely Standard.',
class: 'beige-beauty'
    ),
    $maroon = new Dice(
    1, 6, 0, 1, "high", 0,
name: 'Maroon Marshall',
color: '#770000',
background: '#F6eeee',
surge: 1,
slump: 1,
blurb: 'The mercurial Maroon Marshall is a study in extremes. If he gets out to an early lead, he is almost unbeatable; on the other hand, a loss causes a crisis of confidence. Something of a loner, his closest friend is also his older brother, Red Flag.',
class: 'maroon-marshall'
    ),
    $rose = new Dice(
    1, 3, 1, 1, "high", 0,
name: 'Rose Reactor',
color: '#ff0088',
background: '#ffeef7',
surge: -1,
slump: -1,
blurb: "No other name would fit as well for this sweet-natured competitor. As a middle child who grew up trying to preserve her family’s harmony, she likes fair and balanced series, and beating her always seems to be a long process.",
class: 'rose-reactor'
    ),
    $tan = new Dice(
    1, 10, 0, 1, "high", 0,
name: 'Tan Tumbler',
color: '#ccaa77',
background: '#fcf9f6',
surge: 0,
slump: 1,
blurb: 'Tan Tumbler’s tremendous talent is tapered by his tremulous temperament. An obsessive perfectionist, the slightest setback gets under his skin and causes him to brood - and the more he broods, the more losses he tends to rack up. His attitude of taking losses seriously and yet not playing well under pressure sets him apart and means he has few close friends in the tournaments, but he always feels refreshed after breaks, off-seasons, and visits with his fans.',
class: 'tan-tumbler'
    ),
    $steel = new Dice(
    2, 4, 0, 1, "low", 1,
name: 'Steely Standard',
color: '#3399cc',
background: '#F1F8FC',
surge: 1,
slump: 0,
blurb: 'Steely Standard loves to win, and when he wins, he shifts gears. It might take a while to get his engine started, but once he has picked up momentum, his opponents are little more than speed bumps. All it takes is a couple of wins to fuel him on the trip to a final victory.',
class: 'steely-standard'
    ),
    $potato = new Dice(
    1, 10, -8, 1, "high",
name: 'Hot Potato',
color: '#BB5522',
background: '#FAF4F0',
surge: 1,
slump: -1,
blurb: 'Hot Potato may start out underground, but he isn’t eaten up by slow starts, and if he doesn’t get tossed away quickly, it won’t be long until he can’t be touched!',
class: 'hot-potato'
    ),
    $aluminum = new Dice(
    1, 18, 0, 1, "high",
name: 'Grey Rocket',
color: '#888888',
background: '#f7f7f7',
surge: -1,
slump: 1,
blurb: 'Grey Rocket believes in going full-throttle and winning a match quickly. Unfortunately, she doesn’t have the fuel for a long ascent. If she doesn’t get it done in the first half, she’s bound to come crashing down.',
class: 'grey-rocket'
    ),
    $stone = new Dice(
    1, 1, 2,
name: 'Steady Stone',
color: '#554444',
background: '#f4f3f3',
blurb: 'Unmoved and unchanging, Steady Stone waits in place for his competitors to dash themselves against him. It works more often than you would expect. The hallmark of consistency, he is a good benchmark for any competitor who is unsure of how they stand in the tournaments.',
class: 'steady-stone'
    ),
    $teal = new Dice(
    2, 3, 0, 2, "low", 1,
name: 'Coast Guard',
color: '#008080',
background: '#EEF7F7',
blurb: 'This sea-loving competitor avoids the waves of slumping and surging to bring a classical style of play to the dice tournaments. He is friendly with older contestants, like Green Shield and Yellow Light.',
class: 'coast-guard'
    ),

    );
?>