<?php

namespace browner12\reauthenticate;

use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testServiceProvider()
    {
        $provider = new ReauthenticateServiceProvider($this->app);

        $this->assertTrue(method_exists($provider, 'register'));
        $this->assertTrue(method_exists($provider, 'boot'));

        $this->assertEmpty($provider->register());
        $this->assertEmpty($provider->boot());
    }

    public function testConfig()
    {
        $configFile = sprintf(
            '%s/../src/config/%s',
            __DIR__,
            'reauthenticate.php'
        );
        $this->assertFileExists($configFile);
        $config = $this->config($configFile);

        $this->assertArrayHasKey('timeout', $config);
        $this->assertArrayHasKey('reset', $config);
        $this->assertArrayHasKey('route', $config);
    }

    private function config($configFile)
    {
        return require_once $configFile;
    }
}
