<?php

namespace EscolaLms\Categories\Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;

class CategoriesPermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::findOrCreate('admin', 'api');
        $tutor = Role::findOrCreate('tutor', 'api');

        Permission::findOrCreate('update category', 'api');
        Permission::findOrCreate('delete category', 'api');
        Permission::findOrCreate('create category', 'api');

        $admin->givePermissionTo(['update category', 'delete category', 'create category']);
        $tutor->givePermissionTo(['update category', 'delete category', 'create category']);
    }
}
