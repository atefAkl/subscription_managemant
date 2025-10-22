# نظام تسجيل الدخول للمستخدمين - تعليمات التشغيل

## نظرة عامة
تم إنشاء نظام تسجيل دخول متكامل يدعم نوعين من المستخدمين:
- **المديرون (Admins)**: لديهم إمكانية الوصول إلى لوحة الإدارة
- **العملاء (Clients)**: لديهم إمكانية الوصول إلى لوحة العميل

## الملفات المنشأة

### 1. قاعدة البيانات
- **Migration**: `database/migrations/2025_10_21_202733_add_role_to_users_table.php`
  - إضافة عمود `role` إلى جدول المستخدمين
- **User Model**: تم تحديث `app/Models/User.php`
  - إضافة حقل `role` إلى fillable
  - إضافة دوال `isAdmin()` و `isClient()` و `getDashboardRoute()`
- **Factory**: تم تحديث `database/factories/UserFactory.php`
  - إضافة حقل `role` مع دوال `admin()` و `client()`

### 2. Controllers
- **HomeController**: `app/Http/Controllers/HomeController.php`
- **AuthController**: `app/Http/Controllers/AuthController.php`
- **DashboardController**: `app/Http/Controllers/DashboardController.php`

### 3. Views
- **الصفحة الرئيسية**: `resources/views/home.blade.php`
- **تسجيل الدخول**: `resources/views/auth/login.blade.php`
- **التسجيل**: `resources/views/auth/register.blade.php`
- **لوحة الإدارة**: `resources/views/dashboard/admin.blade.php`
- **لوحة العميل**: `resources/views/dashboard/client.blade.php`

### 4. Middleware
- **CheckRole**: `app/Http/Middleware/CheckRole.php`
  - للتحكم في صلاحيات الوصول

### 5. Routes
- **web.php**: تم تحديث المسارات لتشمل جميع الصفحات

## خطوات التشغيل

### 1. إعداد قاعدة البيانات
```bash
# تشغيل المايجريشن
php artisan migrate

# (اختياري) إنشاء بيانات تجريبية
php artisan tinker
```

### 2. إنشاء مستخدمين للاختبار
```php
// في artisan tinker
use App\Models\User;

// إنشاء مدير
User::factory()->admin()->create([
    'name' => 'مدير النظام',
    'email' => 'admin@test.com',
    'password' => bcrypt('123456')
]);

// إنشاء عميل
User::factory()->client()->create([
    'name' => 'عميل تجريبي',
    'email' => 'client@test.com',
    'password' => bcrypt('123456')
]);
```

### 3. تشغيل الخادم
```bash
php artisan serve
```

## المسارات المتاحة

| المسار | الوصف | المتطلبات |
|--------|--------|----------|
| `/` | الصفحة الرئيسية | - |
| `/login` | تسجيل الدخول | ضيف |
| `/register` | التسجيل العام (عملاء فقط) | ضيف |
| `/admin/dashboard` | لوحة الإدارة | مدير مسجل |
| `/client/dashboard` | لوحة العميل | عميل مسجل |
| `/admin/register` | إضافة مستخدم جديد | مدير مسجل فقط |
| `/logout` | تسجيل الخروج | مستخدم مسجل |

## نظام التسجيل المحدث

### التسجيل العام (`/register`)
- متاح لجميع الزوار
- يسجل المستخدمين كـ **عملاء** بشكل افتراضي
- لا يحتوي على خيار اختيار نوع المستخدم
- يوجه المستخدم مباشرة إلى لوحة العميل

### التسجيل الإداري (`/admin/register`)
- متاح للمديرين المسجلين فقط
- يمكن إضافة مديرين أو عملاء جدد
- محمي بـ middleware للتأكد من صلاحيات المدير
- يحتوي على إحصائيات سريعة للمستخدمين

## بيانات الاختبار
- **المدير**: admin@test.com / 123456
- **العميل**: client@test.com / 123456

## الميزات المتاحة

### للمديرين
- عرض إحصائيات النظام
- قائمة بآخر المستخدمين المسجلين
- روابط سريعة لإدارة النظام

### للعملاء
- عرض حالة الاشتراك
- إحصائيات الاستخدام
- النشاط الأخير
- إجراءات سريعة

## الحماية والأمان
- **Authentication**: تسجيل الدخول مطلوب للوصول للوحات
- **Authorization**: كل مستخدم يمكنه الوصول فقط للوحة الخاصة به
- **Middleware**: `CheckRole` للتحكم في الصلاحيات
- **Form Validation**: التحقق من البيانات المدخلة
- **Password Hashing**: تشفير كلمات المرور

## ملاحظات مهمة
1. تأكد من إعداد قاعدة البيانات في ملف `.env`
2. جميع الصفحات تدعم اللغة العربية والاتجاه من اليمين لليسار
3. التصميم responsive ويعمل على جميع الأجهزة
4. تم استخدام Tailwind CSS للتصميم

## التطوير المستقبلي
- إضافة نظام الصلاحيات المتقدم
- إضافة إدارة الملفات الشخصية
- تنفيذ نظام الإشعارات
- إضافة API endpoints
- تحسين نظام التقارير