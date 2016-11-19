<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * The namespace of the validators location.
     *
     * @var string
     */
    protected $namespace = 'App\Library\Validators';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit(
            'captcha_confirmed',
            $this->extension('CaptchaValidator@captchaConfirmed'),
            'Please ensure that you are a human!'
        );

        Validator::extend(
            'required_if_menuable',
            $this->extension('RequirementValidator@ifMenuable'),
            'The :attribute field is required.'
        );

        Validator::extend(
            'shout_box_limit',
            $this->extension('ShoutBoxValidator@limit'),
            'You may only post two message after each other.'
        );

        Validator::extend(
            'has_cash',
            $this->extension('SuppliesValidator@hasCash'),
            'You don\'t have enough cash.'
        );

        Validator::extend(
            'route_bindings',
            $this->extension('BindingsValidator@route'),
            'You need to have all the bindings.'
        );

        Validator::extend(
            'strength_points',
            $this->extension('ShopValidator@strengthPoints'),
            'You don\'t have the required strength points.'
        );

        Validator::extend(
            'shop_item_amount',
            $this->extension('ShopValidator@amount'),
            'You can\'t have more then the maximum.'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Create the extension string for a new validator extending.
     *
     * @param $extension string
     * @return string
     */
    protected function extension($extension)
    {
        return $this->namespace . '\\' . $extension;
    }
}
