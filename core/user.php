<?php

class User
{
    public $info;
    public $stats;
    public $time;

    /**
     * Class constructor
     */
    function __construct() {
        global $database, $session;

        $items = array(':uid' => $session->username);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->select("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $this->time  = $database->select("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items)->fetchObject();
    }
}