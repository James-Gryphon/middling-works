<?php

// mostly gPress-specific functions

function Scanner(){
    // read "texts" folder, eliminates directories, and returns array of files + mtimes
    $txt = scandir("home/texts/", 1);
    array_pop($txt); array_pop($txt);
    foreach($txt as $file){
    $files[$file] = filemtime("home/texts/$file");
    }
    return $files;
    }
    
    function singleScan($file){
    // reads file in text folder and returns file + mtime
    if(isset($file) && is_readable("home/texts/{$file}")){
    $file = array("$file" => filemtime("home/texts/{$file}"));
    return $file;
    }
    return 0;
    }

function postOnIndex($key, $topic = "Recent Posts"){
    // wants file name
    // accepts currently selected topic; if there is none, it will use the default topic, which is "Recent Posts"
    // print post link
echo "<a href='index.php?a=gpress";
if($topic != "Recent Posts") {echo "&t=$topic";}
echo "&d=", say($key), "'>$key</a>";
}

function qpostOnIndex($key, $topic = "Recent Posts"){
    // wants file name
    // accepts currently selected topic; if there is none, it will use the default topic, which is "Recent Posts"
    // print post link
echo "<a href='index.php?a=gpress";
if($topic != "Recent Posts") {echo "&t=$topic";}
echo "&d=", say($key), "'>$key</a>";
}

function metaDataMaker($key, $value, $cats){
echo "<div class='meta_data'><span class='z3'>last updated ", dateBuilder($value), "</span><br>";
$topics = fileTopics($key, $cats);
topicPrinter($topics);
echo "</div>";
}

function postGetter($file, $lb = true, $topic = "Recent Posts"){
// wants the file name, and whether to search for the line break
// accepts currently selected topic; if there is none, it will use the default topic, which is "Recent Posts"
// if file exists, gets and prints contents
if(isset($file) && is_readable("home/texts/{$file}")){
$string = file_get_contents("home/texts/{$file}");
if($lb == true){
$string = strstr($string, "\n", true);
$filed = sfsay($file); $string .= "<a href='index.php?a=gpress"; 
if($topic != "Recent Posts") {$string .= "&t=$topic";}
$string .= "&d=$filed'>read entire post</a>";}
echo $string;
}
}

function fileTopics($file, $cats){
// returns a list of every topic associated with the file
foreach($cats as $cat => $value){
if(in_array($file, $value)){
$topic_array[] = $cat;
}
}
return $topic_array;
}

function topicPrinter($topic_array){
// prints a series of links based on a topic array
if(isset($topic_array)){
echo "<span class='z3'>topics: ";
foreach($topic_array as $topic){
echo "<a href='index.php?a=gpress&t=", sfSay($topic), "'>$topic</a> ";
}
echo "</span>";
}
}

function p_img($string){
// Some say you can't make a BBCode parser, but let's see what comes of this.
$code = preg_replace('(\[img\](.*)\[\/\])', "<img src='images/$1'>", "$string");
return $code;
}