<?php

namespace browner12\reauthenticate;

class ConcernReauthenticationTestHelper
{
    use Concerns\Reauthenticates;

    public function check($guess, $password)
    {
        return $this->checkReauthenticationPassword($guess, $password);
    }

    public function reset()
    {
        $this->resetReauthenticationTimer();
    }
}
