<?php

class Settings
{
    private $currency;
    public $config;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        global $database;
        $this->config = $database->getConfigs();
        $this->setCurrency();
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
                case "&#163 ;":
                    return "files/images/icons/currency_p.png";
                default:
                    return "files/images/icons/currency_d.png";
            }
        } else {
            return $this->currency;
        }
    }

    public function createFormat($number) {
        global $database;

        $number_format = $database->getConfigs()['NUMBER_FORMAT'];

        if ($number_format == 1) {
            return number_format($number, 0, ',', '.');
        } else if ($number_format == 2) {
            return number_format($number, 0, '.', ',');
        }
    }
}