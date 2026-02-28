<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Page;
use App\Helpers\GlobalHelper;
use App\Models\UserManagement\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(
            [
                'slug' => 'administrator',
                'name' => 'Admin',
                'order' => 1,
                'scope' => "limited",
                'description' => 'Administrator with full access',
                'created_by' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $userRole = Role::updateOrCreate(
            [
                'slug' => 'users',
                'name' => 'User',
                'order' => 2,
                'scope' => "limited",
                'description' => 'Regular user with limited access',
                'created_by' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Users
        $adminUser = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'display_name' => 'Admin',
                'password' => Hash::make('Admin@2024'),
                'locale' => 'en',
                'role_id' => DB::table('roles')->where('slug', 'administrator')->value('id'),
                'created_by' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $regularUser = User::updateOrCreate(
            [
                'email' => 'user@wintech.com.kh',
                'username' => 'user',
                'display_name' => 'User',
                'password' => Hash::make('password'),
                'locale' => 'en',
                'role_id' => DB::table('roles')->where('slug', 'users')->value('id'),
                'created_by' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Modules
        $usreManagement = Module::updateOrCreate(
            [
                'slug' => 'usres-management',
                'name_en' => 'Users Management',
                'name_kh' => 'ការគ្រប់គ្រងអ្នកប្រើប្រាស់',
                'top_slug' => 'usres-management',
                'icon' => 'ph ph-house',
                'order' => 1,
                'type' => 'module',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Pages
        Page::updateOrCreate(
            [
                'slug' => 'dashboard',
                'module_id' => null,
                'sub_module_id' => null,
                'name_en' => 'Dashboard',
                'name_kh' => 'ផ្ទារគ្រប់គ្រង',
                'icon' => 'ph ph-house',
                'order' => 1,
                'route_name' => 'dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $pages = [
            [
                'slug' => 'usres',
                'module_id' => $usreManagement->id,
                'sub_module_id' => null,
                'name_en' => 'Users',
                'name_kh' => 'អ្នកប្រើប្រាស់',
                'icon' => 'ph ph-user-gear',
                'order' => 1,
                'route_name' => 'users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'role',
                'module_id' => $usreManagement->id,
                'sub_module_id' => null,
                'name_en' => 'Roles',
                'name_kh' => 'តួនាទី',
                'icon' => 'ph ph-person-simple-circle',
                'order' => 2,
                'route_name' => 'roles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $helper = new GlobalHelper();
        $helper->insertPage($usreManagement, [], $pages);

    }
}
