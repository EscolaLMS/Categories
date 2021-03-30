<?php

namespace EscolaLms\Categories\Tests\API;

use EscolaLms\Categories\Models\Category;
use EscolaLms\Categories\Tests\TestCase;
use EscolaLms\Core\Models\User;
use Laravel\Passport\Passport;


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

    public function testCategoryUpdate(): void
    {
        $user = User::where(['email' => 'test@test.test'])->first();
        Passport::actingAs($user, ['categories']);

        $category = Category::factory()->create();
        $this->response = $this->startSession()->json('PUT', '/api/categories/' . $category->getKey(), [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryCreate(): void
    {
        $user = User::where(['email' => 'test@test.test'])->first();
        Passport::actingAs($user, ['categories']);

        $this->response = $this->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryDestroy(): void
    {
        $user = User::where(['email' => 'test@test.test'])->first();
        Passport::actingAs($user, ['categories']);

        $category = Category::factory()->create();
        $this->response = $this->json('DELETE', '/api/categories/' . $category->getKey());
        $this->response->assertOk();
    }
}
