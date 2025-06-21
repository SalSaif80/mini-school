<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // صلاحيات الأدوار
            'استعراض الأدوار',
            'إنشاء أدوار',
            'تعديل الأدوار',
            'حذف الأدوار',

            // صلاحيات المستخدمين
            'استعراض المستخدمين',
            'إنشاء مستخدمين',
            'تعديل المستخدمين',
            'حذف المستخدمين',

            // صلاحيات المواد الدراسية
            'استعراض المواد الدراسية',
            'إنشاء مواد دراسية',
            'تعديل المواد الدراسية',
            'حذف المواد الدراسية',
            'عرض تفاصيل المادة',
            'عرض قائمة الطلاب المسجلين',
            'إدارة تسجيل الطلاب',

            // صلاحيات الطلاب
            'استعراض الطلاب',
            'إنشاء طلاب',
            'تعديل الطلاب',
            'حذف الطلاب',
            'عرض درجات الطلاب',

            // صلاحيات المعلمين
            'استعراض المعلمين',
            'إنشاء معلمين',
            'تعديل المعلمين',
            'حذف المعلمين',
            'تعيين مواد للمعلمين',

            // صلاحيات التسجيل
            'استعراض التسجيلات',
            'إنشاء تسجيلات',
            'تعديل التسجيلات',
            'حذف التسجيلات',
            'تغيير حالة التسجيل',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


    }
}
