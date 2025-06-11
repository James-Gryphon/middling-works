<?php
$links =
array(
array(
"url" => "index.php?a=gpress",
"name" => "gPress",
"blurb" => "A blog on various subjects.",
"alt" => "",
"svg" => "gpress",
"linktext" => "gPress",
),
array(
    "url" => "index.php?s=fitb",
    "name" => "Fill in the Blanks",
    "blurb" => "Guess letters to reveal a mystery word, phrase or sentence.",
    "svg" => "fitb",
    "linktext" => "Fill in the Blanks",
),
array(
    "url" => "index.php?s=sm",
    "name" => "Synchronous Messages",
    "blurb" => "Share messages with people that are only revealed when you have both sent them.",
    "svg" => "sm",
    "linktext" => "Synchronous Messages",
    ),
array(
    "url" => "index.php?s=plusminus",
    "name" => "Plus-Minus",
    "blurb" => "With other players, add or subtract from a number and try to reach a target number.",
    "svg" => "plusminus",
    "linktext" => "Plus-Minus",
    ),
array(
    "url" => "index.php?s=thehunt",
    "name" => "The Hunt",
    "blurb" => "Search a continent, planet, city, or other location for the Mole.",
    "svg" => "thehunt",
    "linktext" => "The Hunt",
    ),
array(
    "url" => "index.php?s=maths",
    "name" => "Maths Map",
    "blurb" => "Solve math problems in different categories on a map",
    "svg" => "maths",
    "linktext" => "Maths Map",
    ),
 array(
    "url" => "index.php?s=ult",
    "name" => "Ultimate",
    "blurb" => "ULT TTT",
    "svg" => "ult",
    "linktext" => "Ultimate Tic-Tac-Toe",
    ),
array(
    "url" => "index.php?a=mail",
    "name" => "Middlemail (WIP)",
    "blurb" => "A simple and classsical messaging service.",
    "alt" => "",
    "svg" => "mail",
    "linktext" => "Mail",
    ),
);

if(isset($_SESSION['id']) && (!isset($_SESSION['gp_seen']) || !($_SESSION['gp_seen'])))
{
    $links[0]['linktext'] = "gPress <b class='z3'>(new)</b>";
}

?>
