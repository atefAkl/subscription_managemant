<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClientProfile;
use App\Models\ClientDevice;

class SampleClientsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Client 1
        $client1 = User::firstOrCreate(
            [ 'email' => 'ahmed@test.com' ],
            [
                'name' => 'أحمد محمد علي',
                'password' => bcrypt('password'),
                'role' => 'client',
                'phone' => '+201234567890'
            ]
        );

        $profile1 = ClientProfile::firstOrCreate([
            'user_id' => $client1->id,
            'subscription_type' => 'premium',
            'subscription_status' => 'active',
            'device_limit' => 3,
            'devices_count' => 2,
            'subscription_start_date' => now()->subDays(30),
            'subscription_end_date' => now()->addDays(335),
            'payment_status' => 'paid',
            'billing_cycle' => 'yearly'
        ]);

        // Create devices for Client 1
        ClientDevice::updateOrCreate(
            ['device_serial' => 'F2L12345ABC'],
            [
                'user_id' => $client1->id,
                'subscription_id' => $profile1->id,
                'device_name' => 'iPhone 15 Pro - أحمد',
                'device_serial' => 'F2L12345ABC',
            'device_type' => 'iphone',
            'device_model' => 'iPhone 15 Pro',
            'ios_version' => '17.1',
            'status' => 'active',
            'activation_date' => now()->subDays(25),
            'last_connection' => now()->subMinutes(15),
        ]);

        ClientDevice::updateOrCreate(
            ['device_serial' => 'DMY67890XYZ'],
            [
                'user_id' => $client1->id,
                'subscription_id' => $profile1->id,
                'device_name' => 'iPad Pro - أحمد',
                'device_serial' => 'DMY67890XYZ',
            'device_type' => 'ipad',
            'device_model' => 'iPad Pro 12.9-inch',
            'ios_version' => '17.1',
            'status' => 'active',
            'activation_date' => now()->subDays(20),
            'last_connection' => now()->subHours(2),
        ]);

        // Create Client 2
        $client2 = User::firstOrCreate(
            [ 'email' => 'fatma@test.com' ],
            [
                'name' => 'فاطمة سالم',
                'password' => bcrypt('password'),
                'role' => 'client',
                'phone' => '+201098765432'
            ]
        );

        $profile2 = ClientProfile::firstOrCreate([
            'user_id' => $client2->id,
            'subscription_type' => 'basic',
            'subscription_status' => 'active',
            'device_limit' => 2,
            'devices_count' => 1,
            'subscription_start_date' => now()->subDays(15),
            'subscription_end_date' => now()->addDays(350),
            'payment_status' => 'paid',
            'billing_cycle' => 'monthly'
        ]);

        ClientDevice::updateOrCreate(
            ['device_serial' => 'F1K98765DEF'],
            [
                'user_id' => $client2->id,
                'subscription_id' => $profile2->id,
                'device_name' => 'iPhone 14 - فاطمة',
                'device_serial' => 'F1K98765DEF',
                'device_type' => 'iphone',
                'device_model' => 'iPhone 14',
                'ios_version' => '17.0',
                'status' => 'active',
                'activation_date' => now()->subDays(12),
                'last_connection' => now()->subMinutes(5),
            ]
        );
        ]);

        // Create Admin User
        $admin = User::firstOrCreate(
            [ 'email' => 'admin@test.com' ],
            [
                'name' => 'مدير النظام',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );
    }
}
