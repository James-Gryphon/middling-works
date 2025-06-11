<?php
$content_array = array();

$content_array[] = array(
"title" => "What is this?",
"anchor" => "whatsthis",
"text" => "<p>\"Ultimate\" is an implementation of a pen-and-paper game where you're trying to get three in a row. This has been a favorite game for a while now, and I felt like it was time to upgrade from spreadsheets.</p>");

    $content_array[] = array(
        "title" => "Understanding and using the control panels",
        "anchor" => "usingcp",
        "text" => "<p>
        This section will attempt to explain the interface.<br><br>
        <img src=\"stuff/th_docs_control_panel1.png\"><br>
        <br>
        <b class='z3'>Game Code:</b> This is used for viewing and joining private games, although technically it can be used to access any game that you have the code for. The host copies this code and sends it to anyone he wants; they paste it into the box provided for it in the lobby page.<br><br>
        <b class='z3'>Last Activity:</b> Games will automatically expire after some time with no recent updates, generally after a week or so.<br><br>
        <b class='z3'>Status:</b> Game status options include \"open\", \"active\", and \"completed\". Open games haven't started yet, and may be waiting for players. Active games are being played. Completed games have been resolved, either by someone finding the Mole, or everyone losing or resigning.<br><br><br>
        <img src=\"stuff/th_docs_control_panel2.png\"><br>
        <br>
        The top box here is the <b class='z3'>region blurb</b>, which shows the region the player is currently in and a short flavor-text description.<br>
        After this, we have the similar <b class='z3'>territory blurb</b>.<br><br>
        The line of text after shows the current player and the number of actions that they have available in their turn.<br><br>
        The two adjacent boxes, the <b class='z3'>card chooser</b> and <b class='z3'>location chooser</b> respectively, allow you to select a card to play, or an adjacent territory to move to. Each will generally take up a single action. The bulk of gameplay is done here.<br><br>
        Below this, we have the <b class='z3'>button panel</b>. These correspond to special, rare actions that are not always available, or desirable if they are available. To activate any of these buttons, you must first activate the checkbox directly below that button, to verify your intention. Buttons that are greyed out, and are totally empty (instead of having a checkmark or a line), are not available in the current game state. The buttons are as follows:<br>
            <b class='z3'>Capture:</b> Attempt to capture the Mole in your current territory. If your guess is right (that the Mole is in your territory), you win the game. If you are wrong, you immediately <u>lose</u> the game and are out of play. This button is only available when the game is active and it is your turn.<br>
            <b class='z3'>Start:</b> This begins the game, provided that you are the game host and the game is not active or completed.<br>
            <b class='z3'>Pass:</b> Spend an action staying in place. This is only available when it is your turn and the game is active.<br>
            <b class='z3'>Join:</b> Join the game. This is only available when the game is open, you are not already in the game, and you are not an unregistered spectator.<br>
            <b class='z3'>Leave:</b> Leave the game. This varies depending on your role and the game's state. If the game is <u>active</u>, then it is only available on your turn, and by doing this you resign the game. On the other hand, if the game is <u>open</u>: If you've joined the game, you leave the game with no further consequence; if you're the host, the game is deleted. Finally, if the game is <u>completed</u>, it is, of course, too late to leave it.<br><br>
        <img src=\"stuff/th_docs_control_panel3.png\"><br>
        This section provides essential information about the state of and activities in the game.<br>
        
        The move log shows players' actions in regular text. It also shows secret messages that are addressed specifically to you; these are shown <u>completely</u> in <i>italics</i>.<br>
        Of the messages shown above, other players can see the small text (showing whose turn it is), and they can see that James has moved from Zeta to Gamma, or that he has played a card. Those messages have italics, but they are not completely italicized; any italics you see are only for emphasis.<br>
        On the other hand, the messages about discovering Jason in Gamma, and the distance of the Mole from Zeta, are secret messages. They are entirely italicized.<br>
        Messages are listed in reverse-chronological order, so the newest messages are always at the top, and each message in turn is older than the previous one,<br><br>

        Below the move log, we have the player list, which shows, in order of movement, who all is involved in the game, their current locations (in underlined text), the number of cards they have in their hands, and whether they are the active player or not.<br><br>

        Finally, we have the clue section. Each line shows the name of a clue (a person, place or thing, which, when you find them, will provide you with cards), the region they are associated with, and their current status.<br>
        There are four statuses, three of which are visible to the player:<br>
        <b class='z3'>1 & 2) Unknown:</b> You haven't moved into the clue's territory yet, <i>or</i> you have moved across a territory that a clue is in, but your turn hasn't ended yet; information about clues is revealed every time you spend two actions, which is the normal length of a turn.<br>
        <b class='z3'>3) Discovered:</b> You've been notified that you've discovered a clue, but you haven't left the region yet or drawn your cards yet.<br>
        <b class='z3'>4) Revealed:</b> You've drawn your cards, and everyone's been notified that you discovered the clue and received your cards.
        </p>
    ");

    $content_array[] = array(
        "title" => "Using the map's features",
        "anchor" => "mapfeatures",
        "text" => "<p>The map is defined as the picture you see showing the landscape when you are playing the game.<br><br>
        Territories on maps are identified by a label and the player position square, which is in the color of the region it is in.<br>
        <img src=\"stuff/th_docs_citylabel1.png\">
        <img src=\"stuff/th_docs_citylabel2.png\">
        <img src=\"stuff/th_docs_citylabel3.png\">
        <br>
        There are two types of links between territories, two-way links and one-way links.<br>
        Two-way links are typically colored black or bright yellow, and are depicted by lines drawn between the two territories.<br>
        <img src=\"stuff/th_docs_twowaylink1.png\">
        <img src=\"stuff/th_docs_twowaylink2.png\">
        <br>
        One-way links are typically colored light blue and have a line with an arrow pointing towards another territory.<br>
        <img src=\"stuff/th_docs_onewaylink.png\"><br><br>

        You can determine whether a player is at a territory, and which player it is, by looking for a line. The color of the lines will change from black to white depending on the color of the square that they're on, so that they are always visible. A vertical line represents the first player, a horizontal line the second player, a diagonal line going from top-left to bottom-right the third player, and a diagonal line going from bottom-left to top-right the fourth player.<br>
        <img src=\"stuff/th_docs_player_legend_1.png\">
        <img src=\"stuff/th_docs_player_legend_2.png\">
        <img src=\"stuff/th_docs_player_legend_3.png\">
        <img src=\"stuff/th_docs_player_legend_4.png\"><br>
        When multiple players are on one territory, their lines will intersect, and can form various shapes, such as a plus for the first two players, an X for the last two, or an asterisk for all four.<br>
        <img src=\"stuff/th_docs_player_legend_plus.png\">
        <img src=\"stuff/th_docs_player_legend_x.png\">
        <img src=\"stuff/th_docs_player_legend_star.png\">
        </p>
    ");

    $content_array[] = array(
        "title" => "What's the version history?",
        "anchor" => "versionhistory",
        "text" => "<p>v1.0.0, which came out sometime.
        </p>
    ");

    
$content_array[] = array(
    "title" => "James, you're terrible at making manuals/I still have questions/you need to clarify something/you need to rewrite everything.",
    "anchor" => "stillquestions",
    "text" => "<p>I suspected as much.<br><br>Like many other things I write, this is subject to editing. <a href='index.php?s=thehunt&a=tutorial'>Look at the tutorial</a> first, to make sure that it doesn't answer your questions. Then, if you're still at a loss, get in touch with me at the <a href='mailto:support@middlingworks.com'>usual address</a> and we'll try to sort the problems out.<br><br>Please try to be as specific and detailed in your criticism as possible. If you only say, 'I don't understand thus-and-so', the best I can do is to guess what your problem is. But if you have some idea why you don't understand it, and can tell me, that'll go a long way towards helping both of us out.</p>
");

$local_site_name = "About ULT";
?>
