<?php

include("session.php");

class initialize
{
    var $url;
    var $home_dir;

    function initialize($info) {
        $this->url      = $info['url'];
        $this->home_dir = $info['path'];
    }
}

$initialize = new initialize($info);