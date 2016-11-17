<?php

namespace App\Library\Routing\Facade;

use Illuminate\Support\Facades\Facade;

class DynamicRouter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'dynamic_router'; }
}