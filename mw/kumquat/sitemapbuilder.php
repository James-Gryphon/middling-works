<?php
/* A script to build a sitemap 

The gist of it is this:
1. Look at the links page in files.
This is the central links page and determines what sites are valid.
Get the array from it.

2. For each directory listed in the array above:
Find the 'default page'. This is usually in main.php.
b. Look in the links page in this directory.
This determines what actions are valid.
Get the array from it.
From the array, save the named ones, and that aren't the default. Dump others.
Do an info check (with mtimes) on all these files.
Build an array (or object; whatever) with the resulting information.

3. Build the xml file, using the above.
*/

$path = "files-kumquat";
include("$path/files/links.php");
$main_actions = $button_array;
$complete_array = [];
foreach($main_actions as $action => $action_string)
{
    $file = file_get_contents("$path/$action/main.php");
    $first = strpos($file, "\"");
    ++$first;
    $second = strpos($file, "\"", $first);
    $length = $second - $first;
    $default = substr($file, $first, $length);
    $default_time = filemtime("$path/$action/main.php");
    // make this check if the file exists first
    if(file_exists("$path/{$action}/temps/{$default}_tpl.php"))
    {
        $default_time2 = filemtime("$path/$action/temps/{$default}_tpl.php");
        if($default_time2 > $default_time){$default_time = $default_time2;}    
    }
    // section B
    include("$path/$action/links.php");
    foreach($loc_button_array as $key => $value)
    {
        if(empty($value) || $key === $default){unset($loc_button_array[$key]);}
        else 
        {
            $loc_button_array[$key] = filemtime("$path/$action/files/$key.php");
            if(file_exists("$path/{$action}/temps/{$key}_tpl.php"))
            {
                $new_time = filemtime("$path/$action/temps/{$key}_tpl.php");
                if($new_time > $loc_button_array[$key]){$loc_button_array[$key] = $new_time;}    
            }
        }
    }
    $complete_array[$action] = $loc_button_array;
    $complete_array[$action]['default'] = $default_time;
}

// Now to build the xml file

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n \n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

foreach($complete_array as $key => $value)
{
    foreach($value as $value_key => $subvalue)
    {
        $xml .= "\n<url>\n<loc>\nhttps://www.middlingworks.com";
        if($key === "home" && $value_key === "default"){}
        else {$xml .= "/index.php";}
        if($key !== "home"){$xml .= "?s={$key}&amp;";}
        if($key === "home"){ $xml .= "?";}
        if($value_key !== "default"){$xml .= "a={$value_key}";}
        $xml .= "\n</loc>\n</url>";
    }
}
$xml .= "\n</urlset>";
$fp = fopen("public/sitemap.xml", "w");
fwrite($fp, $xml);
fclose($fp);

?>