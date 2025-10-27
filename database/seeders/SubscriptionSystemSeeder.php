<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مدير تجريبي إضافي
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'مدير النظام',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        // إنشاء عميل تجريبي
        $client = \App\Models\User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'عميل تجريبي',
                'password' => bcrypt('password'),
                'role' => 'client'
            ]
        );

        // إنشاء طلب اشتراك تجريبي
        $subscriptionRequest = \App\Models\SubscriptionRequest::create([
            'user_id' => $client->id,
            'subscription_name' => 'اشتراك المكتب الرئيسي',
            'device_count' => 5,
            'proposed_start_date' => now()->addDays(7),
            'notes' => 'نحتاج إلى خدمة موثوقة لمكتبنا الرئيسي مع دعم فني سريع',
            'status' => 'pending'
        ]);

        // إنشاء طلب اشتراك آخر مع عرض سعر
        $subscriptionRequest2 = \App\Models\SubscriptionRequest::create([
            'user_id' => $client->id,
            'subscription_name' => 'فرع المبيعات',
            'device_count' => 3,
            'proposed_start_date' => now(),
            'notes' => 'للفرع الجديد - نحتاج تفعيل سريع',
            'status' => 'quoted',
            'quoted_price' => 450.00,
            'payment_method' => 'تحويل بنكي أو فودافون كاش',
            'admin_notes' => 'عرض خاص للعميل الجديد - خصم 10%',
            'quoted_at' => now()->subHours(2)
        ]);

        // إنشاء اشتراك نشط مع أجهزة
        $activeSubscription = \App\Models\Subscription::create([
            'user_id' => $client->id,
            'subscription_request_id' => $subscriptionRequest2->id,
            'name' => 'الاشتراك التجريبي النشط',
            'device_count' => 3,
            'price' => 300.00,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(20),
            'status' => 'active',
            'description' => 'اشتراك تجريبي مع 3 أجهزة',
            'features' => ['دعم فني 24/7', 'سرعة عالية', 'نسخ احتياطي']
        ]);

        // إنشاء أجهزة iPhone مختلفة الحالات
        \App\Models\Device::create([
            'subscription_id' => $activeSubscription->id,
            'device_identifier' => 'IPH' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . 'AB',
            'iphone_model' => 'iPhone 15 Pro',
            'device_nickname' => 'آيفون المدير',
            'device_number' => 'IPH-001',
            'device_version' => 'iOS 17.1',
            'device_name' => 'آيفون المدير',
            'machine_name' => 'iPhone-Manager',
            'token' => 'iph_token_' . substr(md5(time()), 0, 16),
            'status' => 'active',
            'activated_at' => now()->subDays(8),
            'last_connected_at' => now()->subMinutes(5),
            'ip_address' => '192.168.1.100',
            'serial_number' => 'G1234567890A',
            'device_info' => json_encode([
                'model' => 'iPhone15,2',
                'storage' => '256GB',
                'color' => 'Natural Titanium',
                'carrier' => 'Orange Egypt'
            ]),
            'last_token_update' => now()->subDays(8)
        ]);

        \App\Models\Device::create([
            'subscription_id' => $activeSubscription->id,
            'device_identifier' => 'IPH' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . 'CD',
            'iphone_model' => 'iPhone 14',
            'device_nickname' => 'آيفون المبيعات',
            'device_number' => 'IPH-002',
            'device_version' => 'iOS 17.0',
            'status' => 'pending'
        ]);

        \App\Models\Device::create([
            'subscription_id' => $activeSubscription->id,
            'device_identifier' => 'IPH' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . 'EF',
            'iphone_model' => 'iPhone 13 Pro Max',
            'device_nickname' => 'آيفون الاستقبال',
            'device_number' => 'IPH-003',
            'device_version' => 'iOS 16.7',
            'device_name' => 'آيفون الاستقبال',
            'machine_name' => 'iPhone-Reception',
            'token' => 'iph_token_' . substr(md5(time() + 100), 0, 16),
            'status' => 'active',
            'activated_at' => now()->subDays(5),
            'last_connected_at' => now()->subHours(2),
            'ip_address' => '192.168.1.101',
            'serial_number' => 'H9876543210B',
            'device_info' => json_encode([
                'model' => 'iPhone14,3',
                'storage' => '512GB',
                'color' => 'Sierra Blue',
                'carrier' => 'Vodafone Egypt'
            ]),
            'last_token_update' => now()->subDays(5)
        ]);

        echo "تم إنشاء البيانات التجريبية بنجاح!\n";
        echo "المدير: manager@test.com / password\n";
        echo "العميل: client@test.com / password\n";
    }
}
