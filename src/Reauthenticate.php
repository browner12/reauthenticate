<?php

namespace App\Http\Middleware;

use Closure;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //only check after set number of second
        if ((strtotime('now') - $request->session()->get('reauthenticate.last_authentication', 0)) > config('reauthenticate.timeout', 3600)) {

            //store the requested url
            $request->session()->put('reauthenticate.requested_url', $request->route()->uri());

            //send to reauthorization page
            return redirect()->route('reauthenticate');
        }

        //reset timer
        if (config('reauthenticate.reset', true)) {
            $request->session()->put('reauthenticate.last_authentication', strtotime('now'));
        }

        //next layer
        return $next($request);
    }
}
