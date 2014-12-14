<?php

class Casino
{
    private $vaultCode;

    public function __construct()
    {

    }

    public function createCrackTheVaultCode()
    {
        $code = null;

        for($i = 0; $i < 4; $i++) {
            $code[$i] = chr(97 + mt_rand(0, 25));
        }

        for($i = 4; $i < 6; $i++) {
            $code[$i] = mt_rand(0, 9);
        }

        $this->vaultCode = $_SESSION['crack_the_vault'] = $code;

        return $code;
    }

    public function checkTheCode($codes)
    {
        global $database, $error, $settings, $user;

        if (!$_SESSION['crack_the_vault'] || empty($_SESSION['crack_the_vault']) || is_null($_SESSION['crack_the_vault'])) {
            $this->createCrackTheVaultCode();
        }

        foreach($codes as $code) {
            if (trim($code) === '') return $error->errorSmall("You have an empty input field!");
        }

        $string = "";
        $success = true;

        for($i = 0; $i < 6; $i++) {
            if ($_SESSION['crack_the_vault'][$i] == strtolower($codes['number_'.$i])) {
                $string .= "<span style='color: green;'>".$codes['number_'.$i]."</span>";
                $_SESSION[$i] = strtoupper($codes['number_'.$i]);
            } else {
                $string .= "<span style='color: red;'>".$codes['number_'.$i]."</span>";
                $success = false;
            }
        }

        if ($success) {
            $price = mt_rand(50000, 200000);

            $user->stats->money = $user->stats->money + $price;
            $items = array(':cash' => $user->stats->money, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET money = :cash WHERE uid = :uid", $items);

            $_SESSION['crack_the_vault'] = $this->createCrackTheVaultCode();
            $_SESSION['vault_is_open'] = true;

            return $error->succesSmall("You have crack the vault! You have won ".$settings->currencySymbol().$settings->createFormat($price).". The vault code has been reset.");
        } else {
            return $error->errorSmall("The code is not correct, output: ".strtoupper($string));
        }
    }

    public function goodCode($i)
    {
        if (isset($_SESSION[$i])) {
            $retval = $_SESSION[$i];
            unset($_SESSION[$i]);
            return $retval;
        } else {
            return '';
        }
    }

    public function betHigherLower($type, $bet)
    {
        global $database, $error, $user, $settings;

        $_SESSION['higher-lower-bet'] = $bet;

        if ($bet > $user->stats->money) {
            return $error->errorSmall("You don't have ".$settings->currencySymbol().$settings->createFormat($bet)." in cash.");
        }

        if ($bet < 500) {
            return $error->errorSmall("You need to have a minimum of ".$settings->currencySymbol().$settings->createFormat(500).".");
        }

        if ($bet > 5000) {
            return $error->errorSmall("The maximum is ".$settings->currencySymbol().$settings->createFormat(5000).".");
        }

        $newNumber = mt_rand(0, 10);
        $won = false;

        if ($newNumber == $_SESSION['higher-lower']) {
            if (($bet * 4) > $user->stats->money) {
                $user->time->jail = time() + 300;
                $items = array(':time' => $user->time->jail, ':uid' => $user->id);
                $database->query("UPDATE ".TBL_TIME." SET jail = :time WHERE uid = :uid", $items);

                return $error->errorBig("The number was the same.. because you didn't have ".$settings->currencySymbol().$settings->createFormat($bet * 4)." you were put in jail for 5 minutes.");
            } else {
                $user->stats->money = $user->stats->money - ($bet * 4);
                $items = array(':money' => $user->stats->money, ':uid' => $user->id);
                $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);

                return $error->errorBig("The number was the same.. you have lost ".$settings->currencySymbol().$settings->createFormat($bet * 4).".");
            }
        }

        if ($type === true) {
            if ($newNumber < $_SESSION['higher-lower']) {
                $won = true;
            }
        } else {
            if ($newNumber > $_SESSION['higher-lower']) {
                $won = true;
            }
        }

        $_SESSION['higher-lower'] = $newNumber;

        if ($won === true) {
            $user->stats->money = $user->stats->money + ($bet * 2);
            $items = array(':money' => $user->stats->money, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);

            return $error->succesSmall("You guessed correctly, you have won ".$settings->currencySymbol().$settings->createFormat($bet * 2).".");
        } else {
            $user->stats->money = $user->stats->money - $bet;
            $items = array(':money' => $user->stats->money, ':uid' => $user->id);
            $database->query("UPDATE ".TBL_INFO." SET money = :money WHERE uid = :uid", $items);

            return $error->errorSmall("You guessed wrong, you have lost ".$settings->currencySymbol().$settings->createFormat($bet).".");
        }
    }
}