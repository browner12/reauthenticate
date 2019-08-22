<?php

namespace browner12\reauthenticate;

use Orchestra\Testbench\TestCase;

class ReauthenticateTest extends TestCase
{

    public function testHandle()
    {
        $reAuth = new Reauthenticate();

        $this->assertTrue(method_exists($reAuth, 'handle'));
    }
}
