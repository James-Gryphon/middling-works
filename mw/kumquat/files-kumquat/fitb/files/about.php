<?php
$content_array = array();

$content_array[] = array(
"title" => "What is this?",
"anchor" => "whatsthis",
"text" => "<p>\"Fill in the Blanks\" is a Hangman-like puzzle game where you try to find a word, phrase or sentence with as few guesses as possible. It is largely inspired by the <a href=\"https://www.thetoo.com/viewtopic.php?p=1162075#p1162075\">Odyssey Gallows</a> game.</p>");

$content_array[] = array(
    "title" => "What are the underscores and asterisks?",
    "anchor" => "underaster",
    "text" => "<p>Each underscore is a letter, and each asterisk is a separator between words. If there are two asterisks and three sets of underscores among them, that means there are three words.</p>
");

$content_array[] = array(
    "title" => "How do you guess?",
    "anchor" => "howtoguess",
    "text" => "<p>To guess a single letter, click or type one of the letters in the keyboard-like letter table and hit the \"Submit\" button. Each guess increases your guess count by one.</p>
");

$content_array[] = array(
    "title" => "I already know the solution. Do I have to put letters in one at a time?",
    "anchor" => "canisolve",
    "text" => "<p>You have the option to skip ahead and attempt to solve the puzzle. If your solution is correct, you win immediately; if it's wrong, you lose. If it is invalid, it isn't counted against you and the puzzle remains as it was before.</p>
");

$content_array[] = array(
    "title" => "What is invalid?",
    "anchor" => "invalid",
    "text" => "<p>A solution is invalid if you have the information to know that your solution attempt can't be correct. Some examples of things that might disqualify a solution attempt are if it has too many or too few letters, if the words in the solution aren't the correct length, if known letters are in the wrong place, or letters that are known not to be in the solution are in your solution attempt.</p>
");

$content_array[] = array(
    "title" => "How do you solve a puzzle?",
    "anchor" => "howtosolve",
    "text" => "<p>Click the grey checkbox to the left of the letter table. When it activates, the table will disappear and show a text area. Type your solution into this box, and hit the \"Submit\" button.</p>
");

$content_array[] = array(
    "title" => "I typed a single letter into the solution box, and it sent it as a guess!",
    "anchor" => "singleguess",
    "text" => "<p>That's a feature.<br><br>I don't expect single-letter puzzles, so it seemed useful to do it this way.</p>
");

$content_array[] = array(
    "title" => "How often do new puzzles come up?",
    "anchor" => "puzzlefrequency",
    "text" => "<p>I have puzzles for certain days loaded into a database. I typically do this at least a few days in advance. You should see a new puzzle every day, if I'm keeping busy.</p>
");

$content_array[] = array(
    "title" => "What if you forget to put a puzzle up?",
    "anchor" => "oldpuzzles",
    "text" => "<p>At one point, we would have been in trouble! But now the game accounts for my weakness. If we get to a day I didn't prepare for, the system will rerun the oldest puzzle of its type (weekday or Sunday) that has been rerun the fewest times.
    </p>
");

$content_array[] = array(
    "title" => "What's different about Sunday puzzles?",
    "anchor" => "sunday",
    "text" => "<p>Sunday puzzles have their own rotation that is separate from the regular ones. Their texts are also always from the Bible.</p>
");

$content_array[] = array(
    "title" => "Which Bible translation do you use?",
    "anchor" => "sundaytranslation",
    "text" => "<p>For these puzzles, we use the King James Version. It's public domain in the United States, as well as familiar and poetic, all suitable traits for this game. If I use another translation here, I expect to mention it.</p>
");

$content_array[] = array(
    "title" => "Why isn't there a clue for a certain puzzle?",
    "anchor" => "noclue",
    "text" => "<p>Some puzzles have clues and some don't. It depends on how generous, or creative, I felt. I sometimes have 'themed' series of puzzles, so there may be a connection between previous puzzles and the current one. It helps to play regularly.</p>
");

$content_array[] = array(
    "title" => "What's a blurb?",
    "anchor" => "whatsablurb",
    "text" => "<p>The italicized text that shows up after some puzzles are finished, giving a comment related to the puzzle.</p>
");

$content_array[] = array(
    "title" => "Why isn't there a blurb for a certain puzzle?",
    "anchor" => "noblurb",
    "text" => "<p>I couldn't think of anything interesting to say for that day.</p>
");

$content_array[] = array(
    "title" => "What's a good guess count for a puzzle?",
    "anchor" => "guesscount",
    "text" => "<p>It's common for me to be able to solve a puzzle without any guesses, but I don't think this is the typical experience!</p>
    <p>It depends on the puzzle. They vary enough that it's hard to compare results from one puzzle to another. Theoretically you could mathematically determine the most efficient plays, but because puzzles can use slang or proper names, this is more complex than for other word games.
    </p>
");

$content_array[] = array(
    "title" => "It's a pain playing this game on a regular computer. Can you fix it?",
    "anchor" => "jstips",
    "text" => "<p>I'm a desktop user first; the JavaScript probably fixes your problem.<br>
    <br>
    The script responds to keystrokes. To pick a letter, type it. To send it, hit the Enter key.<br>
    Hit the Tab key to switch to (and from) \"Solve\" mode; this will also focus on the text area. When you're done typing, hit the Shift-Enter keys to submit your solution.
    <br>
    If none of this helps, or you have another problem, then <a href=\"mailto:support@middlingworks.com\">certainly contact me</a>.
    </p>
");

$content_array[] = array(
    "title" => "It's a pain playing this game on a phone. Can you fix it?",
    "anchor" => "phonehelp",
    "text" => "<p>Maybe; <a href=\"mailto:support@middlingworks.com\">contact me and tell me where you're having trouble.</a> Communication is essential, because I'm unlikely to find your issue on my own. It took over a year, and my mother mentioning that the game was unusable on mobile devices, before I brought it to where it is now.
    </p>
");

$content_array[] = array(
    "title" => "It's a pain playing this game with a terminal browser (or a screen reader). Can you fix it?",
    "anchor" => "terminaldreams",
    "text" => "<p>I'd like to. One of my goals has been to make all the site accessible to users with low-spec machines. I haven't succeeded, but it's never far from my mind.</p>
    <p>As of v1.2.2, this should work with terminal browsers. If it doesn't, <a href=\"mailto:support@middlingworks.com\">tell me</a>.</p>
");

$content_array[] = array(
    "title" => "What's the version history?",
    "anchor" => "versionhistory",
    "text" => "<p>v1.2.4 added a preliminary JavaScript to limit typing incorrect characters in the solution box. It is flawed, because it doesn't account for copy-pasting and doesn't show feedback when you do something wrong, but I feel this is useful enough to get it out early. Minor GUI tweaks were also made shortly before this point release.</br>
    v1.2.3 added a JavaScript helper to keep unused letters out of the solution box. The checkbox was made horizontal (technically, a few days before the formal version number update), and the submit button made larger, so they'd be easier to hit on phones. Solving with shift-enter no longer adds a new line.<br>
    v1.2.2 fixed a breaking bug that escaped attention in the previous point release.<br>
    v1.2.1 included a tweak so that the letter table only appears when your browser supports JavaScript, making life more bearable for light browser users.<br>
    v1.2.0 added the letter table, solve mode switching, and JavaScript support, with the goal of making mobile play nicer without damaging the desktop experience.<br>
    I didn't keep a record earlier, but I think v1.1.x added SQL database support and the rerun feature.<br>
    v1.0.0 read puzzles from an array in a local file.
    </p>
");

$local_site_name = "About Fill in the Blanks";
$local_site_meta = "This page explains the rules and history of the 'Fill in the Blanks' word puzzle game."
?>