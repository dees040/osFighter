<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class Settings
{
    private $currency;
    public $config;
    public $PayPal;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        global $database;
        $this->config = $database->getConfigs();
        $this->setCurrency();
        $this->setPayPalSettings();
    }

    private function setPayPalSettings()
    {
        $this->PayPal = new ApiContext(
            new OAuthTokenCredential(
                $this->config['PAYPAL_CLIENT_ID'],
                $this->config['PAYPAL_SECRET_ID']
            )
        );

        $this->PayPal->setConfig(
            array(
                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => false,
                'log.FileName' => '',
                'log.LogLevel' => 'FINE',
                'validation.level' => 'log'
            )
        );
    }


    private function setCurrency()
    {
        global $database;
        $this->currency = $database->getConfigs()['CURRENCY'];
    }

    public function currencySymbol($images = false)
    {
        if ($images === true) {
            switch ($this->currency) {
                case "&#36;":
                    return "files/images/icons/currency_d.png";
                case "&#128;":
                    return "files/images/icons/currency_e.png";
                case "&#165;":
                    return "files/images/icons/currency_y.png";
                case "&#163;":
                    return "files/images/icons/currency_p.png";
                default:
                    return "files/images/icons/currency_d.png";
            }
        } else {
            return $this->currency;
        }
    }

    public function currencyForPayPal()
    {
        switch ($this->currency) {
            case "&#36;":
                return "USD";
            case "&#128;":
                return "EUR";
            case "&#165;":
                return "USD";
            case "&#163;":
                return "GBP";
            default:
                return "USD";
        }
    }

    public function createFormat($number)
    {
        global $database;

        $number_format = $database->getConfigs()['NUMBER_FORMAT'];

        if ($number_format == 1) {
            return number_format($number, 0, ',', '.');
        } else if ($number_format == 2) {
            return number_format($number, 0, '.', ',');
        }
    }

    public function createCaptcha()
    {
        $string = "<br><table style=\"margin:0px auto; margin-top: -10px;\"><tr><td style=\"text-align: center;\"><b>Click the golden gun</b></td></tr>".
                  "<tr><td style=\"text-align: center;\">";

        // Hieronder niets meer wijzigen, tenzij je weet wat je doet.
        function generate_password($length)
        {
            $ret_val = '';
            $charset = '123456789';
            $charset_len = strlen($charset) - 1;
            srand(microtime() * 1000000);
            for ($i = 0; $i < $length; $i++)
                $ret_val .= $charset{rand(0, $charset_len)};
            return $ret_val;
        }

        for ($q = 1; $q < 5; $q++) {
            $uString[$q] = generate_password(1);
        }

        $_SESSION['botcheck'] = $uString[1] . $uString[2] . $uString[3] . $uString[4];

        $knop = mt_rand(1, 4);
        $i = 0;

        for ($x = 0; $x < 4; $x++) {
            $i++;
            $num = md5(rand(10000, 99999));

            if ($knop == $i) {
                $string .= '<input style="background: url(\'core/image.php?c=' . md5($_SESSION['botcheck']) . '\') no-repeat; border: 0px; width: 43px; height: 43px; color: rgba(0,0,0,0);" type="submit" value="' . md5($_SESSION['botcheck']) . '" name="captcha">';
            } else {
                $string .= '<input style="background: url(\'core/image.php?c=' . $num . '\') no-repeat; border: 0px; width: 43px; height: 43px; color: rgba(0,0,0,0);" type="submit" value="' . $num . '" name="captcha">';
            }
        }
        $string .= '</table>';

        return $string;
    }

    public function checkCaptcha()
    {
        return $_POST['captcha'] == "" || $_POST['captcha'] != md5($_SESSION['botcheck']);
    }
}