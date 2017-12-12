<?php

namespace browner12\reauthenticate;

use Closure;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws \RuntimeException
     */
    public function handle($request, Closure $next)
    {
        //only check after set number of second
        if ($this->lastAuth($request) > config('reauthenticate.timeout', 3600)) {

            //store the requested url
            $request->session()->put('reauthenticate.requested_url', $request->route()->uri());

            //send to reauthentication page
            return redirect()->route('reauthenticate');
        }

        //reset timer
        if (config('reauthenticate.reset', true)) {
            $request->session()->put('reauthenticate.last_authentication', strtotime('now'));
        }

        //next layer
        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return int
     * @throws \RuntimeException
     */
    private function lastAuth($request)
    {
        return time() - $request->session()->get('reauthenticate.last_authentication', 0);
    }
}
