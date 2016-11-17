<?php

namespace App\Library\Routing;

class DynamicRoute
{
    /**
     * The route title.
     *
     * @var string
     */
    private $title;

    /**
     * The unique route name.
     *
     * @var string
     */
    private $routeName;

    /**
     * The route action.
     *
     * @var string
     */
    private $routeAction;

    /**
     * The route method (get/post/put/patch/delete).
     *
     * @var string
     */
    private $routeMethod;

    /**
     * The route url.
     *
     * @var string
     */
    private $routeUrl;

    /**
     * The menu ID.
     *
     * @var integer
     */
    private $menu;

    /**
     * The menu weight.
     *
     * @var integer
     */
    private $weight;

    /**
     * The group ID.
     *
     * @var integer
     */
    private $group;

    /**
     * Indicate if pages if viewable when in jail.
     *
     * @var boolean
     */
    private $jail;

    /**
     * Indicate if the route is menuable.
     *
     * @var boolean
     */
    private $menuable;

    /**
     * DynamicRoute constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->routeName = $name;

        $this->setDefaults();
    }

    public function TODO()
    {
        // A way to store
        // Make Rules
    }

    /**
     * Set the defaults.
     */
    private function setDefaults()
    {
        $this->title = ucfirst($this->routeName);
        $this->routeAction = '';
        $this->routeMethod = 'get';
        $this->routeUrl = $this->routeName;
        $this->menu = 0;
        $this->weight = 1;
        $this->group = 1;
        $this->jail = true;
        $this->menuable = true;
    }

    /**
     * Set the route title.
     *
     * @param $title
     * @return DynamicRoute
     */
    public function setTitle($title)
    {
        return $this->setField('title', $title);
    }

    /**
     * Set the route action.
     *
     * @param $action
     * @return DynamicRoute
     */
    public function setRouteAction($action)
    {
        return $this->setField('routeAction', $action);
    }

    /**
     * Set the route method.
     *
     * @param $method
     * @return DynamicRoute
     */
    public function setRouteMethod($method)
    {
        return $this->setField('routeMethod', $method);
    }

    /**
     * Set the route url.
     *
     * @param $url
     * @return DynamicRoute
     */
    public function setRouteUrl($url)
    {
        return $this->setField('routeUrl', $url);
    }

    /**
     * Set the menu id.
     *
     * @param $menu
     * @return DynamicRoute
     */
    public function setMenu($menu)
    {
        return $this->setField('menu', $menu);
    }

    /**
     * Set the menu weight.
     *
     * @param $weight
     * @return DynamicRoute
     */
    public function setWeight($weight)
    {
        return $this->setField('weight', $weight);
    }

    /**
     * Set the group id.
     *
     * @param $group
     * @return DynamicRoute
     */
    public function setGroup($group)
    {
        return $this->setField('group', $group);
    }

    /**
     * Set jail viewable to true.
     *
     * @param bool $viewable
     * @return DynamicRoute
     */
    public function mayViewPageWhenInJail($viewable = false)
    {
        return $this->setField('jail', $viewable);
    }

    /**
     * Set jail viewable to false.
     *
     * @return DynamicRoute
     */
    public function mayNotViewPageWhenInJail()
    {
        return $this->mayViewPageWhenInJail(true);
    }

    /**
     * Set the menu as editable.
     *
     * @param bool $menuable
     * @return DynamicRoute
     */
    public function menuable($menuable = true)
    {
        return $this->setField('menuable', $menuable);
    }

    /**
     * Set the menu as not editable.
     *
     * @return DynamicRoute
     */
    public function notMenuable()
    {
        return $this->menuable(false);
    }

    /**
     * Set the given field to the given value.
     *
     * @param $field
     * @param $value
     * @return DynamicRoute
     */
    private function setField($field, $value)
    {
        $this->$field = $value;

        return $this;
    }
}