<?php

namespace browner12\reauthenticate;

use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\TestCase;

class ConcernsReauthenticatesTest extends TestCase
{
    public function testConcernsReauthenticates()
    {
        $trait = new \ReflectionClass(Concerns\Reauthenticates::class);
        $this->assertTrue($trait->isTrait());
        $this->assertTrue($trait->hasMethod('checkReauthenticationPassword'));
        $this->assertTrue($trait->hasMethod('resetReauthenticationTimer'));
    }

    public function testReauthenticationPassword()
    {
        $trait = new ConcernReauthenticationTestHelper();

        $this->assertFalse($trait->check('x', 'y'));
        $this->assertFalse($trait->check('y', 'y'));

        $guess = 'password';
        $password = Hash::make($guess);

        $this->assertEquals('/', $trait->check($guess, $password));
    }

    public function testResetReauthenticationTimer()
    {
        $trait = new ConcernReauthenticationTestHelper();

        $lastAuthKey = 'reauthenticate.last_authentication';

        session()->put($lastAuthKey, 0);

        $trait->reset();

        $this->assertNotEquals(session()->get($lastAuthKey), 0);
    }
}
