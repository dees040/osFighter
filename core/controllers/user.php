<?php

class User
{
    public $info;
    public $stats;
    public $time;

    public $in_jail;
    public $in_family;
    public $family;

    /**
     * Class constructor
     */
    public function __construct() {
        global $database, $session;

        $items = array(':uid' => $session->userinfo['id']);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->select("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $this->time  = $database->select("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items)->fetchObject();

        $this->init();
    }

    private function init() {
        $this->check();
        $this->setFamily();
        $this->in_jail = $this->checkJail();
    }

    private function check() {
        if ($this->stats->rank_process >= 100) {
            $this->updateRank();
        }
    }

    private function setFamily() {
        global $database;

        $items = array(":fid" => $this->stats->fid);
        $family = $database->select("SELECT * FROM ".TBL_FAMILY." WHERE id = :fid", $items)->fetchObject();

        if ($family == false) {
            $this->family->id = 0;
            $this->family->name = "-";
            $this->in_family = false;
        } else {
            $this->family->id = $family->id;
            $this->family->name = $family->name;
            $this->in_family = true;
        }

    }

    private function checkJail() {
        $jail_time = (int)$this->time->jail;

        if ($jail_time == 0)     return false;
        if ($jail_time < time()) return false;

        return true;
    }

    public function updateRank() {
        global $database, $session;

        $ranks = $database->getRanks();
        $rank = 1 + (int)$this->stats->rank;
        $rank_process = 1;

        if ($rank >= count($ranks)) {
            $rank = (int)$this->stats->rank;
            $rank_process = 100;
        }

        $items = array(':rank' => $rank, ':process' => $rank_process, ':name' => $session->userinfo['id']);
        $database->update("UPDATE ".TBL_INFO." SET rank = :rank, rank_process = :process WHERE uid = :name", $items);
        $this->stats->rank = $rank;
        $this->stats->rank_process = $rank_process;
    }
}