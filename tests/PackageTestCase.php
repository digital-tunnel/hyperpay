<?php

namespace DigitalTunnel\HyperPay\Tests;


use DigitalTunnel\HyperPay\Providers\PackageServiceProvider;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PackageServiceProvider::class,
        ];
    }
}
