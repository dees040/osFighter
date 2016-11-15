<?php

namespace App\Library;

use App\Models\Page;

class DynamicRouter
{
    /**
     * @var Page
     */
    private $page;

    /**
     * DynamicRouter constructor.
     *
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Call a dynamic route.
     *
     * @return \Illuminate\Routing\Route
     */
    public function call()
    {
        return call_user_func_array(
            ['\Illuminate\Support\Facades\Route', $this->page->route_method],
            [$this->page->url, $this->page->route_action]
        );
    }
}