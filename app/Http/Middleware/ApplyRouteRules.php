<?php

namespace App\Http\Middleware;

use Closure;
use DynamicRouter;
use Illuminate\Http\RedirectResponse;

class ApplyRouteRules
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('logout')) {
            return $next($request);
        }

        $response = $this->getResponse($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $next($request);
    }

    /**
     * Get the response.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function getResponse($request)
    {
        return DynamicRouter::applyRulesToRoute($request->route()->getName());
    }
}
