<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // User::factory(10)->create();
        $now = Carbon::now();
        $users = [
            // -----------------------------------------------------------------
            // 1. المسؤول الرئيسي (Super Admin)
            [
                'name' => 'Admin Ali',
                'user_name' => 'admin_ali',
                'email' => 'admin.ali@example.com',
                'password' => Hash::make('password'), // يمكنك تغيير كلمة المرور
                'serial_number' => 'ADM001',
                'is_app_admin' => true,
                'role' => 'admin',
                'status' => 'active',
                'phone' => '0501234567',
                'address' => 'Riyadh, Saudi Arabia',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 2. مسؤول عادي (Admin)
            [
                'name' => 'Admin Laila',
                'user_name' => 'admin_laila',
                'email' => 'admin.laila@example.com',
                'password' => Hash::make('password'),
                'serial_number' => 'ADM002',
                'is_app_admin' => false,
                'role' => 'admin',
                'status' => 'active',
                'phone' => '0559876543',
                'address' => 'Jeddah, Saudi Arabia',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // -----------------------------------------------------------------
            // 3. عميل فعال (Active Client)
            [
                'name' => 'Client Omar',
                'user_name' => 'client_omar',
                'email' => 'client.omar@example.com',
                'password' => Hash::make('123456'), // كلمة مرور بسيطة للعملاء
                'serial_number' => 'CLT001',
                'is_app_admin' => false,
                'role' => 'client',
                'status' => 'active',
                'phone' => '0561122334',
                'address' => 'Dubai, UAE',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 4. عميل معلق (Pending Client) - بانتظار التفعيل
            [
                'name' => 'Client Fatima',
                'user_name' => 'client_fatima',
                'email' => 'client.fatima@example.com',
                'password' => Hash::make('123456'),
                'serial_number' => 'CLT002',
                'is_app_admin' => false,
                'role' => 'client',
                'status' => 'pending', // حالة معلقة
                'phone' => '0535566778',
                'address' => 'Cairo, Egypt',
                'email_verified_at' => null, // لم يتم تفعيل البريد بعد
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 5. عميل محظور (Blocked Client)
            [
                'name' => 'Client Khalid',
                'user_name' => 'client_khalid',
                'email' => 'client.khalid@example.com',
                'password' => Hash::make('123456'),
                'serial_number' => 'CLT003',
                'is_app_admin' => false,
                'role' => 'client',
                'status' => 'blocked', // حالة محظور
                'phone' => '0590001112',
                'address' => 'Amman, Jordan',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        return $users;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Create a client user.
     */
    public function client(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'client',
        ]);
    }
}
