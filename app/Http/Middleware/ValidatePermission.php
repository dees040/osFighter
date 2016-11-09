<?php

namespace App\Http\Middleware;

use Closure;

class ValidatePermission
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
        } else if ($this->isAdminSection($request)) {
            game()->abortUnlessIsAdmin();
        } else {
            game()->abortUnlessHasPermissionForPage();
        }

        return $next($request);
    }

    /**
     * Check if the user is visiting the admin section.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function isAdminSection($request)
    {
        return $request->is('admin', 'admin/*');
    }
}
