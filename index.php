<?php
    $link = explode('/', $_SERVER['REQUEST_URI']);

    $info = array(
        'url'  => $link,
        'path' => getcwd()
    );

    include 'core/initialize.php';