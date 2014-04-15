<?php

class User
{
    public $info;
    public $stats;
    public $time;

    public $in_jail;

    /**
     * Class constructor
     */
    public function __construct() {
        global $database, $session;

        $items = array(':uid' => $session->username);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->select("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $this->time  = $database->select("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items)->fetchObject();

        $this->init();
    }

    private function init() {
        $this->in_jail = $this->checkJail();
    }

    private function checkJail() {
        $jail_time = (int)$this->time->jail;

        if ($jail_time == 0)     return false;
        if ($jail_time < time()) return false;

        return true;
    }
}