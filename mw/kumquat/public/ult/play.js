// Modified from thehunt/play.js
var $refresh;
$refresh = document.getElementById("refresh_on");
$refresh = $refresh.textContent;
$clock_on = document.getElementById("clock_setting");
$clock_on = $clock_on.textContent;

$move_box = document.getElementById("ult_move");


// Function to change the content of t2
function selectMove(event) 
{
    // thanks to Maker's Aid: https://makersaid.com/id-of-clicked-dom-element-javascript/
    // Also to PlainEnglish.io and w3schools
    var $cell = event.target.innerHTML;
    var $intVal = parseInt($cell); 
    // this is to check we just clicked cells, and not a whole line of TDs or something...
    // Prob'ly bad form, oh well
    // This will need changing if I add non-numeric notation, though
    if($intVal > 0 && $intVal < 100 && !$move_box.disabled)
        {
            if($move_box.value === $cell)
                {
                    $draw_checkbox = document.getElementById("draw_offer_box");
                    if($draw_checkbox.checked)
                        {
                            document.getElementById("draw_offer").click();
                        }
                        else
                        {
                            document.getElementById("move_submit").click();
                        }
                }
            else if($cell !== "X" && $cell !== "O")
                {
                    $move_box.value = $cell;
                } 
        }
  }
  
  // Add event listener to table
  const game_table = document.getElementById("game_table");
  game_table.addEventListener("click", selectMove, false);

// Reserve this for the player for now, so we don't hammer the server. Maybe in the future we can be
// more spectator-friendly, when/if I figure out a better way of doing this.
if($refresh == "yes")
{
    console.log("refresh!");
    const $ajax = new XMLHttpRequest();

    const clockCheck = function()
    {
        $currenttime = Math.round(Date.parse(Date())/1000);
        $timediff = $currenttime - $textstring;
        /*
        If there has been an action within the last minute, poll quickly: every second.
        If the last five minutes, more slowly; every 5 seconds.
        If the last fifteen minutes, yet more slowly; 15 seconds..
        If within the last thirty minutes, even more slowly; every 30 seconds.
        If it has been more than thirty minutes, poll no more than once a minute.
        If it has been more than an hour, poll no more than once every fifteen minutes.
        These times were made for "The Hunt" and aren't ideal, perhaps, but they may reduce server load
        Some of them could be eliminated, given that we don't have clocks larger than 30 minutes
        */
        switch(true)
        {
            case $timediff <= 60: // let's keep stage 1, every second
                $num1 = 1000; // milliseconds to check server
                $num2 = 60000; // milliseconds to check clock
                break;
            case $timediff <= 300: // stage 2 - 5 seconds, minutes
                $num1 = 5000;
                $num2 = 300000;
                break;
            case $timediff <= 1500: // stage 3 - 15
                $num1 = 15000;
                $num2 = 900000;
                break;
            case $timediff <= 3000: // stage 4 - 30
                $num1 = 30000;
                $num2 = 1800000;
                break;
            case $timediff <= 6000: // stage 4 - 60
                $num1 = 60000;
                $num2 = 3600000;
                break;
                case $timediff > 6000: // stage 5 - over 60
                $num1 = 900000;
                $num2 = 6000000;
                break;
        }
        clearInterval($clockCheck);
        clearInterval($processor);
        $clockCheck = setInterval(clockCheck, $num2);
        $processor = setInterval(makeRequest, $num1);
    }

    /* This AJAX section is, largely, shamelessly ripped off of the MDN tutorial */

    const makeRequest = function()
    {
        if(!$ajax){ return false;}
        $ajax.onreadystatechange = alertContents;
        $sendstring = "ult/play_tracker.php?match=" + $match_id + "&gm=" + $game_id + "&tm=" + $textstring;
        $ajax.open("GET", $sendstring, true);
        $ajax.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );
        $ajax.send();
//        console.log("Sent: ", $sendstring);
    }

    const alertContents = function()
    {
        if($ajax.readyState === XMLHttpRequest.DONE)
        {
//            console.log("Done.");
            if($ajax.status === 200)
            {
//                console.log("200");
//                console.log($ajax.responseText);
                if($ajax.responseText == "1")
                {
                // Refresh the content
            window.location.replace(window.location.href);
            // OpenReplay suggested putting 'window' before 'location.replace'
                }
            }
            else
            {
//                alert("There was a problem with the request.");
            }
        }
    }

    var $match_id;
    var $game_id;
    var $textstring;
    var $currenttime;
    var $timediff;
    var $num1 = 1000; // 1 second
    var $num2 = 60000; // milliseconds, so 1 minute
    var $sendstring;
    $query_string = new URLSearchParams(window.location.search);
    $match_id = $query_string.get("match");
    $game_id = $query_string.get("gm");
    $textstring = document.getElementById("last_active");
    $textstring = $textstring.textContent;
    $clockCheck = setInterval(clockCheck, 1000);
    $processor = setInterval(makeRequest, 60000);
}

$cur_mover = document.getElementById("cur_mover").innerHTML;
let $running_minutes;
let $running_seconds;
if($refresh != "inactive" && $clock_on != " 1 year per player")
{
    getRunningThings();
    setInterval(getRunningThings, 1000);
}

function getRunningThings()
{
    if($cur_mover == "1")
        {
            $running_minutes = document.getElementById("host_minutes");
            $running_seconds = document.getElementById("host_seconds");
        }
        else if($cur_mover == "2")
        {
            $running_minutes = document.getElementById("guest_minutes");
            $running_seconds = document.getElementById("guest_seconds");
        }
        clockMaintain($running_minutes, $running_seconds);
}

function clockMaintain($running_minutes, $running_seconds)
{
    $curSecond = $running_seconds.innerHTML;
    $curMinute = $running_minutes.innerHTML;
    if($curSecond > 0)
    {
        $curSecond -= 1;
        $curSecond.padStart(2, "0");
        $running_seconds.innerHTML = $curSecond;
    }
    else
    {
        $curSecond = 59;
        $curMinute -= 1;
        $running_seconds.innerHTML = $curSecond;
        $running_minutes.innerHTML = $curMinute;
    }
    if($curSecond === 0 && $curMinute === 0)
    { // The game is lost on time; refresh the page, for players' convenience
        location.reload();
    }
}
    