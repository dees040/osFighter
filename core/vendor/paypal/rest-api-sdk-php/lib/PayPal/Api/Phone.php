<?php

namespace PayPal\Api;

use PayPal\Common\PPModel;

/**
 * Class Phone
 *
 * Representation of a phone number.
 *
 * @package PayPal\Api
 *
 * @property string country_code
 * @property string national_number
 * @property string extension
 */
class Phone extends PPModel
{
    /**
     * Country code (in E.164 format). Assume length is n.
     *
     * @param string $country_code
     * 
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     * Country code (in E.164 format). Assume length is n.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * Country code (in E.164 format). Assume length is n.
     *
     * @deprecated Instead use setCountryCode
     *
     * @param string $country_code
     * @return $this
     */
    public function setCountry_code($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     * Country code (in E.164 format). Assume length is n.
     * @deprecated Instead use getCountryCode
     *
     * @return string
     */
    public function getCountry_code()
    {
        return $this->country_code;
    }

    /**
     * In-country phone number (in E.164 format). Maximum (15 - n) d