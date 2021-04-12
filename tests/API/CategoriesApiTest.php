<?php

namespace EscolaLms\Categories\Tests\API;

use EscolaLms\Categories\Models\Category;
use EscolaLms\Categories\Tests\TestCase;
use EscolaLms\Core\Models\User;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;


class CategoriesApiTest extends TestCase
{

    public function testCategoriesIndex(): void
    {
        $this->response = $this->json('GET', '/api/categories');
        $this->response->assertOk();
    }

    public function testCategoriesIndexTree(): void
    {
        $this->response = $this->json('GET', '/api/categories/tree');
        $this->response->assertOk();
    }

    public function testCategoryShow(): void
    {
        $category = Category::factory()->create();

        $this->response = $this->json('GET', '/api/categories/' . $category->getKey());
        $this->response->assertOk();
    }

    public function testCategoryUpdateUserAdmin(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('admin');
        $category = Category::factory()->create();
        $this->response = $this->actingAs($user, 'api')->json('PUT', '/api/categories/' . $category->getKey(), [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryCreateUserAdmin(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('admin');
        $this->response = $this->actingAs($user, 'api')->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryDestroyUserAdmin(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('admin');
        $category = Category::factory()->create();
        $this->response = $this->actingAs($user, 'api')->json('DELETE', '/api/categories/' . $category->getKey());
        $this->response->assertOk();
    }

    public function testCategoryUpdateUserStudent(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('student');
        $category = Category::factory()->create();
        $this->response = $this->actingAs($user, 'api')->json('PUT', '/api/categories/' . $category->getKey(), [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertForbidden();
    }

    public function testCategoryCreateUserStudent(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('student');
        $this->response = $this->actingAs($user, 'api')->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertForbidden();
    }

    public function testCategoryDestroyUserStudent(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('student');
        $category = Category::factory()->create();
        $this->response = $this->actingAs($user, 'api')->json('DELETE', '/api/categories/' . $category->getKey());
        $this->response->assertForbidden();
    }
}
