<?php

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
