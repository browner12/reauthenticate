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
    protected function checkReauthenticationPassword($guess, $password)
    {
        //good password
        if (Hash::check($guess, $password)) {

            //reset timer
            $this->resetReauthenticationTimer();

            //send to requested page
            return session()->get('reauthenticate.requested_url', '/');
        }

        //bad password
        return false;
    }

    /**
     * @return void
     */
    protected function resetReauthenticationTimer()
    {
        session()->put('reauthenticate.last_authentication', strtotime('now'));
    }
}
