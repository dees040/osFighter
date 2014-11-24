<?php
@session_start();
$text = rand(1000,9999);
$_SESSION["vercode"] = $text;
$height = 25;
$width = 50;

$image_p = imagecreate($width, $height);
$white = imagecolorallocate($image_p, 255, 255, 255);
$black = imagecolorallocate($image_p, 0, 0, 0);
$font_size = 14;

imagestring($image_p, $font_size, 5, 5, $text, $black);
$pixel_color = imagecolorallocate($image, 0,0,255);
for($i=0;$i<1000;$i++) {
    imagesetpixel($image_p,rand()%200,rand()%50,$pixel_color);
}
header('Content-Type: image/jpeg');
imagejpeg($image_p);