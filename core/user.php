<?php

class User
{
    var $info;
    var $stats;
    var $time;

    function User() {
        global $database, $session;

        $items = array(':uid' => $session->username);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->select("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items);
        $this->time  = $database->select("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items);

    }
}