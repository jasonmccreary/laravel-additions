<?php

namespace Tests;

use JMac\Additions\AdditionsServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    protected function getPackageProviders($app)
    {
        return [
            AdditionsServiceProvider::class,
        ];
    }

    public function basePath($path = ''): string
    {
        return __DIR__.'/../'.$path;
    }
}
