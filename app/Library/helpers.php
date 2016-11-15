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

if (! function_exists('call_dynamic_route')) {
    /**
     * Call a dynamic route.
     *
     * @param \App\Models\Page $page
     * @return \Illuminate\Routing\Route
     */
    function call_dynamic_route(\App\Models\Page $page)
    {
        return (new \App\Library\DynamicRouter($page))->call();
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

if (! function_exists('sec_difference')) {
    /**
     * Get the difference in seconds.
     *
     * @param \Carbon\Carbon $time
     * @param Carbon\Carbon $now
     * @return int
     */
    function sec_difference(\Carbon\Carbon $time, $now = null)
    {
        $now = $now ?: \Carbon\Carbon::now();

        return $time->diffInSeconds($now);
    }
}

if (! function_exists('user')) {
    /**
     * Get the UserHandler instance.
     *
     * @param \App\Models\User|null $user
     * @return \App\Library\UserHandler
     */
    function user($user = null)
    {
        if (is_null($user)) {
            return app()->make('App\Library\UserHandler');
        }

        return new \App\Library\UserHandler($user);
    }
}
