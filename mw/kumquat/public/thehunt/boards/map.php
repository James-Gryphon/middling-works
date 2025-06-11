<?php
// Kludgy beyond belief, but works. Let's try to figure out a better way to do this later...
$prefs = parse_ini_file("../../../settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
session_name(sessionname);
session_save_path(sessiondir);
session_start();
$im = imagecreatefrompng($_SESSION['instructions']['board_dir']);
/*
$red = imagecolorallocate($im, 255, 0, 0);
imagefill($im, 0, 0, $red);
*/

/* Another file sets the 'instructions' in the session, and then this carries them out.
Currently the only two things that are supported are choosing colors, and painting specific spots those colors.
It would probably not be a bad idea if there were options for text, or for drawing lines or shapes.*/
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
if(!empty($_SESSION['instructions']['color_step'])){
    foreach($_SESSION['instructions']['color_step'] as $step)
    {
        $color = imagecolorallocate($im, $step['r'], $step['g'], $step['b']);
        imagefill($im, $step['x'], $step['y'], $color);
    }
}
$border_size = $_SESSION['instructions']['board_image_array']['circle_width'] + 2;

if(!empty($_SESSION['instructions']['circle_step'])){
    foreach($_SESSION['instructions']['circle_step'] as $key => $step)
    {
        /*
        $color = imagecolorallocate($im, $step['r'], $step['g'], $step['b']);
        imagefilledellipse($im, $step['x'], $step['y'], $_SESSION['instructions']['board_image_array']['circle_width'], $_SESSION['instructions']['board_image_array']['circle_width'], $color);
        $color = imagecolorallocate($im, 255, 255, 255);
        imageellipse($im, $step['x'], $step['y'], $_SESSION['instructions']['board_image_array']['circle_width'], $_SESSION['instructions']['board_image_array']['circle_width'], $color);
        $color = imagecolorallocate($im, 0, 0, 0);
        imageellipse($im, $step['x'], $step['y'], $border_size, $border_size, $color);
        */
        $basecolor = imagecolorat($im, $step['x'], $step['y']);
        $basecolor = imagecolorsforindex($im, $basecolor);
        if($basecolor['red'] == $basecolor['green'] && $basecolor['red'] == $basecolor['blue'])
        {
            if($basecolor['red'] == 255)
            { 
               $newcolor = $black;
            }
            else 
            {
               $newcolor = $white;
            }
        }
        else
        { // color luminance finding
            $colors = array();
            foreach($basecolor as $bcsRGB)
            {
                $bcsRGB = $bcsRGB/255;
                if($bcsRGB <= 0.03928){ $colors[] = $bcsRGB/12.92;}
                else 
                {
                    $colors[] = pow((($bcsRGB+0.055)/1.055),2.4);
                }
            }
            $l = 0.2126 * $colors[0] + 0.7152 * $colors[1] + 0.0722 * $colors[2];
            if($l <= .49)
            {
                $newcolor = $white;
            }
            else 
            { 
                $newcolor = $black;
            }
        }
        $d = $border_size;
        $e = round($border_size / 2);
        $q = $border_size + ($e*2);

        switch($key):
            case 0: imageline($im, $step['x']-$border_size+$d, $step['y']-$border_size+$e, $step['x']+$border_size-$d, $step['y']+$border_size-$e, $newcolor); break; // vert
            case 1: imageline($im, $step['x']-$border_size+$e, $step['y']-$border_size+$d, $step['x']+$border_size-$e, $step['y']+$border_size-$d, $newcolor); break; // horz
            case 2: imageline($im, $step['x']-$border_size+$e, $step['y']-$border_size+$e, $step['x']+$border_size-$e, $step['y']+$border_size-$e, $newcolor); break; // top-left diag
            case 3: imageline($im, $step['x']-$border_size+$e, $step['y']+$border_size-$e, $step['x']+$border_size-$e, $step['y']-$border_size+$e, $newcolor); break; // bottom-left diag
        endswitch;
        }
}
unset($_SESSION['instructions']);
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
?>