<?php

if (! function_exists('currentUser')) {
    /**
     * Get the current authenticated user.
     *
     * @return \App\Models\User|null
     */
    function currentUser()
    {
        return Auth::user();
    }
}

if (! function_exists('game')) {
    /**
     * Get the Game instance.
     *
     * @param string $config
     * @return \App\Library\Game
     */
    function game($config = null)
    {
        $game = app()->make('App\Library\Game');

        if (is_null($config)) {
            return $game;
        }

        return $game->$config;
    }
}

if (! function_exists('icon')) {
    /**
     * Get an icon.
     *
     * @param $name
     * @return string
     */
    function icon($name)
    {
        return asset('images/icons/' . $name . '.png');
    }
}

if (! function_exists('money')) {
    /**
     * Convert amount to money string.
     *
     * @param $amount
     * @return string
     */
    function money($amount)
    {
        return game()->currency_symbol . number_format($amount, 0, '.', ',');
    }
}

if (! function_exists('user')) {
    /**
     * Get the UserHandler instance.
     *
     * @return \App\Library\UserHandler
     */
    function user()
    {
        return app()->make('App\Library\UserHandler');
    }
}
