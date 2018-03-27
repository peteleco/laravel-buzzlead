<?php

namespace Peteleco\Buzzlead\Http\Middleware;

use Carbon\Carbon;
use Closure;

class ExpiryAffiliateCookie
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure                   $next
     */
    public function handle($request, Closure $next)
    {

        if ($request->hasCookie($originalCookie = config('buzzlead.cookie.original'))) {

            if (! $request->hasCookie('buzzlead_affiliate')) {
                return $next($request)
                    ->withCookie(\Cookie::forget($originalCookie))
                    ->withCookie($this->makeBuzzleadAffiliateCookie($request->cookie($originalCookie)));
            }
        }

        return $next($request);
    }

    private function makeBuzzleadAffiliateCookie($value)
    {
        $updateCookie = config('buzzlead.cookie.updated');

        return \Cookie::make($updateCookie, $value, config('buzzlead.cookie.expiry'), null, null, false, false);
    }
}