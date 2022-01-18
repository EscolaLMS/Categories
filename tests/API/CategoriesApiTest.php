<?php

namespace EscolaLms\Categories\Tests\API;

use EscolaLms\Categories\Database\Seeders\CategoriesPermissionSeeder;
use EscolaLms\Categories\Enums\CategoriesPermissionsEnum;
use EscolaLms\Categories\Models\Category;
use EscolaLms\Categories\Tests\TestCase;
use EscolaLms\Core\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CategoriesApiTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategoriesPermissionSeeder::class);

        $this->user = $this->createAdmin();
    }

    public function testCategoryCannotIndex(): void
    {
        $user = $this->createStudent();

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories');

        $this->response->assertForbidden();
    }

    public function testCategoriesIndex(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_LIST);

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories');

        $this->response->assertOk();
    }

    public function testCategoryCannotIndexTree(): void
    {
        $user = $this->createStudent();

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/tree');

        $this->response->assertForbidden();
    }

    public function testCategoriesIndexTree(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_LIST);

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/tree');

        $this->response->assertOk();
    }

    public function testCategoryCannotShow(): void
    {
        $user = $this->createStudent();
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/' . $category->getKey());

        $this->response->assertForbidden();
    }

    public function testCategoryShow(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_READ);
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/' . $category->getKey());

        $this->response->assertOk();
    }

    public function testCategoryShowWithParent(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_READ);
        $category_parent = Category::factory()->create();
        $category_child = Category::factory()->create(['parent_id' => $category_parent->getKey()]);

        $this->assertEquals($category_parent->getKey(), $category_child->parent->getKey());

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/' . $category_child->getKey());

        $this->response->assertOk();
        $this->response->assertJsonFragment([
            'name_with_breadcrumbs' => ucfirst($category_parent->name) . '. ' . ucfirst($category_child->name),
        ]);
    }

    public function testCategoryShowWithCycleInAncestors(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_READ);
        $category_parent = Category::factory()->create();
        $category_child = Category::factory()->create(['parent_id' => $category_parent->getKey()]);
        $category_parent->update(['parent_id' => $category_child->getKey()]);

        $this->assertEquals($category_child->getKey(), $category_parent->parent->getKey());
        $this->assertEquals($category_parent->getKey(), $category_child->parent->getKey());

        $this->response = $this->actingAs($user, 'api')->json('GET', '/api/categories/' . $category_child->getKey());

        $this->response->assertOk();
        $this->response->assertJsonFragment([
            'name_with_breadcrumbs' => ucfirst($category_parent->name) . '. ' . ucfirst($category_child->name),
        ]);
    }

    public function testCategoryUpdate(): void
    {
        $user = User::factory(['email' => 'category@email.com'])->make();
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('PUT', '/api/categories/' . $category->getKey(), [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);

        $this->response->assertForbidden();
    }

    public function testCategoryCreate(): void
    {
        $user = User::factory(['email' => 'category@email.com'])->make();

        $this->response = $this->actingAs($user, 'api')->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);

        $this->response->assertForbidden();
    }

    public function testCategoryDestroy(): void
    {
        $user = User::factory(['email' => 'category@email.com'])->make();
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('DELETE', '/api/categories/' . $category->getKey());

        $this->response->assertForbidden();
    }

    public function testCategoryUpdateUserAdmin(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_UPDATE);

        $category = Category::factory()->create();
        $this->response = $this->actingAs($this->user, 'api')->json('PUT', '/api/categories/' . $category->getKey(), [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryCreateUserAdmin(): void
    {
        $user = $this->createAdmin();
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_CREATE);

        $this->response = $this->actingAs($this->user, 'api')->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);
        $this->response->assertOk();
    }

    public function testCategoryDestroyUserAdmin(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->guard_name = 'api';
        $user->givePermissionTo(CategoriesPermissionsEnum::CATEGORY_DELETE);
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('DELETE', '/api/categories/' . $category->getKey());

        $this->response->assertOk();
    }

    public function testCategoryUpdateUserStudent(): void
    {
        $user = $this->createStudent();
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
        $user = $this->createStudent();

        $this->response = $this->actingAs($user, 'api')->json('POST', '/api/categories', [
            'name' => 'Category 123',
            'icon_class' => 'fa-business-time',
            'is_active' => true
        ]);

        $this->response->assertForbidden();
    }

    public function testCategoryDestroyUserStudent(): void
    {
        $user = $this->createStudent();
        $category = Category::factory()->create();

        $this->response = $this->actingAs($user, 'api')->json('DELETE', '/api/categories/' . $category->getKey());

        $this->response->assertForbidden();
    }

    private function createAdmin()
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->guard_name = 'api';
        $user->assignRole('admin');

        return $user;
    }

    private function createStudent()
    {
        $user = config('auth.providers.users.model')::factory()->create();
        $user->assignRole('student');

        return $user;
    }
}
