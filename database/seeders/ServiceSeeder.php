<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Premium Case Support', 'description' => '24/7 dedicated support.', 'price' => 1999.00, 'tier' => 'Premium'],
            ['name' => 'Standard Review', 'description' => 'Standard application review.', 'price' => 499.00, 'tier' => 'Standard'],
            ['name' => 'Express Processing', 'description' => 'Expedited handling.', 'price' => 999.00, 'tier' => 'Premium'],
            ['name' => 'Basic Consultation', 'description' => '30 min consultation.', 'price' => 99.00, 'tier' => 'Standard'],
        ];

        foreach ($services as $s) {
            Service::create($s);
        }

        $allServices = Service::all();
        $user = User::where('role', 'Client')->first() ?? User::first();
        $now = Carbon::now();

        // Seed some purchases for the current month
        for ($i = 0; $i < 15; $i++) {
            $service = $allServices->random();
            Purchase::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'amount' => $service->price,
                'status' => 'Completed',
                'created_at' => $now->copy()->subDays(rand(1, 28)),
                'updated_at' => $now->copy()->subDays(rand(1, 28)),
            ]);
        }

        // Seed some purchases for last month (to show growth)
        $lastMonth = $now->copy()->subMonth();
        for ($i = 0; $i < 10; $i++) {
            $service = $allServices->random();
            Purchase::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'amount' => $service->price,
                'status' => 'Completed',
                'created_at' => $lastMonth->copy()->subDays(rand(1, 28)),
                'updated_at' => $lastMonth->copy()->subDays(rand(1, 28)),
            ]);
        }
    }
}
