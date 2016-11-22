<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserIsFamilyFree
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
        if (user()->isInFamily()) {
            return redirect()->route('families.index')
                ->with('m_warning', 'You can\'t visit this page because you are in a family.');
        }

        return $next($request);
    }
}
