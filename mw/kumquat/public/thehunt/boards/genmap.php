<?php
// Generates a complete map of its own, without relying on the background image
// Kludgy beyond belief, but works. Let's try to figure out a better way to do this later...
$prefs = parse_ini_file("../../../settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
session_name(sessionname);
session_save_path(sessiondir);
session_start();
$im = imagecreatefrompng($_SESSION['instructions']['board_dir']);
$red = imagecolorexact($im, 155, 155, 155);
imagefilledrectangle($im, 0, 0, 999, 999, $red);

/* Another file sets the 'instructions' in the session, and then this carries them out.
Currently the only two things that are supported are choosing colors, and painting specific spots those colors.
It would probably not be a bad idea if there were options for text, or for drawing lines or shapes.*/

// thanks to Brent on the PHP manual, imagesetbrush
$brush_size = 3;
$one_way_brush = imagecreatetruecolor(1, 1);
$line_brush = imagecreatetruecolor(2, 2);
$one_way_brush_color = imagecolorallocate($one_way_brush, 0, 128, 255);
$line_brush_color = imagecolorallocate($line_brush, 255,255,192);
imagefill($one_way_brush,0,0,$one_way_brush_color);
imagefill($line_brush,0,0,$line_brush_color);
imagesetbrush($im, $line_brush);

if(!empty($_SESSION['instructions']['line'])){
    foreach($_SESSION['instructions']['line'] as $step)
    {
        if($step['one_way'] == 0)
        {
            imagesetbrush($im, $line_brush);
            imageline($im, $step['x'], $step['y'], $step['x2'], $step['y2'], IMG_COLOR_BRUSHED);
        }
        else 
        { 
            imagesetbrush($im, $one_way_brush);
            imageline($im, $step['x'], $step['y'], $step['x2'], $step['y2'], IMG_COLOR_BRUSHED);
        }
    }
}
imagedestroy($one_way_brush);
imagedestroy($line_brush);
//    $font = "/usr/share/fonts/truetype/msttcorefonts/Andale_Mono.ttf";
    $font = "/usr/share/fonts/dejavu/DejaVuSansMono.ttf";

if(!empty($_SESSION['instructions']['square_step'])){
    foreach($_SESSION['instructions']['square_step'] as $step)
    {
        $color = imagecolorexact($im, $step['r'], $step['g'], $step['b']);
        if($color == -1){$color = imagecolorallocate($im, $step['r'], $step['g'], $step['b']);}
        imagefilledrectangle($im, $step['x']-4, $step['y']-4, $step['x']+4, $step['y']+4, $color);
        $color = imagecolorexact($im, 0, 0, 0);
        imagefttext($im, 8, 0, $step['x']-4, $step['y']-8, $color, $font, $step['name']);
    }
}

unset($_SESSION['instructions']);
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
?>