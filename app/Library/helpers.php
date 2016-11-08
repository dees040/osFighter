<?php

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
