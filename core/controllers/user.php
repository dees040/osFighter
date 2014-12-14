<?php

class User
{
    public $info;
    public $stats;
    public $time;

    public $in_jail;
    public $in_family;
    public $family;
    public $id;
    public $profile_pic;
    public $in_air;

    /**
     * Class constructor
     */
    public function __construct()
    {
        global $database, $session;

        if (!isset($session->userinfo->id) || $session->userinfo->id == NULL) return false;

        $items = array(':uid' => $session->userinfo->id);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->query("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $this->time  = $database->query("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items)->fetchObject();

        $this->init();

        return true;
    }

    private function init()
    {
        $this->check();
        $this->setFamily();
        $this->in_jail = $this->checkJail();
        $this->in_air = $this->checkAir();
        $this->id = $this->info->id;
        $this->profile_pic = $this->info->profile_picture;
    }

    private function check()
    {
        if ($this->stats->rank_process >= 100) {
            $this->updateRank();
        }
    }

    private function setFamily()
    {
        global $database;

        $items = array(":fid" => $this->stats->fid);
        $family = $database->query("SELECT * FROM ".TBL_FAMILY." WHERE id = :fid", $items)->fetchObject();

        if ($family == false) {
            $this->family->id = 0;
            $this->family->name = "-";
            $this->in_family = false;
        } else {
            $this->family = $family;
            $this->in_family = true;
        }

    }

    private function checkJail()
    {
        $jail_time = (int)$this->time->jail;

        if ($jail_time == 0)     return false;
        if ($jail_time < time()) return false;

        return true;
    }

    private function checkAir()
    {
        $air_time = (int)$this->time->fly_time;

        if ($air_time == 0)     return false;
        if ($air_time < time()) return false;

        return true;
    }

    public function updateRank()
    {
        global $database, $session;

        $ranks = $database->getRanks();
        $rank = 1 + (int)$this->stats->rank;
        $rank_process = 1;

        if ($rank >= count($ranks)) {
            $rank = (int)$this->stats->rank;
            $rank_process = 100;
        }

        $items = array(':rank' => $rank, ':process' => $rank_process, ':name' => $session->userinfo->id);
        $database->query("UPDATE ".TBL_INFO." SET rank = :rank, rank_process = :process WHERE uid = :name", $items);
        $this->stats->rank = $rank;
        $this->stats->rank_process = $rank_process;
    }

    public function getInbox($unread = false)
    {
        global $database;

        return $database
            ->query(
                "SELECT * FROM ".TBL_MESSAGE." WHERE to_id = :id ORDER BY date DESC",
                array(':id' => $this->id))
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOutbox()
    {
        global $database;

        return $database
            ->query(
                "SELECT * FROM ".TBL_MESSAGE." WHERE from_id = :id AND from_status = 0 ORDER BY date DESC",
                array(':id' => $this->id))
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUnreadInbox()
    {
        global $database;

        $count = $database
            ->query(
                "SELECT * FROM ".TBL_MESSAGE." WHERE to_id = :id AND status = 0 ORDER BY date DESC",
                array(':id' => $this->id))->rowCount();

        return ($count == 0) ? '' : " (".$count.")";
    }

    public function moneyEarnedFromHo()
    {
        global $settings, $database;

        if ($this->time->pimp_money == 0) {
            $items = array(':time' => time(), ':uid' => $this->id);
            $database->query("UPDATE ".TBL_TIME." SET pimp_money = :time WHERE uid = :uid", $items);
        }

        $time = floor((time() - $this->time->pimp_money) / 3600);

        if ($time == 0) {
            $money = 0;
        } else {
            $money = $time * ($this->stats->ho_glass * 60);
        }

        return $money;
    }

    public function cars()
    {
        global $database;

        $items = array(':uid' => $this->id);
        return $database
            ->query("SELECT * FROM ".TBL_GARAGE." WHERE uid = :uid", $items)
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function setShoutbox($sid)
    {
        global $database;

        $items = array(':sid' => $sid, ':uid' => $this->id);
        $database->query(
            "UPDATE ".TBL_INFO." SET last_shoutbox = :sid WHERE uid = :uid",
            $items
        );
    }

    public function newShoutBoxMessage()
    {
        global $database;

        $lastShoutBox = $database->query("SELECT id FROM ".TBL_SHOUTBOX." ORDER BY date DESC LIMIT 1")->fetchObject();

        if ($lastShoutBox->id != $this->stats->last_shoutbox){
            return '<span style="color: red; font-size: 10px; vertical-align: top;">&nbsp;&nbsp;NEW</span>';
        }

        return '';
    }
}