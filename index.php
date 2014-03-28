<?php
    $link = $_GET['REQUEST_URI'];

    $info = array(
        'url'  => $link,
        'path' => getcwd()
    );

    include 'core/initialize.php';