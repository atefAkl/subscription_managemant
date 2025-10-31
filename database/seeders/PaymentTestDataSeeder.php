<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\SubscriptionRequest;
use App\Models\User;

class PaymentTestDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // إنشاء مستخدمين تجريبيين إذا لم يكونوا موجودين
    $users = [];
    for ($i = 1; $i <= 3; $i++) {
      $users[] = User::firstOrCreate(
        ['email' => "client{$i}@test.com"],
        [
          'name' => "عميل تجريبي {$i}",
          'password' => bcrypt('password'),
          'role' => 'client'
        ]
      );
    }

    // إنشاء طلبات اشتراك تجريبية
    $subscriptionRequests = [];
    foreach ($users as $index => $user) {
      $subscriptionRequests[] = SubscriptionRequest::firstOrCreate(
        ['user_id' => $user->id],
        [
          'subscription_name' => ['Basic Plan', 'Premium Plan', 'Pro Plan'][$index],
          'device_count' => [2, 5, 10][$index],
          'proposed_start_date' => now()->addDays(rand(1, 10))->format('Y-m-d'),
          'notes' => "طلب اشتراك تجريبي رقم " . ($index + 1),
          'status' => 'quoted',
          'quoted_price' => [500.00, 800.00, 1200.00][$index],
          'quoted_at' => now()->subDays(rand(1, 3)),
          'payment_method' => ['تحويل بنكي', 'فيزا/ماستركارد', 'فودافون كاش'][$index]
        ]
      );
    }

    // إنشاء مدفوعات معلقة
    $paymentMethods = ['bank_transfer', 'visa_card', 'vodafone_cash', 'orange_money', 'etisalat_cash'];
    $amounts = [250.00, 500.00, 750.00, 1000.00, 1500.00];

    for ($i = 0; $i < 8; $i++) {
      $user = $users[array_rand($users)];
      $subscriptionRequest = $subscriptionRequests[array_rand($subscriptionRequests)];

      Payment::create([
        'user_id' => $user->id,
        'subscription_request_id' => $subscriptionRequest->id,
        'amount' => $amounts[array_rand($amounts)],
        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
        'transaction_reference' => 'TXN' . rand(100000, 999999),
        'status' => 'pending_verification',
        'created_at' => now()->subHours(rand(1, 72))
      ]);
    }

    // إنشاء بعض المدفوعات المؤكدة
    for ($i = 0; $i < 5; $i++) {
      $user = $users[array_rand($users)];
      $subscriptionRequest = $subscriptionRequests[array_rand($subscriptionRequests)];

      Payment::create([
        'user_id' => $user->id,
        'subscription_request_id' => $subscriptionRequest->id,
        'amount' => $amounts[array_rand($amounts)],
        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
        'transaction_reference' => 'TXN' . rand(100000, 999999),
        'status' => 'verified',
        'verified_at' => now()->subDays(rand(1, 10)),
        'verified_by' => 1, // assuming admin user ID is 1
        'created_at' => now()->subDays(rand(1, 15))
      ]);
    }

    // إنشاء مدفوعة واحدة مرفوضة
    $user = $users[array_rand($users)];
    $subscriptionRequest = $subscriptionRequests[array_rand($subscriptionRequests)];

    Payment::create([
      'user_id' => $user->id,
      'subscription_request_id' => $subscriptionRequest->id,
      'amount' => 300.00,
      'payment_method' => 'bank_transfer',
      'transaction_reference' => 'TXN' . rand(100000, 999999),
      'status' => 'rejected',
      'verified_by' => 1,
      'admin_notes' => 'معلومات التحويل غير صحيحة',
      'created_at' => now()->subDays(5)
    ]);

    echo "✅ تم إنشاء البيانات التجريبية للمدفوعات بنجاح!\n";
    echo "   - 8 مدفوعات معلقة\n";
    echo "   - 5 مدفوعات مؤكدة\n";
    echo "   - 1 مدفوعة مرفوضة\n";
  }
}
