<?php

namespace App\Library\Routing;

use Illuminate\Support\HtmlString;
use App\Models\Route as DynamicRoute;
use App\Exceptions\DynamicRouteNotDefined;
use Illuminate\Support\Facades\Route as BaseRoute;

class DynamicRouter
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * Rules which can apply to a route.
     *
     * @var array
     */
    private $rules = [
        'applyGroupRule',
        'applyJailRule',
        'applyFlyRule',
        'applyFamilyRule',
    ];

    /**
     * DynamicRouter constructor.
     */
    public function __construct()
    {
        $this->setRoutes();
    }

    /**
     * Apply all the rules to a route.
     *
     * @param string $name
     * @return bool
     */
    public function applyRulesToRoute($name)
    {
        $route = $this->getRoute($name);

        // If the route is null, we will assume the route is a none dynamic route
        // and we will let the request continue.
        if (is_null($route)) {
            return true;
        }

        foreach ($this->rules as $rule) {
            $response = call_user_func_array([$this, $rule], [$route]);

            if (! is_null($response)) {
                return $response;
            }
        }

        return true;
    }

    /**
     * Apply the group rule.
     *
     * @param DynamicRoute $route
     * @return null
     */
    protected function applyGroupRule(DynamicRoute $route)
    {
        if (request()->is('admin', 'admin/*')) {
            game()->abortUnlessIsAdmin();
        } else {
            game()->abortUnlessIsInGroup($route->rules->group);
        }

        return null;
    }

    /**
     * Apply the jail rule.
     *
     * @param DynamicRoute $route
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function applyJailRule(DynamicRoute $route)
    {
        if (! $route->rules->jail_viewable && user()->isInJail()) {
            return redirect()->route('jail.index')
                ->with('m_warning', 'You can visit this page, because you\'re in jail.');
        }

        return null;
    }

    /**
     * Apply the jail rule.
     *
     * @param DynamicRoute $route
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function applyFlyRule(DynamicRoute $route)
    {
        if (request()->route()->getName() == 'airport.index') {
            return null;
        }

        if (! $route->rules->fly_viewable && user()->isFlying()) {
            return redirect()->route('airport.index');
        }

        return null;
    }

    /**
     * Apply the family rule.
     *
     * @param DynamicRoute $route
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function applyFamilyRule(DynamicRoute $route)
    {
        if ($route->rules->family_viewable && ! user()->isInFamily()) {
            return redirect()->route('families.index')
                ->with('m_warning', 'You\'re not in a family.');
        }

        return null;
    }

    /**
     * Get the route by name.
     *
     * @param $name
     * @return mixed
     * @throws DynamicRouteNotDefined
     */
    private function getRoute($name)
    {
        if (array_key_exists($name, $this->routes)) {
            return $this->routes[$name];
        }

        return null;
    }

    /**
     * Build all the dynamic routes.
     *
     * @return DynamicRouter
     */
    public function build()
    {
        foreach ($this->routes as $route) {
            $this->callRoute($route)->name($route->name);
        }

        return $this;
    }

    /**
     * Call a dynamic route.
     *
     * @param DynamicRoute $route
     * @return \Illuminate\Routing\Route
     */
    public function callRoute(DynamicRoute $route)
    {
        return call_user_func_array(
            [BaseRoute::class, $route->method],
            [$route->url, $route->action]
        );
    }

    /**
     * Set the dynamic routes from the database.
     */
    private function setRoutes()
    {
        if (\Schema::hasTable('routes')) {
            $routes = DynamicRoute::with('rules')->get();

            foreach ($routes as $route) {
                $this->routes[$route->name] = $route;
            }
        }
    }

    /**
     * Generate an url.
     *
     * @param string $name
     * @param \Illuminate\Database\Eloquent\Model $binding
     * @param bool $onlyUrl
     * @return HtmlString|string
     * @throws DynamicRouteNotDefined
     */
    public function generateUrl($name, $binding = null, $onlyUrl = null)
    {
        if (! array_key_exists($name, $this->routes)) {
            throw new DynamicRouteNotDefined(sprintf("Dynamic route [%s] not defined", $name));
        }

        $url = $this->getFullUrl($this->routes[$name], $binding);

        if (is_null($onlyUrl)) {
            return $url;
        }

        return new HtmlString("<a href='$url'>$onlyUrl</a>");
    }

    /**
     * Get the full url to a route.
     *
     * @param DynamicRoute $route
     * @param $binding
     * @return string
     */
    private function getFullUrl(DynamicRoute $route, $binding)
    {
        if ($route->rules->hasBindings()) {
            return route($route->name, $binding);
        }

        return route($route->name);
    }
}