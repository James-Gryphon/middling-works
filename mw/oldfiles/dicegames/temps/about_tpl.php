    <div class="text_board">
<h2>About This Site</h2><br>
Dice Games is a simulation that features a number of hard-rolling sets of virtual dice as they battle each other in elimination series to see who will be the Ephemeral Tournament Champion.<br><br>

This isn't exactly a game; a proper game would have an objective for the player to aim at, and this has none, unless you count reloading the page until your favorite challenger wins. It isn't even a toy: a toy would be interactive, and this has no components that the user can alter. However, if numbers, statistics, chance, and inventing sporting narratives are your idea of a good time, this could be just the thing for you.<br><br>

I've had similar ideas in my mind for quite a while, but the idea of making a dice-based sim in particular was probably solidified by watching the famed Marble Leagues <i>(late Marblelympics)</i>. It turns out that you can ascribe a lot of personality to an inanimate object if you try!<br><br>

For that matter, you can ascribe purpose to a simulation. There <i>is</i> a potential game here, although it doesn't lie in manipulating rolls and outcomes. The contestants are not identical, and are probably not equal. They can have different numbers of sides, slightly different rules that they follow, or 'modifiers' which boost or decrease the results they get. If you have an analytical mind, you may enjoy examining the results from tournaments and figuring out the attributes each one has.<br><br>

To make it easier for you, and to get you started, here are the main attributes I currently use <span class='z3'>(as of 4/11/23)</span> to create competitors:<br>
<i><b>Dice count:</b> Number of dice to roll<br>
<b>Sides:</b> Sides of dice<br>
<b>Addition modifier</b> applies per die (negative numbers can be used)<br>
<b>Multiplicative modifier</b> applies to sum, after other modifiers<br>
<b>Rules</b>: High keeps the highest dice, low keeps the lowest dice; used with Rint<br>
<b>Rint:</b> How many dice to remove from the set; whether it removes low or high dice depends on Rules setting<br>
<b>Surge:</b> How much of a modifier the set gets when it wins a roll. Reset after every match.<br>
<b>Slump:</b> Like Surge, but triggered after a loss.<br>
<b>Tiebreak:</b> Used when there is a tie in the numbers rolled. Higher tiebreak numbers trump lower tiebreak numbers.<br>
<b>Boost:</b> Like surge, but given after winning a match (instead of winning a roll), and isn't reset until the tournament is over.<br><br></i>

I may periodically devise new rosters, particularly if this is a popular feature and people crack the values. <a href="mailto:gpress@middlingworks.com">Send me an email</a> with your guesses if you think you've figured it out!<br><br>

<h2>Version History</h2><br><br>
v1.5.0: Changed up the layout so that more information is visible at once, while removing "Statistician Mode" (which used to show match results in a row, with each roll in a column, instead of a column with each roll in a row). Tweaked JavaScript and added 'iterate' button. Added four new contestants to roster 3, "Teams", and made other tweaks. Added tiebreak and boost attributes. Winners are now always added into the next tournament's roster.<br><br>
v1.4.1: Added a feature that shows the previous championship teams, up to 20.<br><br>
v1.4: Officially released two new rosters, "Balanced" and "Teams", with a roster-selecting feature, as well as minor tweaks and some incremental improvements. Removed custom dice colors (hopefully temporarily).
<br><br>

v1.3.2: Added new 'background color' feature. Made a number of tweaks to integrate the page better with the main Soopergrape layout.<br><br>

v1.3.1: Developed internal testing tool and made fixes accordingly. Brought the total contestants up to 16, and made minor tweaks to others.<br><br>

v1.3: Added a new "Custom Dice" feature that allows you to try your hand at making a competitor of your own, who is automatically seeded into the tournament. Added two new competitors to the regular roster.<br><br>

v1.2.5: Made a number of under-the-hood changes, some of which may contribute to future features. Added the roster page, to inject some personality into the proceedings, and also to help give clues about the competitors' capabilities.<br><br>

v1.2: Besides minor tweaks to improve usability (which were part of silent 1.1.x releases), v1.2 includes three new attributes, <b>Surge</b>, <b>Slump</b>, and <b>Momentum</b>. When a competitor wins or loses, its surge/slump value, respectively, are added to its momentum. The momentum value is added to the sum every roll in a match. Competitors may or may not have surge/slump values. Version 1.2 also adds four new competitors, for Roster 1.1, which has 12 total. The pool randomly selects 8 of them for the tournament and leaves the others out. This about page was also added during v1.2.<br><br>

v1.1: Features JavaScript (hacked together with help from old scripts of mine, the official manual and its samples, and lots of commentary and principles from StackExchange), and fixes the problem with 'dramatic tension' by allowing you to reveal the results a bit at a time. <b>Left-click on a box to show one roll; middle-click it to show the whole thing.</b> Semi-finals, the finals, and the winner are all revealed in their appropriate times.<br><br>

Unfortunately, this new functionality only works with JavaScript, but you can still access and use the site without it turned off.<br><br>

v1.0: represents the main release as of opening day. It featured a roster of 8 competitors in an elimination tournament, "Statistician Mode", and <a href="https://soopergrape.com/index.php?a=gpress&d=3" target="_blank">a link to my blog post introducing the site</a>.
</div>
