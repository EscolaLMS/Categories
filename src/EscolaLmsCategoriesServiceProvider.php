<?php

namespace EscolaLms\Categories;

use EscolaLms\Categories\Repositories\CategoriesRepository;
use EscolaLms\Categories\Repositories\Contracts\CategoriesRepositoryContract;
use EscolaLms\Categories\Services\CategoryService;
use EscolaLms\Categories\Services\Contracts\CategoryServiceContracts;
use EscolaLms\Core\Providers\Injectable;
use Illuminate\Support\ServiceProvider;

class EscolaLmsCategoriesServiceProvider extends ServiceProvider
{
    use Injectable;

    private const CONTRACTS = [
        CategoriesRepositoryContract::class => CategoriesRepository::class,
        CategoryServiceContracts::class => CategoryService::class
    ];

    public function register()
    {
        $this->injectContract(self::CONTRACTS);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->app['router']->aliasMiddleware('role', \Spatie\Permission\Middlewares\RoleMiddleware::class);
    }
}
