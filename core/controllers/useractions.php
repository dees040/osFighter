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
            return $error->errorSmall("This product has a minimum of 1.");
        }

        $productInfo = $database->query(
            "SELECT name, price, power, max_amount, min_gym FROM ".TBL_SHOP_ITEMS." WHERE id = :sid",
            array(':sid' => $sid)
        )->fetchObject();
        $cost = $productInfo->price * $amount;

        if ($cost > $user->stats->money) {
            return $error->errorSmall("You don't have enough cash to buy this item(s).");
        }

        if ($productInfo->min_gym > $user->stats->gym) {
            return $error->errorSmall("You don't have ".$productInfo->min_gym."% gym process.");
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

        return $error->succesSmall("You have bought ".$amount." ".$productInfo->name."(s) for ".$settings->currencySymbol().$settings->createFormat($cost)." with success!");
    }

    public function buyFromShopFamily($info)
    {
        global $user, $database, $error, $settings;

        $sid = 0;
        $amount = $_POST['number_family_shop'];

        foreach($info as $key => $item)
            if ($item == "Buy!")
                $sid = $key;

        if ($amount < 1) {
            return $error->errorSmall("This product has a minimum of 1.");
        }

        if ($user->family->creator != $user->id) {
            return $error->errorBig("You don't have permission to buy items.");
        }

        $productInfo = $database->query(
            "SELECT name, price, power, max_amount FROM ".TBL_SHOP_ITEMS." WHERE id = :sid",
            array(':sid' => $sid)
        )->fetchObject();
        $cost = $productInfo->price * $amount;

        if ($cost > $user->family->bank) {
            return $error->errorSmall("Your family bank don't have enough money on the bank to buy this item(s).");
        }

        $currentAmount = $database->query(
            "SELECT amount FROM ".TBL_FAMILY_ITEMS." WHERE fid = :id AND sid = :sid",
            array(':id' => $user->family->id, ':sid' => $sid)
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

        $user->family->bank = $user->family->bank - $cost;
        $user->family->power = $user->family->power + ($amount * $productInfo->power);
        $items = array(':fid' => $user->family->id, ':bank' => $user->family->bank, ':power' => $user->family->power);
        $database->query("UPDATE ".TBL_FAMILY." SET bank = :bank, power = :power WHERE id = :fid", $items);

        $dataExists = $database->query(
            "SELECT sid FROM ".TBL_FAMILY_ITEMS." WHERE fid = :fid AND sid = :sid",
            array(':fid' => $user->family->id, ':sid' => $sid)
        )->rowCount();

        $items = array(':fid' => $user->family->id, ':amount' => ($currentAmount + $amount), ':sid' => $sid);
        if ($dataExists == 1) {
            $database->query("UPDATE " . TBL_FAMILY_ITEMS . " SET amount = :amount WHERE fid = :fid AND sid = :sid", $items);
        } else {
            $database->query("INSERT INTO ".TBL_FAMILY_ITEMS." SET fid = :fid, sid = :sid, amount = :amount", $items);
        }

        return $error->succesSmall("You have bought ".$amount." ".$productInfo->name."(s) for ".$settings->currencySymbol().$settings->createFormat($cost)." with success!");
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

        return $error->succesSmall("You're traveling to ".unserialize($settings->config['CITIES'])[$toId].".");
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
        global $user, $database, $error, $settings;

        if ($username == "demo") {
            return $error->errorSmall("You can't attack the demo account.");
        }

        if ($username == $user->info->username) {
            return $error->errorSmall("You can't attack yourself.");
        }

        if ($bullets > $user->stats->bullets || $user->stats->bullets == 0) {
            return $error->errorSmall("You don't have ".$bullets." bullets.");
        }

        if ($user->stats->protection > time()) {
            return $error->errorSmall("You're under protection, this means you can't attack players.");
        }

        $userToAttackId = $database->getUserInfo($username)->id;

        if ($userToAttackId == NULL) {
            return $error->errorSmall("User \"".$username."\" does not exists.");
        }

        $userToAttack = $database->query("SELECT fid, city, protection, power, health, bullets FROM ".TBL_INFO." WHERE uid = :uid", array(':uid' => $userToAttackId))->fetchObject();

        if ($userToAttack->fid == $user->stats->fid) {
            return $error->errorSmall("You can't attack players who are in the same family.");
        }

        if ($user->stats->city != $userToAttack->city) {
            return $error->errorSmall("You are not in the same city as ".$username);
        }

        if ($userToAttack->protection > time()) {
            return $error->errorSmall("This user is under protection till ".date("Y-m-d H:i", $userToAttack->protection).".");
        }

        if ($userToAttack->health == 0) {
            return $error->errorSmall("This user is death.");
        }

        $userChance = round(($user->stats->power / ($user->stats->power + $userToAttack->power)) * 100);
        $userToAttackChange = round(100 - $userChance);
        $winner = $settings->getRandomWeightedElement(array($user->id => $userChance, $userToAttackId => $userToAttackChange));

        if ($winner == $user->id) {
            $attackInfo = $this->attackInfo($bullets, $user->id, $userToAttackId);

            $settings->sendMessage("You have been attacked", "Dear ".$username.",<br><br>".$user->info->username." has attacked you. You have lost the fight. He has stole ".$settings->currencySymbol().$settings->createFormat($attackInfo['money'])." from you. He toke ".$attackInfo['health']."% health away from you.", $userToAttackId);

            return $error->succesSmall("You have won. You removed ".$attackInfo['health']."% from ".$username."'s health. You have won ".$settings->currencySymbol().$settings->createFormat($attackInfo['money']).".");
        } else {
            $attackInfo = $this->attackInfo(mt_rand(0, $userToAttack->bullets), $userToAttackId, $user->id);

            $settings->sendMessage("You have been attacked", "Dear ".$username.",<br><br>".$user->info->username." has attacked you. You have won the fight. You stole ".$settings->currencySymbol().$settings->createFormat($attackInfo['money'])." from him. You toke ".$attackInfo['health']."% health away from him.", $userToAttackId);

            return $error->errorSmall("You have lost, he removed ".$attackInfo['health']."% health from you. You have lost ".$settings->currencySymbol().$settings->createFormat($attackInfo['money']).".");
        }
    }

    private function attackInfo($bullets, $winner, $loser)
    {
        global $database;

        $healRemoveVal = ceil($bullets * rand(0, 10000000000) / 100000000000);
        $healRemove = ($healRemoveVal > 100) ? 100 : $healRemoveVal;

        $items = array(':uid' => $loser);
        $loserInfo = $database->query("SELECT money, health FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();
        $items = array(':uid' => $winner);
        $winnerInfo = $database->query("SELECT money, bullets FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject();

        $moneyWon = mt_rand(0, $loserInfo->money);

        $items = array(':bullets' => $winnerInfo->bullets - $bullets, ':money' => $winnerInfo->money + $moneyWon, ':uid' => $winner);
        $database->query("UPDATE ".TBL_INFO." SET bullets = :bullets, money = :money WHERE uid = :uid", $items);

        $newHealth = ($loserInfo->health - $healRemove < 0) ? 0 : $loserInfo->health - $healRemove;
        $items = array(':money' => $loserInfo->money - $moneyWon, ':health' => $newHealth, ':uid' => $loser);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, health = :health WHERE uid = :uid", $items);

        return array('health' => $healRemove, 'money' => $moneyWon);
    }

    public function createFamily($name)
    {
        global $user, $database, $error;

        if (empty($name)) {
            return $error->errorSmall("Please fill in a family name.");
        }

        if (strlen($name) < 3) {
            return $error->errorSmall("Your family need at least 3 characters, yours is ".strlen($name).".");
        }

        if (strlen($name) > 20) {
            return $error->errorSmall("Your family name can't be above 20 characters, yours is ".strlen($name).".");
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
        $lastId = $database->query("INSERT INTO ".TBL_FAMILY." SET name = :name, bank = :bank, power = :power, creator = :creator, max_members = :max", $items, true);

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
            return $error->errorSmall("You don't have that amount on your bank to deposit.");
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
            return $error->errorSmall("You don't have that amount on your family bank to withdraw.");
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

    public function carHijacking()
    {
        global $database, $error, $user, $settings;

        if ($user->time->car_time > time()) {
            return null;
        }

        if ($settings->checkCaptcha()) {
            return $error->errorBig("Verification code is not correct.");
        }

        $change = mt_rand(0, 100);

        if ($change < 50) {
            $user->time->car_time = time() + 90;
            $items = array(':time' => $user->time->car_time, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_TIME." SET car_time = :time WHERE uid = :uid", $items);

            $user->stats->rank_process = $user->stats->rank_process + 1;
            $items = array(':rank' => $user->stats->rank_process, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET rank_process = :rank WHERE uid = :uid", $items);

            return $error->errorSmall("Car hijacking has failed, but you escaped the police. Cooldown for 90 seconds.");
        } else if ($change < 75) {
            $user->time->car_time = time() + 90;
            $user->time->jail = time() + 120;
            $items = array(':jail' => $user->time->jail, ':car' => $user->time->car_time, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_TIME." SET jail = :jail, car_time = :car WHERE uid = :uid", $items);
            return $error->errorSmall("Car hijacking has failed, you're in jail for 120 seconds.");
        } else {
            $cars = $database->query("SELECT * FROM ".TBL_CARS)->fetchAll(PDO::FETCH_OBJ);

            $carArray = array();
            foreach($cars as $car) {
                $carArray[$car->id] = $car->steal_change;
            }

            $carForUser = $settings->getRandomWeightedElement($carArray);

            $items = array(':cid' => $carForUser, ':uid' => $user->id, ':damage' => mt_rand(5, 80), ':image' => mt_rand(1, 2));
            $database->query("INSERT INTO ".TBL_GARAGE." SET cid = :cid, uid = :uid, damage = :damage, image = :image", $items);

            $user->time->car_time = time() + 90;
            $items = array(':time' => $user->time->car_time, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_TIME." SET car_time = :time WHERE uid = :uid", $items);

            $user->stats->rank_process = $user->stats->rank_process + 3;
            $items = array(':rank' => $user->stats->rank_process, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET rank_process = :rank WHERE uid = :uid", $items);

            return $error->succesSmall("You have hijacked the ".$cars[($carForUser - 1)]->name." with success!");
        }
    }

    public function sellCar($id)
    {
        global $error, $database, $user, $settings;

        $items = array(':cid' => $id, ':uid' => $user->id);
        $car = $database->query("SELECT cid, damage FROM ".TBL_GARAGE." WHERE id = :cid AND uid = :uid", $items)->fetchObject();

        if ($car === false) {
            return $error->errorSmall("This vehicle doesn't belong to you.");
        }

        $items = array(':cid' => $car->cid);
        $car_info = $database->query("SELECT worth FROM ".TBL_CARS." WHERE id = :cid", $items)->fetchObject();
        $price = $car_info->worth - ($car->damage * ($car_info->worth / 100));

        $user->stats->money = $user->stats->money + $price;
        $items = array(':uid' => $user->id, ':money' => $user->stats->money);
        $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);
        $items = array(':cid' => $id);
        $database->query("DELETE FROM ".TBL_GARAGE." WHERE id = :cid", $items);

        return $error->succesSmall("You sold the vehicle for ".$settings->currencySymbol().$settings->createFormat($price).".");
    }

    public function repairCar($id)
    {
        global $user, $database, $error, $settings;

        $items = array(':cid' => $id, ':uid' => $user->id);
        $car = $database->query("SELECT cid, damage FROM ".TBL_GARAGE." WHERE id = :cid AND uid = :uid", $items)->fetchObject();

        if ($car === false) {
            return $error->errorSmall("This vehicle doesn't belong to you.");
        }

        $items = array(':cid' => $car->cid);
        $car_info = $database->query("SELECT worth FROM ".TBL_CARS." WHERE id = :cid", $items)->fetchObject();
        $price = $car->damage * ($car_info->worth / 100);

        if ($price > $user->stats->money) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($price)." in cash.");
        }

        $user->stats->money = $user->stats->money - $price;
        $items = array(':uid' => $user->id, ':money' => $user->stats->money);
        $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);
        $items = array(':cid' => $id, ':damage' => 0);
        $database->query("UPDATE ".TBL_GARAGE." SET damage = :damage WHERE id = :cid", $items);

        return $error->succesSmall("You have repaired your vehicle for ".$settings->currencySymbol().$settings->createFormat($price).".");
    }

    public function createRace($cid, $bet)
    {
        global $database, $error, $settings, $user;

        $items = array(':cid' => $cid, ':uid' => $user->id);
        $carForRace = $database->query("SELECT damage FROM ".TBL_GARAGE." WHERE id = :cid AND uid = :uid", $items)->fetchObject();

        if ($carForRace === false) {
            return $error->errorSmall("This car doesn't belong to you.");
        }

        if ($bet > $user->stats->money) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($bet)." in cash.");
        }

        $items = array(':uid' => $user->id);
        $lastRace = $database->query("SELECT id FROM ".TBL_RACES." WHERE uid = :uid", $items)->rowCount();

        if ($lastRace != 0) {
            return $error->errorSmall("You already have created a race.");
        }

        $user->stats->money =$user->stats->money - $bet;
        $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :cash WHERE uid = :uid", $items);

        $items = array(':uid' => $user->id, ':bet' => $bet, ':cid' => $cid);
        $database->query("INSERT INTO ".TBL_RACES." SET uid = :uid, bet = :bet, cid = :cid", $items);

        return $error->succesSmall("Your streetrace has been created!");
    }

    public function startRace($rid, $cid)
    {
        global $database, $error, $user, $settings;

        $items = array(':rid' => $rid);
        $race = $database->query("SELECT * FROM ".TBL_RACES." WHERE id = :rid", $items)->fetchObject();

        if ($race === false) {
            return $error->errorSmall("This race doesn't exists.");
        }

        if ($race->uid == $user->id) {
            return $error->errorSmall("You have created this race, you can't start it yourself.");
        }

        $items = array(':cid' => $cid, ':uid' => $user->id);
        $car = $database->query("SELECT id, cid, damage FROM ".TBL_GARAGE." WHERE id = :cid AND uid = :uid", $items)->fetchObject();

        if ($car === false) {
            return $error->errorSmall("This car doesn't belong to you.");
        }

        $items = array(':cid' => $car->cid);
        $carWorthStarter = $database->query("SELECT worth FROM ".TBL_CARS." WHERE id = :cid", $items)->fetchObject()->worth;
        $items = array(':cid' => $race->cid);
        $creatorCar = $database->query("SELECT cid, damage FROM ".TBL_GARAGE." WHERE id = :cid", $items)->fetchObject();
        $items = array(':cid' => $creatorCar->cid);
        $carWorthCreator = $database->query("SELECT worth FROM ".TBL_CARS." WHERE id = :cid", $items)->fetchObject()->worth;

        $carStarterChance = round(($carWorthStarter / ($carWorthStarter + $carWorthCreator)) * 100);
        $carCreatorChance = round(100 - $carStarterChance);

        $winner = $settings->getRandomWeightedElement(array($user->id => $carStarterChance, $race->uid => $carCreatorChance));

        if ($winner == $user->id) {
            $settings->sendMessage("Race lost", "You have lost the race from ".$user->info->username.". He has won ".$settings->currencySymbol().$settings->createFormat($race->bet).".", $race->uid);

            $user->stats->money = $user->stats->money + $race->bet;
            $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET money = :cash WHERE uid = :uid", $items);

            $newDamage = $car->damage + mt_rand(5, 95);

            $items = array(':rid' => $race->id);
            $database->query("DELETE FROM ".TBL_RACES." WHERE id = :rid", $items);

            if ($newDamage >= 100) {
                $items = array(':cid' => $car->id);
                $database->query("DELETE FROM ".TBL_GARAGE." WHERE id = :cid", $items);

                return $error->succesSmall("You have won this race. The price of ".$settings->currencySymbol().$settings->createFormat($race->bet)." has been given to you. Your car has been destroyed from the race.");
            } else {
                $items = array(':cid' => $car->id, ':damage' => $newDamage);
                $database->query("UPDATE ".TBL_GARAGE." SET damage = :damage WHERE id = :cid", $items);

                return $error->succesSmall("You have won this race. The price of ".$settings->currencySymbol().$settings->createFormat($race->bet)." has been given to you. Your car has a new damage of ".$newDamage."%.");

            }
        } else {
            $items = array(':uid' => $race->uid);
            $money = $database->query("SELECT money FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject()->money;
            $items = array(':money' => $money + ($race->bet * 2), ':uid' => $race->uid);
            $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);

            $settings->sendMessage("Race won", "You have won the race from ".$user->info->username.". You have won ".$settings->currencySymbol().$settings->createFormat($race->bet).".", $race->uid);

            $user->stats->money = $user->stats->money - $race->bet;
            $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET money = :cash WHERE uid = :uid", $items);

            $newDamage = $car->damage + mt_rand(5, 95);

            $items = array(':rid' => $race->id);
            $database->query("DELETE FROM ".TBL_RACES." WHERE id = :rid", $items);

            if ($newDamage >= 100) {
                $items = array(':cid' => $car->id);
                $database->query("DELETE FROM ".TBL_GARAGE." WHERE id = :cid", $items);

                return $error->errorSmall("You have lost this race. The price of ".$settings->currencySymbol().$settings->createFormat($race->bet)." has been removed from cash. Your car has been destroyed from the race.");
            } else {
                $items = array(':cid' => $car->id, ':damage' => $newDamage);
                $database->query("UPDATE ".TBL_GARAGE." SET damage = :damage WHERE id = :cid", $items);

                return $error->errorSmall("You have lost this race. The price of ".$settings->currencySymbol().$settings->createFormat($race->bet)." has been removed from your cash. Your car has a new damage of ".$newDamage."%.");

            }
        }
    }

    public function deleteRace($rid)
    {
        global $database, $error, $user, $settings;

        $items = array(':uid' => $user->id, ':rid' => $rid);
        $race = $database->query("SELECT bet FROM ".TBL_RACES." WHERE uid = :uid AND id = :rid", $items)->fetchObject();

        if ($race === false) {
            return $error->errorSmall("You haven't created this race.");
        }

        $user->stats->money = $user->stats->money + $race->bet;
        $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO. " SET money = :cash WHERE uid = :uid", $items);

        $items = array(':rid' => $rid);
        $database->query("DELETE FROM ".TBL_RACES." WHERE id = :rid", $items);

        return $error->succesSmall("Your race has been deleted, your ".$settings->currencySymbol().$settings->createFormat($race->bet)." has been given back.");
    }

    public function trainGym($type)
    {
        global $error, $database, $user;

        if ($user->time->gym_time > time()) {
            return $error->errorSmall("You need to wait <time class='timer'>".($user->time->gym_time - time())."</time> seconds before you can train again.");
        }

        if ($user->info->gym >= 100) {
            return $error->errorSmall("Your gym process is already 100%.");
        }

        $gymInfo = array();

        switch($type) {
            case '1':
                $gymInfo = array('time' => 120, 'process' => 1);
                break;
            case '2':
                $gymInfo = array('time' => 240, 'process' => 2);
                break;
            case '3':
                $gymInfo = array('time' => 360, 'process' => 3);
                break;
        }

        $user->stats->gym = $user->stats->gym + $gymInfo['process'];
        $items = array(':gym' => $user->stats->gym, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET gym = :gym WHERE uid = :uid", $items);

        $user->time->gym_time = time() + $gymInfo['time'];
        $items = array(':time' => $user->time->gym_time, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_TIME." SET gym_time = :time WHERE uid = :uid", $items);

        return $error->succesSmall("You have trained ".$gymInfo['process']."%.");
    }

    public function buyBullets($amount)
    {
        global $database, $error, $user, $settings;

        $price = $amount * 200;

        if ($amount < 1) {
            return $error->errorSmall("You need to have a minimum of one bullet.");
        }

        if ($price > $user->stats->money) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($price)." in cash.");
        }

        $user->stats->money = $user->stats->money - $price;
        $user->stats->bullets = $user->stats->bullets + $amount;
        $items = array(':money' => $user->stats->money, ':bullets' => $user->stats->bullets, ':uid' => $user->id);
        $database->query("UPDATE ".TBL_INFO." SET money = :money, bullets = :bullets WHERE uid = :uid", $items);

        return $error->succesSmall("You have bought ".$amount." bullets for the price of ".$settings->currencySymbol().$settings->createFormat($price).".");
    }

    public function giveRespect($to)
    {
        global $user, $database, $error, $settings;

        $userid = $database->getUserInfo($to);

        if ($to == NULL || $to == "" || $userid === false) {
            return $error->errorSmall("This use doens't exists.");
        }

        if ($user->time->respect > time()) {
            return $error->errorSmall("You need to wait <time class='timer'>".($user->time->respect - time())."</time> before you can give respect again.");
        }

        $userid = $userid->id;

        if ($userid == $user->id) {
            return $error->errorSmall("You can't give respect to yourself.");
        }

        $items = array(':time' => time() + (3600 * 24), ':uid' => $user->id);
        $database->query("UPDATE ".TBL_TIME." SET respect = :time WHERE uid = :uid", $items);

        $items = array(':uid' => $userid);
        $respect = $database->query("SELECT respect FROM ".TBL_INFO." WHERE uid = :uid", $items)->fetchObject()->respect;
        $items[':respect'] = $respect + 1;
        $database->query("UPDATE ".TBL_INFO." SET respect = :respect WHERE uid = :uid", $items);

        $settings->sendMessage("Respect", "Dear ".$to.", <br><br>".$user->info->username." has gave you one respect point.", $userid);

        return $error->succesSmall("Respect given to <a href='personal/user-info?user=".$to."'>".$to."</a>.");
    }
}