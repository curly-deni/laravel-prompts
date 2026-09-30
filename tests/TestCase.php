<?php

namespace Aesis\Prompts\Tests;

use Aesis\Prompts\PromptsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PromptsServiceProvider::class,
        ];
    }
}
