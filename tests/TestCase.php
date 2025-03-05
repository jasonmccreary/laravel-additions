<?php

namespace Tests;

use JMac\Additions\AdditionsServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AdditionsServiceProvider::class,
        ];
    }
}
