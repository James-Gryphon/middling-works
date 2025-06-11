<?php
// thanks to A2 Hosting for their guide on session cleaning and advice on using PHP to run cron
// thanks to tylerl for explaining mtime, ctime, etc.
$sets = parse_ini_file("settings.ini");
define("sessiondir", $sets['sessiondir']);
define("sessionlength", $sets['sessionlength']);
// print_r(sessionlength);
chdir(sessiondir);
$dir = scandir(sessiondir);
// print_r($dir);
// echo "<br>The time:"; echo(time()); echo "<br>";
foreach($dir as $item){
// echo "Hi from $item: "; print(filectime($item)); echo "<br>";
    // thanks to first comment on strpos at PHP Manual for "false"
if(strpos($item, "sess_") !== FALSE){
//    echo "Sess file:";
//    print_r(filectime($item));
if(((time() - filectime($item)) > sessionlength)){
// echo "This could be destroyed?<br>";    
unlink($item);
}
}
}
// print(filectime("index.php"));
?>