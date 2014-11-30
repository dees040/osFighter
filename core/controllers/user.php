<?php

class User
{
    public $info;
    public $stats;
    public $time;

    public $in_jail;
    public $in_family;
    public  $family;
    public $id;
    public $in_air;

    /**
     * Class constructor
     */
    public function __construct()
    {
        global $database, $session;

        if ($session->userinfo->id == null) {
            return false;
        }

        $items = array(':uid' => $session->userinfo->id);
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

        return $error->succesSmall("You have bought ".$amount." ".$productInfo->name."(s) for ".$settings->currencySymbol().$settings->createFormat($cost)." with succes!");
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

    public function buyHouse($id)
    {
        global $database, $error, $settings;

        $house = $database->query("SELECT * FROM ".TBL_HOUSE_ITEMS." WHERE id = :id", array(':id' => $id));

        if ($house->rowCount() == 0) {
            return $error->errorSmall("This house doesn't exists.");
        }

        $house = $house->fetchObject();

        if ($house->price > $this->stats->money) {
            return $error->errorSmall("You don't have enough money to buy this property.");
        }

        $oldHouseId = $this->stats->house;
        if ($this->stats->house != 0) {
            $oldHouse = $database->query("SELECT price FROM ".TBL_HOUSE_ITEMS." WHERE id = :id", array(':id' => $this->stats->house))->fetchObject();

            $moneyBack = $oldHouse->price * 0.75;
            $this->stats->money = $this->stats->money + $moneyBack;
        }

        $this->stats->house = $id;
        $this->stats->money = $this->stats->money - $house->price;
        $items = array(':money' => $this->stats->money, ':house' => $id, ':uid' => $this->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, house = :house WHERE uid = :uid", $items);

        if ($oldHouseId == 0) {
            return $error->succesSmall("You bought your new house for " . $settings->currencySymbol() . $settings->createFormat($house->price));
        } else if ($id == 0) {
            return $error->succesSmall("You sold your old house for ".$settings->currencySymbol() . $settings->createFormat($moneyBack));
        } else {
            return $error->succesSmall("You bought your new house for " . $settings->currencySymbol() . $settings->createFormat($house->price). " and you sold your old house for ".$settings->currencySymbol() . $settings->createFormat($moneyBack));
        }
    }

    public function sendMessage($to, $subject, $message)
    {
        global $database, $error;

        $userInfo = $database->getUserInfo($to);

        if ($userInfo == null) {
            return $error->errorSmall("User: ". $to ." doesn't exists.");
        }

        if (!$subject || empty($subject)) {
            return $error->errorSmall("Subject can not be empty.");
        }

        $items = array(':from_id' => $this->id, ':to_id' => $userInfo->id, ':date' => time(), ':sub' => $subject, ':mess' => $message);
        $database->query(
            "INSERT INTO ".TBL_MESSAGE." SET from_id = :from_id, to_id = :to_id, date = :date, subject = :sub, content = :mess",
            $items
        );

        return $error->succesSmall("Message has been sent to ".$userInfo->username);
    }

    public function deleteMessage($messages, $type = false)
    {
        global $database, $error;

        $i = 0;
        foreach($messages as $message) {
            if ($type === false) {
                $from = $database->query("SELECT to_id FROM ".TBL_MESSAGE." WHERE id = :id", array(':id' => $message[$i]))->fetchObject();
                if ($from->to_id == $this->id) {
                    $items = array(':id' => $message[$i], ':to_id' => $this->id);
                    $database->query("DELETE FROM " . TBL_MESSAGE . " WHERE id = :id AND to_id = :to_id", $items);
                } else {
                    return $error->errorSmall("You are not allowed to delete this message!");
                }
            } else {
                $items = array(':id' => $message[$i], ':status' => 1);
                $database->query("UPDATE " . TBL_MESSAGE . " SET from_status = :status  WHERE id = :id", $items);
            }
            $i++;
        }

        return $error->succesSmall("All selected messages have been deleted!");
    }

    public function addToShoutBox($message)
    {
        global $database, $error;

        if (strlen(strip_tags($message)) > 300) {
            return $error->errorSmall("The message can not ben more then 300 characters.");
        }

        $lastUser = $database->query("SELECT uid FROM ".TBL_SHOUTBOX." ORDER BY date DESC LIMIT 1")->fetchObject();

        if ($lastUser->uid == $this->id) {
            return $error->errorSmall("You need to wait till somebody else added a message to the shoutbox!");
        }

        $items = array(':uid' => $this->id, ':message' => $message, ':date' => time());
        $database->query("INSERT INTO ".TBL_SHOUTBOX." SET uid = :uid, message = :message, date = :date", $items);

        return $error->succesSmall("Your message has been added to the shoutbox!");
    }

    public function attackPlayer($username, $bullets)
    {
        global $database, $error;

        if ($username == "demo") {
            return $error->errorSmall("You can't attack the demo account.");
        }

        if ($username == $this->info->username) {
            return $error->errorSmall("You can't attack yourself.");
        }

        if ($bullets > $this->stats->bullets || empty($bullets)) {
            return $error->errorSmall("You don't have ".$bullets." bullets.");
        }

        if ($this->stats->protection > time()) {
            return $error->errorSmall("You're under protection, this means you can't attack players.");
        }

        $userToAttackId = $database->getUserInfo($username)->id;

        if ($userToAttackId == NULL) {
            return $error->errorSmall("User \"".$username."\" does not exists.");
        }

        $userToAttack = $database->query("SELECT fid, city, protection FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $userToAttackId))->fetchObject();

        if ($userToAttack->fid == $this->stats->fid) {
            return $error->errorSmall("You can't attack players who are in the same family.");
        }

        if ($this->stats->city != $userToAttack->city) {
            return $error->errorSmall("You are not in the same city as ".$username);
        }

        if ($userToAttack->protection > time()) {
            return $error->errorSmall("This user is under protection till ".date("Y-m-d H:i", $userToAttack->protection).".");
        }

        return $error->succesSmall("Success");
    }
}