<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;

class LoginUserActivity
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
        if(Auth::check()){
            $expirAt=Carbon::now()->addSeconds(10);
            Cache::put('user-is-online'.Auth::user()->id,true, $expirAt);
        }
        return $next($request);
    }
}
