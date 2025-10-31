<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSampleDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:create-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample devices for clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = \App\Models\User::where('role', 'client')->with('clientProfile')->get();

        $appleDevices = [
            [
                'name' => 'iPhone 15 Pro',
                'type' => 'mobile',
                'model' => 'iPhone 15 Pro',
                'ios' => '17.1',
                'serial' => 'F2L' . strtoupper(bin2hex(random_bytes(4)))
            ],
            [
                'name' => 'iPad Pro 12.9',
                'type' => 'tablet',
                'model' => 'iPad Pro 12.9-inch (6th generation)',
                'ios' => '17.1',
                'serial' => 'DMY' . strtoupper(bin2hex(random_bytes(4)))
            ],
            [
                'name' => 'MacBook Pro 16',
                'type' => 'laptop',
                'model' => 'MacBook Pro 16-inch',
                'ios' => '14.1',
                'serial' => 'C02' . strtoupper(bin2hex(random_bytes(4)))
            ],
            [
                'name' => 'Apple TV 4K',
                'type' => 'tv',
                'model' => 'Apple TV 4K (3rd generation)',
                'ios' => '17.1',
                'serial' => 'HD6' . strtoupper(bin2hex(random_bytes(4)))
            ]
        ];

        $count = 0;
        foreach ($clients as $client) {
            if (!$client->clientProfile) continue;

            // Create devices based on current device count in profile
            $devicesCount = min($client->clientProfile->devices_count, 3); // Max 3 devices per client

            for ($i = 0; $i < $devicesCount; $i++) {
                $device = $appleDevices[array_rand($appleDevices)];
                $deviceName = $device['name'] . ' - العميل ' . $client->name;

                \App\Models\ClientDevice::create([
                    'user_id' => $client->id,
                    'subscription_id' => $client->clientProfile->id,
                    'device_name' => $deviceName,
                    'device_serial' => $device['serial'] . sprintf('%04d', $count + 1),
                    'device_type' => $device['type'],
                    'device_model' => $device['model'],
                    'ios_version' => $device['ios'],
                    'status' => rand(0, 10) > 2 ? 'active' : 'inactive', // 80% active
                    'activation_date' => now()->subDays(rand(1, 30)),
                    'last_connection' => rand(0, 1) ? now()->subMinutes(rand(1, 1440)) : null,
                    'device_info' => [
                        'device_model' => $device['model'],
                        'ios_version' => $device['ios'],
                        'last_backup' => now()->subDays(rand(1, 7))->format('Y-m-d')
                    ]
                ]);
                $count++;
            }
        }

        $this->info("Created {$count} sample devices for clients");
        return 0;
    }
}
