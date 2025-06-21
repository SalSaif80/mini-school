# هيكل قاعدة البيانات الجديد - نظام إدارة المدرسة

## نظرة عامة

تم إعادة هيكلة قاعدة البيانات لتتبع أفضل الممارسات في فصل الأدوار والجداول. النظام الجديد يفصل بين المستخدمين الأساسيين وملفاتهم الشخصية المفصلة.

## هيكل الجداول

### 1. جدول المستخدمين (users)
```sql
- id (Primary Key)
- name (اسم المستخدم)
- email (البريد الإلكتروني - فريد)
- password (كلمة المرور)
- role (الدور: admin, teacher, student)
- email_verified_at (تاريخ تأكيد البريد)
- remember_token
- timestamps
- soft_deletes
```

### 2. جدول الطلاب (students)
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- student_id (رقم الطالب - فريد)
- date_of_birth (تاريخ الميلاد)
- gender (الجنس: male, female, other)
- address (العنوان)
- phone (رقم الهاتف)
- enrollment_date (تاريخ التسجيل)
- major (التخصص)
- class_level (المستوى الدراسي)
- timestamps
```

### 3. جدول المدرسين (teachers)
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- teacher_id (رقم المدرس - فريد)
- date_of_birth (تاريخ الميلاد)
- gender (الجنس: male, female, other)
- address (العنوان)
- phone (رقم الهاتف)
- hire_date (تاريخ التوظيف)
- department (القسم)
- specialization (التخصص)
- salary (الراتب - اختياري)
- timestamps
```

### 4. جدول المواد (courses)
```sql
- id (Primary Key)
- course_code (رمز المادة - فريد)
- title (عنوان المادة)
- description (وصف المادة)
- credit_hours (عدد الساعات المعتمدة)
- teacher_id (Foreign Key → teachers.id)
- level (المستوى: beginner, intermediate, advanced)
- timestamps
```

### 5. جدول التسجيلات (enrollments)
```sql
- id (Primary Key)
- student_id (Foreign Key → students.id)
- course_id (Foreign Key → courses.id)
- enrollment_date (تاريخ التسجيل)
- status (الحالة: active, completed, dropped)
- grade (الدرجة - اختياري)
- timestamps
- unique(student_id, course_id) - منع التسجيل المكرر
```

### 6. جدول الحضور (attendances)
```sql
- id (Primary Key)
- student_id (Foreign Key → students.id)
- course_id (Foreign Key → courses.id)
- date (تاريخ الحضور)
- status (حالة الحضور: present, absent, late, excused)
- notes (ملاحظات - اختياري)
- timestamps
```

## العلاقات بين الجداول

### User Model
- `hasOne(Student::class)` - علاقة واحد لواحد مع الطالب
- `hasOne(Teacher::class)` - علاقة واحد لواحد مع المدرس

### Student Model
- `belongsTo(User::class)` - ينتمي لمستخدم واحد
- `hasMany(Enrollment::class)` - له تسجيلات متعددة
- `hasManyThrough(Course::class, Enrollment::class)` - له مواد من خلال التسجيلات
- `hasMany(Attendance::class)` - له سجلات حضور متعددة

### Teacher Model
- `belongsTo(User::class)` - ينتمي لمستخدم واحد
- `hasMany(Course::class)` - يدرس مواد متعددة
- علاقة معقدة للوصول للطلاب من خلال المواد والتسجيلات

### Course Model
- `belongsTo(Teacher::class)` - ينتمي لمدرس واحد
- `hasMany(Enrollment::class)` - له تسجيلات متعددة
- `hasManyThrough(Student::class, Enrollment::class)` - له طلاب من خلال التسجيلات
- `hasMany(Attendance::class)` - له سجلات حضور متعددة

### Enrollment Model
- `belongsTo(Student::class)` - ينتمي لطالب واحد
- `belongsTo(Course::class)` - ينتمي لمادة واحدة

### Attendance Model
- `belongsTo(Student::class)` - ينتمي لطالب واحد
- `belongsTo(Course::class)` - ينتمي لمادة واحدة

## البيانات التجريبية

### المستخدمون
- **مدير واحد**: admin@school.com / password123
- **4 مدرسين** مع ملفات شخصية كاملة
- **8 طلاب** مع ملفات شخصية كاملة

### المواد
- **10 مواد** متنوعة تغطي مختلف التخصصات
- موزعة على المدرسين بشكل عشوائي
- مستويات مختلفة (مبتدئ، متوسط، متقدم)

### التسجيلات
- **24 تسجيل** عشوائي
- حالات متنوعة: نشط، مكتمل، منسحب
- درجات للتسجيلات المكتملة

## مميزات الهيكل الجديد

1. **فصل الأدوار**: كل دور له جدول منفصل مع بياناته المحددة
2. **مرونة في البيانات**: يمكن إضافة حقول خاصة بكل دور دون تأثير على الآخرين
3. **علاقات واضحة**: علاقات محددة وواضحة بين الجداول
4. **أمان أفضل**: تحكم أفضل في الوصول للبيانات
5. **قابلية التوسع**: سهولة إضافة جداول وعلاقات جديدة

## أوامر التشغيل

```bash
# تشغيل الـ migrations
php artisan migrate:fresh

# تشغيل الـ seeders
php artisan db:seed

# أو تشغيل كل شيء معاً
php artisan migrate:fresh --seed
```

## ملاحظات مهمة

1. تم تغيير `user_type` إلى `role` في جدول المستخدمين
2. تم إزالة الحقول الشخصية من جدول المستخدمين ونقلها للجداول المخصصة
3. تم تبسيط جدول المواد وإزالة الحقول غير الضرورية
4. تم تحسين جدول التسجيلات لمنع التكرار
5. تمت إضافة جدول الحضور للمتابعة اليومية

هذا الهيكل الجديد يوفر أساساً قوياً ومرناً لنظام إدارة المدرسة مع إمكانيات التوسع المستقبلي. 
