<?php

namespace App\Http\Middleware;

use Closure;

class RedirectUserToAirportIfFlying
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
        if (! $request->is('airport') && user()->isFlying() && ! user()->isInAdminGroup()) {
            return redirect()->route('airport.index');
        }

        return $next($request);
    }
}
