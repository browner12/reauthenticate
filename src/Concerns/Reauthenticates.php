<?php

namespace browner12\reauthenticate\Concerns;

use Illuminate\Support\Facades\Hash;

trait Reauthenticates
{
    /**
     * @param string $guess
     * @param string $password
     * @return string|bool
     */
    protected function checkReauthorizationPassword($guess, $password)
    {
        //good password
        if (Hash::check($guess, $password)) {

            //reset timer
            $this->resetReauthorizationTimer();

            //send to requested page
            return session()->get('reauthenticate.requested_url', '/');
        }

        //bad password
        return false;
    }

    /**
     * @return void
     */
    protected function resetReauthorizationTimer()
    {
        session()->put('reauthenticate.last_authentication', strtotime('now'));
    }
}
