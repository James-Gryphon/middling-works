var $game_status;
$game_status = document.getElementById("game_status");
$game_status = $game_status.textContent;
var $active_player;
$active_player = document.getElementById("active_player");
$active_player = $active_player.textContent;
// console.log("Active player: ", $active_player);

if($game_status != "complete" && $active_player != "1")
{
    const $ajax = new XMLHttpRequest();

    const clockCheck = function()
    {
//        console.log("Clock check!");
        $currenttime = Math.round(Date.parse(Date())/1000);
        $timediff = $currenttime - $textstring;
        console.log($timediff);
        /*
        If there has been an action within the last minute, poll quickly: every second.
        If the last five minutes, more slowly; every 5 seconds.
        If the last fifteen minutes, yet more slowly; 15 seconds..
        If within the last thirty minutes, even more slowly; every 30 seconds.
        If it has been more than thirty minutes, poll no more than once a minute.
        If it has been more than an hour, poll no more than once every fifteen minutes.
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
//        console.log("New interval: ", $num2, " and also:", $num1);
        $clockCheck = setInterval(clockCheck, $num2);
        $processor = setInterval(makeRequest, $num1);
    }

    /* This AJAX section is, largely, shamelessly ripped off of the MDN tutorial */

    const makeRequest = function()
    {
        if(!$ajax){ return false;}
        $ajax.onreadystatechange = alertContents;
        $sendstring = "thehunt/play_tracker.php?gm=" + $game_id + "&tm=" + $textstring;
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
                console.log($ajax.responseText);
                if($ajax.responseText == "1")
                {
                // Refresh the content
//                console.log("We would refresh here.");
            location.reload();
                }
            }
            else
            {
//                alert("There was a problem with the request.");
            }
        }
    }

    var game_id;
    var $textstring;
    var $currenttime;
    var $timediff;
    var $num1 = 1000; // 1 second
    var $num2 = 60000; // milliseconds, so 1 minute
    var $sendstring;
    $game_id = document.getElementById("game_id");
    $game_id = $game_id.textContent;
    $textstring = document.getElementById("last_active");
    $textstring = $textstring.textContent;

    $clockCheck = setInterval(clockCheck, 1000);
    $processor = setInterval(makeRequest, 60000);

}