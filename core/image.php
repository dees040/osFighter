<?php
@session_start();

if(md5($_SESSION['botcheck']) == $_GET['c']) {
    $image  = imagecreatefromgif("../files/images/captcha/img_g.gif");
} else {
    $image  = imagecreatefromgif("../files/images/captcha/img_n.gif");
}

header('Content-type: image/png');
imagepng($image);
