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
            [
                'name' => 'view_dashboard',
                'title' => json_encode(['ar' => 'عرض لوحة القيادة', 'en' => 'view dashboard'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_sarf_band',
                'title' => json_encode(['ar' => 'عرض بنود الصرف', 'en' => 'view sarf_band'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_sarf_band',
                'title' => json_encode(['ar' => 'اضافة بند الصرف', 'en' => 'add sarf_band'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'edit_sarf_band',
                'title' => json_encode(['ar' => 'تعديل بند الصرف', 'en' => 'edit sarf_band'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_sarf_band',
                'title' => json_encode(['ar' => 'حذف بند الصرف', 'en' => 'delete sarf_band'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_employees',
                'title' => json_encode(['ar' => 'عرض الموظفين', 'en' => 'view employees'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_employee',
                'title' => json_encode(['ar' => 'اضافة موظف', 'en' => 'add employee'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'edit_employee',
                'title' => json_encode(['ar' => 'تعديل موظف', 'en' => 'edit employee'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_employee',
                'title' => json_encode(['ar' => 'حذف موظف', 'en' => 'delete employee'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'list_clients',
                'title' => json_encode(['ar' => 'عرض العملاء', 'en' => 'list clients'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_client',
                'title' => json_encode(['ar' => 'اضافة عميل', 'en' => 'create client'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_client',
                'title' => json_encode(['ar' => 'تعديل عميل', 'en' => 'update client'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_client',
                'title' => json_encode(['ar' => 'حذف عميل', 'en' => 'delete client'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'list_roles',
                'title' => json_encode(['ar' => 'عرض الادوار', 'en' => 'list roles'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_role',
                'title' => json_encode(['ar' => 'اضافة دور', 'en' => 'create role'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_role',
                'title' => json_encode(['ar' => 'تعديل دور', 'en' => 'update role'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_role',
                'title' => json_encode(['ar' => 'حذف دور', 'en' => 'delete role'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'list_masrofat',
                'title' => json_encode(['ar' => 'عرض المصروفات', 'en' => 'list expenses'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_masrofat',
                'title' => json_encode(['ar' => 'اضافة مصروف', 'en' => 'create expense'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_masrofat',
                'title' => json_encode(['ar' => 'تعديل مصروف', 'en' => 'update expense'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_masrofat',
                'title' => json_encode(['ar' => 'حذف مصروف', 'en' => 'delete expense'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'list_eradat',
                'title' => json_encode(['ar' => 'عرض الايرادات', 'en' => 'list revenue'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_eradat',
                'title' => json_encode(['ar' => 'اضافة ايراد', 'en' => 'create revenue'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_eradat',
                'title' => json_encode(['ar' => 'تعديل ايراد', 'en' => 'update revenue'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_eradat',
                'title' => json_encode(['ar' => 'حذف ايراد', 'en' => 'delete revenue'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'list_users',
                'title' => json_encode(['ar' => 'عرض المستخدمين', 'en' => 'list users'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'create_user',
                'title' => json_encode(['ar' => 'اضافة مستخدم', 'en' => 'create user'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'update_user',
                'title' => json_encode(['ar' => 'تعديل مستخدم', 'en' => 'update user'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_user',
                'title' => json_encode(['ar' => 'حذف مستخدم', 'en' => 'delete user'], JSON_UNESCAPED_UNICODE),
                'guard_name' => 'admin',
            ],
            [
                'name' => 'change_user_status',
                'title' => json_encode(['ar' => 'تغيير حالة المستخدم', 'en' => 'change user status'], JSON_UNESCAPED_UNICODE),
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

