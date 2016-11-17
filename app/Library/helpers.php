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
     * @param \App\Models\Route $page
     * @return \Illuminate\Routing\Route
     */
    function call_dynamic_route(\App\Models\Route $page)
    {
        return app()->make('dynamic_router')->call($page);
    }
}

if (! function_exists('dynamic_route')) {
    /**
     * Generate url via dynamic route.
     *
     * @param $name
     * @param $binding
     * @param null $onlyUrl
     * @return \Illuminate\Support\HtmlString|string
     */
    function dynamic_route($name, $binding, $onlyUrl = null)
    {
        return DynamicRouter::generateUrl($name, $binding, $onlyUrl);
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
     * @param \Carbon\Carbon $now
     * @return int
     */
    function sec_difference($time, $now = null)
    {
        $now = $now ?: \Carbon\Carbon::now();

        return is_null($time) ? 0 : $time->diffInSeconds($now);
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
