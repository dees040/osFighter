<?php
    $link = isset($_GET['REQUEST_URI']) ? $_GET['REQUEST_URI'] : "home";

    $init = array(
        'url'  => $link,
        'base' => str_replace($link, "", "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),
        'path' => str_replace('\\', '/', getcwd())
    );

    include 'core/initialize.php';