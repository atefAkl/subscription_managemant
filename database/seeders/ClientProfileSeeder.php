<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClientProfile;

class ClientProfileSeeder extends Seeder
{
  public function run()
  {
    // Find all clients without profiles
    $clients = User::where('role', 'client')
      ->doesntHave('clientProfile')
      ->get();

    foreach ($clients as $client) {
      ClientProfile::create([
        'user_id' => $client->id,
        'subscription_type' => 'basic',
        'subscription_status' => 'active',
        'subscription_start_date' => now(),
        'subscription_end_date' => now()->addMonths(3),
        'device_limit' => 5,
        'devices_count' => rand(1, 3),
        'payment_status' => 'paid',
        'billing_cycle' => 'monthly',
        'client_notes' => 'عميل تجريبي - تم إنشاؤه تلقائياً'
      ]);
    }

    echo "Created profiles for " . $clients->count() . " clients\n";
  }
}
