<?php

namespace Peteleco\Buzzlead\Http\Middleware;

use Closure;
use Peteleco\Buzzlead\Helpers\Buzzlead;

/**
 * Seta o código do afiliado caso não tenha sido
 * setado no login
 * Class SetAffiliateCodeCookie
 *
 * @package Peteleco\Buzzlead\Http\Middleware
 */
class SetAffiliateCodeCookie
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure                   $next
     */
    public function handle($request, Closure $next)
    {
        if (Buzzlead::isAmbassadorEnabled()) {
            if ($request->user()
                && ! $request->hasCookie('affiliate_code')
                && $request->user()->isCustomer()
                && $request->user()->isAffiliated()) {
                return $next($request)->withCookie($this->makeAffiliateCookie($request->user()->affiliate_code));
            }
        }

        return $next($request);
    }

    private function makeAffiliateCookie($affiliateCode)
    {
        return \Cookie::make('affiliate_code', $affiliateCode, config('buzzlead.cookie.expiry'), null, null, false,
            false);
    }
}