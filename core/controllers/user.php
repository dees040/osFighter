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
    public $in_air;

    /**
     * Class constructor
     */
    public function __construct()
    {
        global $database, $session;

        if ($session->userinfo['id'] == null) {
            return false;
        }

        $items = array(':uid' => $session->userinfo['id']);
        $this->info  = (object)$session->userinfo;
        $this->stats = $database->query("SELECT * FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $this->time  = $database->query("SELECT * FROM ".TBL_TIME." WHERE uid = :uid", $items)->fetchObject();

        $this->init();
    }

    private function init()
    {
        $this->check();
        $this->setFamily();
        $this->in_jail = $this->checkJail();
        $this->in_air = $this->checkAir();
        $this->id = $this->info->id;
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
            $this->family->id = $family->id;
            $this->family->name = $family->name;
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

        $items = array(':rank' => $rank, ':process' => $rank_process, ':name' => $session->userinfo['id']);
        $database->query("UPDATE ".TBL_INFO." SET rank = :rank, rank_process = :process WHERE uid = :name", $items);
        $this->stats->rank = $rank;
        $this->stats->rank_process = $rank_process;
    }

    public function getInbox()
    {
        global $database;

        return $database
            ->query(
                "SELECT * FROM ".TBL_MESSAGE." WHERE to_id = :id",
                array(':id' => $this->id))
            ->fetchAll(PDO::FETCH_OBJ);
    }

    public function deposit($value)
    {
        global $database, $error;

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater");
        }

        if ($value > $this->stats->money) {
            return $error->errorSmall("You don't have that amount to deposit");
        }

        $this->stats->money = $this->stats->money - $value;
        $this->stats->bank  = $this->stats->bank + $value;
        $items = array(':cash' => $this->stats->money, ':bank' => $this->stats->bank, ':id' => $this->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, bank = :bank WHERE uid = :id", $items);

        return $error->succesSmall("You have deposit ".$value." with success.");
    }

    public function withdraw($value)
    {
        global $database, $error;

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater");
        }

        if ($value > $this->stats->bank) {
            return $error->errorSmall("You don't have that amount to withdraw");
        }

        $this->stats->bank  = $this->stats->bank - $value;
        $this->stats->money  = $this->stats->money + $value;
        $items = array(':cash' => $this->stats->money, ':bank' => $this->stats->bank, ':id' => $this->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, bank = :bank WHERE uid = :id", $items);

        return $error->succesSmall("You have withdraw ".$value." with success.");
    }

    public function healInHospital()
    {
        global $database;

        $cost = $this->stats->health * 100;

        if ($cost > $this->stats->money) {
            return false;
        }

        $this->stats->money = $this->stats->money - $cost;
        $this->stats->health = 100;
        $items = array(':money' => $this->stats->money, ':health' => $this->stats->health, ':id' => $this->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, health = :health WHERE uid = :id", $items);

        return true;
    }

    public function buyFree($uid)
    {
        global $database, $error;

        $userInJail = $database
            ->query(
                "SELECT users.id, users.username, users_time.jail FROM ".TBL_USERS." INNER JOIN users_time ON users_time.uid = users.id WHERE users.id = :id",
                array(':id' => $uid)
            )
            ->fetchObject();

        if ($userInJail->jail < time()) {
            return $error->errorSmall($userInJail->username." is already out of jail.");
        }

        $cost = ($userInJail->jail - time()) * 180;

        if ($cost > $this->stats->money) {
            return $error->errorSmall("You don't have enough cash to pay ".$userInJail->username." free");
        }

        $this->stats->money = $this->stats->money - $cost;
        $items = array(':money' => $this->stats->money, ':id' => $this->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :id", $items);
        $items = array(':time' => time(), ':id' => $userInJail->id);
        $database->query("UPDATE ".TBL_TIME." SET jail = :time WHERE uid = :id", $items);

        return $error->succesSmall("You have bought ".$userInJail->username." free!");
    }

    public function buyFromShop($info)
    {
        global $database, $error, $settings;

        $sid = 0;
        $amount = $_POST['number'];

        foreach($info as $key => $item)
            if ($item == "Buy!")
                $sid = $key;

        if ($amount < 1) {
            return $error->errorSmall("This product has a minimum of 1");
        }

        $productInfo = $database->query(
            "SELECT name, price, power, max_amount FROM ".TBL_SHOP_ITEMS." WHERE id = :sid",
            array(':sid' => $sid)
        )->fetchObject();
        $cost = $productInfo->price * $amount;

        if ($cost > $this->stats->money) {
            return $error->errorSmall("You don't have enough cash to buy this item(s).");
        }

        $currentAmount = $database->query(
            "SELECT amount FROM ".TBL_USERS_ITEMS." WHERE uid = :id AND sid = :sid",
            array(':id' => $this->id, ':sid' => $sid)
        );

        if ($currentAmount->rowCount() == 1) {
            $currentAmount = $currentAmount->fetchObject()->amount;
        } else {
            $currentAmount = 0;
        }

        if ($productInfo->max_amount != 0) {
            if (($currentAmount + $amount) > $productInfo->max_amount) {
                return $error->errorSmall("You can't buy more than the maximum amount.");
            }
        }

        $this->stats->money = $this->stats->money - $cost;
        $this->stats->power = $this->stats->power + ($amount * $productInfo->power);
        $items = array(':uid' => $this->id, ':money' => $this->stats->money, ':power' => $this->stats->power);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, power = :power WHERE uid = :uid", $items);

        $dataExists = $database->query(
            "SELECT sid FROM ".TBL_USERS_ITEMS." WHERE uid = :uid AND sid = :sid",
            array(':uid' => $this->id, ':sid' => $sid)
        )->rowCount();

        $items = array(':uid' => $this->id, ':amount' => ($currentAmount + $amount), ':sid' => $sid);
        if ($dataExists == 1) {
            $database->query("UPDATE " . TBL_USERS_ITEMS . " SET amount = :amount WHERE uid = :uid AND sid = :sid", $items);
        } else {
            $database->query("INSERT INTO ".TBL_USERS_ITEMS." SET uid = :uid, sid = :sid, amount = :amount", $items);
        }

        return $error->succesSmall("You have bought ".$amount." ".$productInfo->name."(s) for ".$settings->currencySymbol().$cost." with succes!");
    }

    public function buyTicket($toId)
    {
        global $database, $error, $settings;

        if ($toId == $this->stats->city) {
            return $error->errorSmall("You're in this city already!");
        }

        $cost = $database->getConfigs()['FLY_TICKET_COST'];

        if ($cost > $this->stats->money) {
            return $error->errorSmall("You don't have enough money to buy a ticket!");
        }

        $this->stats->money = $this->stats->money - $cost;
        $items = array(':uid' => $this->id, ':cash' => $this->stats->money, ':city' => $toId);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, city = :city WHERE uid = :uid", $items);
        $this->time->fly_time = time() + 60;
        $items = array(':uid' => $this->id, ':fly' => $this->time->fly_time);
        $database->query("UPDATE ".TBL_TIME." SET fly_time = :fly WHERE uid = :uid", $items);

        return $error->succesSmall("You're traveling to ".unserialize($settings->config['CITIES'])[$toId]." now");
    }
}