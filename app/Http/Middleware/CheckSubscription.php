<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckSubscription
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
    	if(isset(Auth::user()->owner->stripe_id) && Auth::user()->owner->stripe_id==Auth::user()->username){
    		return $next($request);
    	}else{

    		if(!Auth::user()->subscribed('main')){
    			return redirect('subscription');
    		}
    	}
    	return $next($request);
    }
}
