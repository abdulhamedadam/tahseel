<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard and Test route permissions
            [
                'name' => 'view_dashboard',
                'title' => ['ar' => 'عرض لوحة القيادة', 'en' => 'view dashboard'],
                'guard_name' => 'admin',
            ],

            // Sarf_band permissions
            [
                'name' => 'view_sarf_band',
                'title' => ['ar' => 'عرض بنود الصرف', 'en' => 'view sarf_band'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_sarf_band',
                'title' => ['ar' => 'اضافة بند الصرف', 'en' => 'add sarf_band'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'edit_sarf_band',
                'title' => ['ar' => 'تعديل بند الصرف', 'en' => 'edit sarf_band'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_sarf_band',
                'title' => ['ar' => 'حذف بند الصرف', 'en' => 'delete sarf_band'],
                'guard_name' => 'admin',
            ],

            // Employees permissions
            [
                'name' => 'view_employees',
                'title' => ['ar' => 'عرض الموظفين', 'en' => 'view employees'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_employee',
                'title' => ['ar' => 'اضافة موظف', 'en' => 'add employee'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'edit_employee',
                'title' => ['ar' => 'تعديل موظف', 'en' => 'edit employee'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_employee',
                'title' => ['ar' => 'حذف موظف', 'en' => 'delete employee'],
                'guard_name' => 'admin',
            ],

            // Clients permissions
            [
                'name' => 'list_clients',
                'title' => ['ar' => 'عرض العملاء', 'en' => 'list clients'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_client',
                'title' => ['ar' => 'اضافة عميل', 'en' => 'create client'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_client',
                'title' => ['ar' => 'تعديل عميل', 'en' => 'update client'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_client',
                'title' => ['ar' => 'حذف عميل', 'en' => 'delete client'],
                'guard_name' => 'admin',
            ],

            // Roles permissions
            [
                'name' => 'list_roles',
                'title' => ['ar' => 'عرض الادوار', 'en' => 'list roles'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_role',
                'title' => ['ar' => 'اضافة دور', 'en' => 'create role'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_role',
                'title' => ['ar' => 'تعديل دور', 'en' => 'update role'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_role',
                'title' => ['ar' => 'حذف دور', 'en' => 'delete role'],
                'guard_name' => 'admin',
            ],

            // Masrofat permissions
            [
                'name' => 'list_masrofat',
                'title' => ['ar' => 'عرض المصروفات', 'en' => 'list expenses'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_masrofat',
                'title' => ['ar' => 'اضافة مصروف', 'en' => 'create expense'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_masrofat',
                'title' => ['ar' => 'تعديل مصروف', 'en' => 'update expense'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_masrofat',
                'title' => ['ar' => 'حذف مصروف', 'en' => 'delete expense'],
                'guard_name' => 'admin',
            ],

            // Eradat permissions
            [
                'name' => 'list_eradat',
                'title' => ['ar' => 'عرض الايرادات', 'en' => 'list revenue'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_eradat',
                'title' => ['ar' => 'اضافة ايراد', 'en' => 'create revenue'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_eradat',
                'title' => ['ar' => 'تعديل ايراد', 'en' => 'update revenue'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_eradat',
                'title' => ['ar' => 'حذف ايراد', 'en' => 'delete revenue'],
                'guard_name' => 'admin',
            ],

            // Users permissions
            [
                'name' => 'list_users',
                'title' => ['ar' => 'عرض المستخدمين', 'en' => 'list users'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_user',
                'title' => ['ar' => 'اضافة مستخدم', 'en' => 'create user'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_user',
                'title' => ['ar' => 'تعديل مستخدم', 'en' => 'update user'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_user',
                'title' => ['ar' => 'حذف مستخدم', 'en' => 'delete user'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'change_user_status',
                'title' => ['ar' => 'تغيير حالة المستخدم', 'en' => 'change user status'],
                'guard_name' => 'admin',
            ],

        ];

        foreach ($permissions as $permissionData) {
            Permission::create([
                'name' => $permissionData['name'],
                'title' => $permissionData['title'],
                'guard_name' => $permissionData['guard_name'],
            ]);
        }
    }
}

