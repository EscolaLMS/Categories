# Categories

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/Categories/)
[![codecov](https://codecov.io/gh/EscolaLMS/Categories/branch/main/graph/badge.svg?token=ci4VPQbrOI)](https://codecov.io/gh/EscolaLMS/Categories)
[![phpunit](https://github.com/EscolaLMS/Categories/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Categories/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/courses)](https://packagist.org/packages/escolalms/categories)
[![downloads](https://img.shields.io/packagist/v/escolalms/courses)](https://packagist.org/packages/escolalms/categories)
[![downloads](https://img.shields.io/packagist/l/escolalms/courses)](https://packagist.org/packages/escolalms/categories)


## Features

The lib allows categories

- adding a category
- generate slug for category
- edit category
- delete category
- show list categories

See [Swagger](https://escolalms.github.io/Categories/) documented endpoints.

Some [tests](tests) can also be a great point of start.

To play the content you can use [EscolaLMS Categories](https://github.com/EscolaLMS/Categories)

## Install

1. `composer require escolalms/categories`
2. `php artisan migrate`

### Seeder

You can seed library and content with build-in seeders that are accessible with

- `php artisan category-permissions:seed` to add permissions
- `php artisan db:seed --class="\EscolaLms\Categories\Database\Seeders\CategoriesSeeder"`
