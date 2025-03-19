<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'update categories']);
        Permission::create(['name' => 'delete categories']);

        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'update products']);
        Permission::create(['name' => 'delete products']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'create categories', 'update categories', 'delete categories',
            'create products', 'update products' , 'delete products'
        ]);

        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo(['create products', 'update products' , 'delete products']);

        $user = User::find(1);
        $user->assignRole($admin);

        $user = User::factory()->create([
            'name' => 'customer',
            'email' => 'customer@gmail.com',
        ]);

        $user->assignRole($customer);
    }
}
