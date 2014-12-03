<?php

class UserActions
{

    public function deposit($value)
    {
        global $user, $database, $error, $settings;

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater");
        }

        if ($value > $user->stats->money) {
            return $error->errorSmall("You don't have that amount to deposit");
        }

        $user->stats->money = $user->stats->money - $value;
        $user->stats->bank  = $user->stats->bank + $value;
        $items = array(':cash' => $user->stats->money, ':bank' => $user->stats->bank, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, bank = :bank WHERE uid = :id", $items);

        return $error->succesSmall("You have deposit ".$settings->currencySymbol().$settings->createFormat($value)." with success.");
    }

    public function withdraw($value)
    {
        global $user, $database, $error, $settings;

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater");
        }

        if ($value > $user->stats->bank) {
            return $error->errorSmall("You don't have that amount to withdraw");
        }

        $user->stats->bank  = $user->stats->bank - $value;
        $user->stats->money  = $user->stats->money + $value;
        $items = array(':cash' => $user->stats->money, ':bank' => $user->stats->bank, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, bank = :bank WHERE uid = :id", $items);

        return $error->succesSmall("You have withdraw ".$settings->currencySymbol().$settings->createFormat($value)." with success.");
    }

    public function healInHospital()
    {
        global $user, $database, $error, $settings;

        $cost = $user->stats->health * 100;

        if ($cost > $user->stats->money) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($cost)." in cash");
        }

        $user->stats->money = $user->stats->money - $cost;
        $user->stats->health = 100;
        $items = array(':money' => $user->stats->money, ':health' => $user->stats->health, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, health = :health WHERE uid = :id", $items);

        return $error->succesSmall("You health yourself!");
    }

    public function buyFree($uid)
    {
        global $user, $database, $error, $settings;

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

        if ($cost > $user->stats->money) {
            return $error->errorSmall("You don't have enough cash to buy ".$userInJail->username." free");
        }

        $user->stats->money = $user->stats->money - $cost;
        $items = array(':money' => $user->stats->money, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :id", $items);
        $items = array(':time' => time(), ':id' => $userInJail->id);
        $database->query("UPDATE ".TBL_TIME." SET jail = :time WHERE uid = :id", $items);

        $settings->sendMessage("Free from jail", "Dear ".$userInJail->username.",<br><br>".$user->info->username." has bought you free from jail. For the price of ".$settings->currencySymbol().$settings->createFormat($cost).".", $userInJail->id);

        return $error->succesSmall("You have bought ".$userInJail->username." free for ".$settings->currencySymbol().$settings->createFormat($cost)."!");
    }

    public function buyFromShop($info)
    {
        global $user, $database, $error, $settings;

        $sid = 0;
        $amount = $_POST['number_shop'];

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

        if ($cost > $user->stats->money) {
            return $error->errorSmall("You don't have enough cash to buy this item(s).");
        }

        $currentAmount = $database->query(
            "SELECT amount FROM ".TBL_USERS_ITEMS." WHERE uid = :id AND sid = :sid",
            array(':id' => $user->id, ':sid' => $sid)
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

        $user->stats->money = $user->stats->money - $cost;
        $user->stats->power = $user->stats->power + ($amount * $productInfo->power);
        $items = array(':uid' => $user->id, ':money' => $user->stats->money, ':power' => $user->stats->power);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, power = :power WHERE uid = :uid", $items);

        $dataExists = $database->query(
            "SELECT sid FROM ".TBL_USERS_ITEMS." WHERE uid = :uid AND sid = :sid",
            array(':uid' => $user->id, ':sid' => $sid)
        )->rowCount();

        $items = array(':uid' => $user->id, ':amount' => ($currentAmount + $amount), ':sid' => $sid);
        if ($dataExists == 1) {
            $database->query("UPDATE " . TBL_USERS_ITEMS . " SET amount = :amount WHERE uid = :uid AND sid = :sid", $items);
        } else {
            $database->query("INSERT INTO ".TBL_USERS_ITEMS." SET uid = :uid, sid = :sid, amount = :amount", $items);
        }

        return $error->succesSmall("You have bought ".$amount." ".$productInfo->name."(s) for ".$settings->currencySymbol().$settings->createFormat($cost)." with succes!");
    }

    public function buyTicket($toId)
    {
        global $user, $database, $error, $settings;

        if ($toId == $user->stats->city) {
            return $error->errorSmall("You're in this city already!");
        }

        $cost = $database->getConfigs()['FLY_TICKET_COST'];

        if ($cost > $user->stats->money) {
            return $error->errorSmall("You don't have enough money to buy a ticket!");
        }

        $user->stats->money = $user->stats->money - $cost;
        $items = array(':uid' => $user->id, ':cash' => $user->stats->money, ':city' => $toId);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash, city = :city WHERE uid = :uid", $items);
        $user->time->fly_time = time() + 60;
        $items = array(':uid' => $user->id, ':fly' => $user->time->fly_time);
        $database->query("UPDATE ".TBL_TIME." SET fly_time = :fly WHERE uid = :uid", $items);

        return $error->succesSmall("You're traveling to ".unserialize($settings->config['CITIES'])[$toId]." now");
    }

    public function buyHouse($id)
    {
        global $user, $database, $error, $settings;

        $house = $database->query("SELECT * FROM ".TBL_HOUSE_ITEMS." WHERE id = :id", array(':id' => $id));

        if ($house->rowCount() == 0) {
            return $error->errorSmall("This house doesn't exists.");
        }

        $house = $house->fetchObject();

        if ($house->price > $user->stats->money) {
            return $error->errorSmall("You don't have enough money to buy this property.");
        }

        $oldHouseId = $user->stats->house;
        if ($user->stats->house != 0) {
            $oldHouse = $database->query("SELECT price FROM ".TBL_HOUSE_ITEMS." WHERE id = :id", array(':id' => $user->stats->house))->fetchObject();

            $moneyBack = $oldHouse->price * 0.75;
            $user->stats->money = $user->stats->money + $moneyBack;
        }

        $user->stats->house = $id;
        $user->stats->money = $user->stats->money - $house->price;
        $items = array(':money' => $user->stats->money, ':house' => $id, ':uid' => $user->id);
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
        global $user, $database, $error;

        $userInfo = $database->getUserInfo($to);

        if ($userInfo == null) {
            return $error->errorSmall("User: ". $to ." doesn't exists.");
        }

        if (!$subject || empty($subject)) {
            return $error->errorSmall("Subject can not be empty.");
        }

        if (strlen($subject) < 3) {
            return $error->errorSmall("Subject must contain 3 characters.");
        }

        if (!ctype_alnum($subject)) {// you may send ":"
            //return $error->errorSmall("The subject may online contain letters and digits");
        }

        $items = array(':from_id' => $user->id, ':to_id' => $userInfo->id, ':date' => time(), ':sub' => $subject, ':mess' => $message);
        $database->query(
            "INSERT INTO ".TBL_MESSAGE." SET from_id = :from_id, to_id = :to_id, date = :date, subject = :sub, content = :mess",
            $items
        );

        return $error->succesSmall("Message has been sent to ".$userInfo->username);
    }

    public function deleteMessage($messages, $type = false)
    {
        global $user, $database, $error;

        foreach($messages['messages'] as $message) {
            if ($type === false) {
                $from = $database->query("SELECT to_id FROM ".TBL_MESSAGE." WHERE id = :id", array(':id' => $message))->fetchObject();
                if ($from->to_id == $user->id) {
                    $items = array(':id' => $message, ':to_id' => $user->id);
                    $database->query("DELETE FROM " . TBL_MESSAGE . " WHERE id = :id AND to_id = :to_id", $items);
                } else {
                    return $error->errorSmall("You are not allowed to delete this message!");
                }
            } else {
                $items = array(':id' => $message, ':status' => 1);
                $database->query("UPDATE " . TBL_MESSAGE . " SET from_status = :status  WHERE id = :id", $items);
            }
        }

        return $error->succesSmall("All selected messages have been deleted!");
    }

    public function setMessageStatus()
    {
        global $database, $user;

        $message = $database->query(
            "SELECT * FROM " . TBL_MESSAGE . " WHERE id = :id AND (from_id = :uid OR to_id = :uid)"
            , array(':id' => $_GET['message_id'], ':uid' => $user->id)
        )->fetchObject();

        if ($message->status == 0 && $message->to_id == $user->id) {
            $items = array(':status' => 1, ':id' => $message->id);
            $database->query("UPDATE ".TBL_MESSAGE." SET status = :status WHERE id = :id", $items);
        }

        return 'Message set.';
    }

    public function addToShoutBox($message)
    {
        global $user, $database, $error;

        if (strlen(strip_tags($message)) > 300) {
            return $error->errorSmall("The message can not ben more then 300 characters.");
        }

        $lastUser = $database->query("SELECT uid FROM ".TBL_SHOUTBOX." ORDER BY date DESC LIMIT 1")->fetchObject();

        if ($lastUser->uid == $user->id) {
            return $error->errorSmall("You need to wait till somebody else added a message to the shoutbox!");
        }

        $items = array(':uid' => $user->id, ':message' => $message, ':date' => time());
        $database->query("INSERT INTO ".TBL_SHOUTBOX." SET uid = :uid, message = :message, date = :date", $items);

        return $error->succesSmall("Your message has been added to the shoutbox!");
    }

    public function attackPlayer($username, $bullets)
    {
        global $user, $database, $error;

        if ($username == "demo") {
            return $error->errorSmall("You can't attack the demo account.");
        }

        if ($username == $user->info->username) {
            return $error->errorSmall("You can't attack yourself.");
        }

        if ($bullets > $user->stats->bullets || empty($bullets)) {
            return $error->errorSmall("You don't have ".$bullets." bullets.");
        }

        if ($user->stats->protection > time()) {
            return $error->errorSmall("You're under protection, this means you can't attack players.");
        }

        $userToAttackId = $database->getUserInfo($username)->id;

        if ($userToAttackId == NULL) {
            return $error->errorSmall("User \"".$username."\" does not exists.");
        }

        $userToAttack = $database->query("SELECT fid, city, protection FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $userToAttackId))->fetchObject();

        if ($userToAttack->fid == $user->stats->fid) {
            return $error->errorSmall("You can't attack players who are in the same family.");
        }

        if ($user->stats->city != $userToAttack->city) {
            return $error->errorSmall("You are not in the same city as ".$username);
        }

        if ($userToAttack->protection > time()) {
            return $error->errorSmall("This user is under protection till ".date("Y-m-d H:i", $userToAttack->protection).".");
        }

        return $error->succesSmall("Success");
    }

    public function createFamily($name)
    {
        global $user, $database, $error;

        if (empty($name)) {
            return $error->errorSmall("Please fill in a family name.");
        }

        if (strlen($name) > 20) {
            return $error->errorSmall("Your family is above 20 characters, yours is ".strlen($name).".");
        }

        if (!ctype_alnum($name)) {
            return $error->errorSmall("Your family name may only contain letters or digits.");
        }

        if ($user->stats->fid != 0) {
            return $error->errorSmall("You're already in a family.");
        }

        $count = $database->query("SELECT id FROM ".TBL_FAMILY." WHERE name = :name", array(':name' => $name))->rowCount();

        if ($count != 0) {
            return $error->errorSmall("This family name already exists.");
        }

        $items = array(':name' => $name, ':bank' => 100, ':power' => 100, ':creator' => $user->id, ':max' => 10);
        $lastId = $database->query("INSERT INTO ".TBL_FAMILY." SET name = :name, cash = :cash, bank = :bank, power = :power, creator = :creator, max_members = :max", $items, true);

        $items = array(':fid' => $lastId, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET fid = :fid WHERE uid = :uid", $items);

        return $error->succesSmall("You have created the family ".$name." with success!");
    }

    public function joinFamily($fid)
    {
        global $user, $database, $error;

        if ($user->in_family) {
            return $error->errorSmall("You're already in a family. If you want to join this family you first need to leave your own family.");
        }

        $items = array(':fid' => $fid);
        $family = $database->query("SELECT join_status, max_members FROM ".TBL_FAMILY." WHERE id = :fid", $items);

        if ($family->rowCount() == 0) {
            return $error->errorSmall("This family doesn't exists.");
        }

        $family = $family->fetchObject();

        $members = $database->query("SELECT fid FROM ".TBL_INFO." WHERE fid = :fid", $items)->rowCount();

        if ($members >= $family->max_members) {
            return $error->errorSmall("This family has already reached his maximum member amount.");
        }

        $items[':uid'] = $user->id;

        if ($family->join_status == 1) {
            $database->query("UPDATE ".TBL_INFO." SET fid = :fid WHERE uid = :uid", $items);
            return $error->succesSmall("You have joined the family!");
        } else if ($family->join_status == 2) {
            return $error->errorSmall("This family doesn't accepts join invites.");
        } else {
            $database->query("INSERT INTO ".TBL_FAMILY_JOIN." SET uid = :uid, fid = :fid", $items);
            return $error->succesSmall("An invite to join this family has been sent.");
        }
    }

    public function leaveFamily()
    {
        global $user, $database, $error;

        if ($user->family->creator == $user->id) {
            $items = array(':fid' => $user->family->id, ':uid' => $user->id);
            $members = $database->query("SELECT fid FROM ".TBL_INFO." WHERE fid = :fid AND uid != :uid", $items)->rowCount();
            if ($members == 0) {
                $items = array(':fid' => 0, ':uid' => $user->id);
                $database->query("UPDATE ".TBL_INFO." SET fid = :fid WHERE uid = :uid", $items);
                $items = array(':fid' => $user->family->id, ':uid' => $user->id);
                $database->query("DELETE FROM ".TBL_FAMILY." WHERE id = :fid AND creator = :uid", $items);
                return $error->succesSmall("You have left and removed your family.");
            } else {
                $items = array(':fid' => $user->family->id, ':uid' => $user->id);
                $highestPlayer = $database->query("SELECT uid FROM ".TBL_INFO." WHERE fid = :fid AND uid != :uid ORDER BY power DESC LIMIT 1", $items)->fetchObject();
                $items = array(':uid' => $highestPlayer->uid, ':fid' => $user->family->id);
                $database->query("UPDATE ".TBL_FAMILY." SET creator = :uid WHERE id = :fid", $items);
                $items = array(':fid' => 0, ':uid' => $user->id);
                $database->query("UPDATE ".TBL_INFO." SET fid = :fid WHERE uid = :uid", $items);
                return $error->succesSmall("You have left your family.");
            }
        } else {
            $items = array(':fid' => 0, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET fid = :fid WHERE uid = :uid", $items);
            return $error->succesSmall("You have left your family.");
        }
    }

    public function pimpHo()
    {
        global $database, $error, $user;

        if ($user->time->pimp_time > time()) {
            return $error->errorSmall("You need to wait <time class='timer'>".($user->time->pimp_time - time())."</time> seconds before you can pimp again.");
        }

        $newHo = mt_rand(3, 15);

        $user->stats->ho_street = $user->stats->ho_street + $newHo;
        $items = array(':ho' => $user->stats->ho_street, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET ho_street = :ho WHERE uid = :uid", $items);

        $items = array(':time' => time() + 600, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_TIME." SET pimp_time = :time WHERE uid = :uid", $items);

        return $error->succesSmall("You pimped ".$newHo." ho('s) with success!");
    }

    public function putHoFromStreet()
    {
        global $database, $error, $user;

        if ($user->stats->ho_street == 0) {
            return $error->errorSmall("You don't have anny ho's on the street.");
        }

        $user->stats->ho_glass = $user->stats->ho_glass + $user->stats->ho_street;
        $oldHo = $user->stats->ho_street;
        $user->stats->ho_street = 0;
        $items = array(':street' => $user->stats->ho_street, ':glass' => $user->stats->ho_glass, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET ho_street = :street, ho_glass = :glass WHERE uid = :uid", $items);

        return $error->succesSmall("You put all you ho's (".$oldHo.") behind the glass!");
    }

    public function getHoCash()
    {
        global $database, $user, $error, $settings;

        $money = $user->moneyEarnedFromHo();

        if ($money == 0) {
            return $error->errorSmall("You haven't earned anything yet.");
        }

        $user->stats->money = $user->stats->money + $money;
        $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash WHERE uid = :uid", $items);

        $user->time->pimp_money = time();
        $items = array(':time' => $user->time->pimp_money, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_TIME." SET pimp_money = :time WHERE uid = :uid", $items);

        return $error->succesSmall("You have earned ".$settings->currencySymbol().$settings->createFormat($money));
    }

    public function createTransaction($amount, $to)
    {
        global $database, $settings, $user, $error;

        if ($amount < 1) {
            return $error->errorSmall("Amount needs to have a minimum of '1'.");
        }

        if ($amount > $user->stats->bank) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($amount)." on your bank.");
        }

        if ($to == $user->info->username) {
            return $error->errorSmall("You silly boy!");
        }

        $uid = $database->getUserInfo($to);

        if ($uid == NULL) {
            return $error->errorSmall("This user (".$to.") doesn't exists.");
        }

        $uid = $uid->id;
        $bank = $database->query("SELECT bank FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $uid))->fetchObject()->bank;
        $newBank = $bank + $amount;
        $items = array(':bank' => $newBank, ':uid' => $uid);
        $database->query("UPDATE ".TBL_INFO." SET bank = :bank WHERE uid = :uid", $items);

        $user->stats->bank = $user->stats->bank - $amount;
        $items = array(':bank' => $user->stats->bank, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET bank = :bank WHERE uid = :uid", $items);

        $settings->sendMessage("New transaction", $user->info->username." has gave you ".$settings->currencySymbol().$settings->createFormat($amount).".", $uid);
        return $error->succesSmall("The transaction to ".$to." with the amount of ".$settings->currencySymbol().$settings->createFormat($amount)." has been send.");
    }

    public function depositFamily($value)
    {
        global $user, $database, $error, $settings;

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater.");
        }

        if ($value > $user->stats->bank) {
            return $error->errorSmall("You don't have that amount to deposit.");
        }

        $user->stats->bank = $user->stats->bank - $value;
        $user->family->bank  = $user->family->bank + $value;
        $items = array(':bank' => $user->stats->bank, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET bank = :bank WHERE uid = :id", $items);

        $items = array(':bank' => $user->family->bank, ':id' => $user->family->id);
        $database->query("UPDATE ".TBL_FAMILY." SET bank = :bank WHERE id = :id", $items);

        $this->createFamilyTransaction($value, 0);

        return $error->succesSmall("You have deposit ".$settings->currencySymbol().$settings->createFormat($value)." to your family with success.");
    }

    public function withdrawFamily($value)
    {
        global $user, $database, $error, $settings;

        if ($user->family->creator != $user->id) {
            return $error->errorSmall("You don't have the permissions to take money.");
        }

        if ($value < 1) {
            return $error->errorSmall("Value must be 1 or greater.");
        }

        if ($value > $user->family->bank) {
            return $error->errorSmall("You don't have that amount to withdraw.");
        }

        $user->stats->bank  = $user->stats->bank + $value;
        $user->family->bank  = $user->family->bank - $value;
        $items = array(':bank' => $user->stats->bank, ':id' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET bank = :bank WHERE uid = :id", $items);

        $items = array(':bank' => $user->family->bank, ':id' => $user->family->id);
        $database->query("UPDATE ".TBL_FAMILY." SET bank = :bank WHERE id = :id", $items);

        $this->createFamilyTransaction($value, 1);

        return $error->succesSmall("You have withdraw ".$settings->currencySymbol().$settings->createFormat($value)." from your family with success.");
    }

    private function createFamilyTransaction($value, $type)
    {
        global $database, $user;

        $items = array(':fid' => $user->family->id, ':uid' => $user->id);
        $stmt = $database->query("SELECT * FROM ".TBL_FAMILY_TRAN." WHERE uid = :uid AND fid = :fid", $items)->fetchObject();

        if ($stmt == false) {
            $items[':amount'] = $value;
            $database->query("INSERT INTO ".TBL_FAMILY_TRAN." SET amount = :amount, uid = :uid, fid = :fid", $items);
        } else {
            $newAmount = ($type == 1) ? $stmt->amount - $value : $stmt->amount + $value;
            $items[':amount'] = $newAmount;
            $database->query("UPDATE ".TBL_FAMILY_TRAN." SET amount = :amount WHERE uid = :uid AND fid = :fid", $items);
        }
    }

    public function updateFamilyMessage($message)
    {
        global $error, $database, $user;

        if (strlen($message) > 5000) {
            return $error->errorSmall("The message may not be longer then 5000 characters, yours is ".strlen($message));
        }

        $items = array(':mess' => $message, ':id' => $user->family->id);
        $database->query("UPDATE ".TBL_FAMILY." SET info = :mess WHERE id = :id", $items);

        return $error->succesSmall("Your family message has been updated!");
    }
}