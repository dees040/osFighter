<?php

namespace App\Library\Validators;

class CaptchaValidator
{
    /**
     * Create new validator for checking if captcha is corretly
     * confirmed, IF NEEDED. Captcha can be turned off.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @return bool
     */
    public function captchaConfirmed($attribute, $value, $parameters)
    {
        if (game()->isUsingCaptcha()) {
            $captcha = app('recaptcha.service');
            $challenge = app('request')->input($captcha->getResponseKey());

            return $captcha->check($challenge, $value);
        }

        return true;
    }
}