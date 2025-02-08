<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'guard_name' => 'admin',
                'title' => ['ar' => 'مدير عام', 'en' => 'Super-Admin'],
                'name' => 'super_admin',
            ],
            [
                'guard_name' => 'admin',
                'title' => ['ar' => 'مدير', 'en' => 'Admin'],
                'name' => 'admin',
            ],
            [
                'guard_name' => 'admin',
                'title' => ['ar' => 'محصل', 'en' => 'Collector'],
                'name' => 'collector',
            ],
            [
                'guard_name' => 'admin',
                'title' => ['ar' => 'محاسب', 'en' => 'Accountant'],
                'name' => 'accountant',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'guard_name' => $role['guard_name'],
                    'title' => $role['title'],
                ]
            );
        }
    }
}
