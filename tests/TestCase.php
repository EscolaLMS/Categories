<?php

namespace EscolaLms\Categories\Tests;

use EscolaLms\Categories\EscolaLmsCategoriesServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    protected function getPackageProviders($app)
    {
        return [EscolaLmsCategoriesServiceProvider::class];
    }
}
