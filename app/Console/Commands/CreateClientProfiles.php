<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateClientProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:create-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create client profiles for users without them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = \App\Models\User::where('role', 'client')
            ->doesntHave('clientProfile')
            ->get();

        $count = 0;
        foreach ($clients as $client) {
            \App\Models\ClientProfile::create([
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
            $count++;
        }

        $this->info("Created profiles for {$count} clients");
        return 0;
    }
}
