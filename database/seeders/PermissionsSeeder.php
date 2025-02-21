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

            // Subscriptions permissions
            [
                'name' => 'view_subscriptions',
                'title' => ['ar' => 'عرض الاشتراكات', 'en' => 'view subscriptions'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_subscription',
                'title' => ['ar' => 'اضافة اشتراك', 'en' => 'add subscription'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'edit_subscription',
                'title' => ['ar' => 'تعديل اشتراك', 'en' => 'edit subscription'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_subscription',
                'title' => ['ar' => 'حذف اشتراك', 'en' => 'delete subscription'],
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
            [
                'name' => 'view_employee_files',
                'title' => ['ar' => 'عرض ملفات الموظف', 'en' => 'view employee files'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_employee_files',
                'title' => ['ar' => 'اضافة ملفات للموظف', 'en' => 'add employee files'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'read_employee_file',
                'title' => ['ar' => 'قراءة ملف الموظف', 'en' => 'read employee file'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'download_employee_file',
                'title' => ['ar' => 'تحميل ملف الموظف', 'en' => 'download employee file'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_employee_file',
                'title' => ['ar' => 'حذف ملف الموظف', 'en' => 'delete employee file'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_employee_details',
                'title' => ['ar' => 'عرض تفاصيل الموظف', 'en' => 'view employee details'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_employee_masrofat',
                'title' => ['ar' => 'عرض مصروفات الموظف', 'en' => 'view employee masrofat'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_employee_masrofat',
                'title' => ['ar' => 'اضافة مصروفات للموظف', 'en' => 'add employee masrofat'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_employee_masrofat',
                'title' => ['ar' => 'حذف مصروفات الموظف', 'en' => 'delete employee masrofat'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_employee_revenues',
                'title' => ['ar' => 'عرض ايرادات الموظف', 'en' => 'view employee revenues'],
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
            [
                'name' => 'view_client_unpaid_invoices',
                'title' => ['ar' => 'عرض فواتير العميل الغير مدفوعة', 'en' => 'view client unpaid invoices'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_client_paid_invoices',
                'title' => ['ar' => 'عرض فواتير العميل المدفوعة', 'en' => 'view client paid invoices'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_client_invoices',
                'title' => ['ar' => 'عرض فواتير العميل', 'en' => 'view client invoices'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'add_client_invoice',
                'title' => ['ar' => 'اضافة فاتورة للعميل', 'en' => 'add client invoice'],
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

            // Invoices permissions
            [
                'name' => 'list_invoices',
                'title' => ['ar' => 'عرض الفواتير', 'en' => 'list invoices'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'delete_invoice',
                'title' => ['ar' => 'حذف فاتورة', 'en' => 'delete invoice'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'pay_invoice',
                'title' => ['ar' => 'دفع فاتورة', 'en' => 'pay invoice'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_invoice_details',
                'title' => ['ar' => 'عرض تفاصيل الفاتورة', 'en' => 'view invoice details'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'print_invoice',
                'title' => ['ar' => 'طباعة فاتورة', 'en' => 'print invoice'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'redo_invoice',
                'title' => ['ar' => 'إعادة فاتورة', 'en' => 'redo invoice'],
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
            [
                'name' => 'update_user_permissions',
                'title' => ['ar' => 'تحديث صلاحيات المستخدم', 'en' => 'update user permissions'],
                'guard_name' => 'admin',
            ],

            // Notification permissions
            [
                'name' => 'view_new_clients_notifications',
                'title' => ['ar' => 'عرض اشعارات العملاء الجدد', 'en' => 'view new clients notifications'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'view_unpaid_invoices_notifications',
                'title' => ['ar' => 'عرض اشعارات الفواتير الغير مدفوعة', 'en' => 'view unpaid invoices notifications'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'mark_notification_read',
                'title' => ['ar' => 'تأشير الإشعار كمقروء', 'en' => 'mark notification as read'],
                'guard_name' => 'admin',
            ],

            // reports permissions
            [
                'name' => 'view_reports',
                'title' => ['ar' => 'عرض التقارير', 'en' => 'view reports'],
                'guard_name' => 'admin',
            ],
            [
                'name' => 'generate_reports',
                'title' => ['ar' => 'انشاء التقارير', 'en' => 'generate reports'],
                'guard_name' => 'admin',
            ],

        ];

        foreach ($permissions as $permissionData) {
            // Permission::create([
            //     'name' => $permissionData['name'],
            //     'title' => $permissionData['title'],
            //     'guard_name' => $permissionData['guard_name'],
            // ]);
            Permission::updateOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
                ['title' => $permissionData['title']]
            );
        }
    }
}
