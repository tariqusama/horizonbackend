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
            ['title' => 'Premium Case Support', 'subtitle' => '24/7 dedicated support.', 'starting_price' => '1999.00', 'processing_time' => 'Varies', 'requirements' => json_encode([]), 'is_popular' => true],
            ['title' => 'Standard Review', 'subtitle' => 'Standard application review.', 'starting_price' => '499.00', 'processing_time' => '2-3 weeks', 'requirements' => json_encode([]), 'is_popular' => false],
            ['title' => 'Express Processing', 'subtitle' => 'Expedited handling.', 'starting_price' => '999.00', 'processing_time' => '3-5 business days', 'requirements' => json_encode([]), 'is_popular' => false],
            ['title' => 'Basic Consultation', 'subtitle' => '30 min consultation.', 'starting_price' => '99.00', 'processing_time' => 'Instant booking', 'requirements' => json_encode([]), 'is_popular' => false],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(
                ['title' => $s['title']],
                $s
            );
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
                'amount' => (float) ($service->starting_price ?? 0),
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
                'amount' => (float) ($service->starting_price ?? 0),
                'status' => 'Completed',
                'created_at' => $lastMonth->copy()->subDays(rand(1, 28)),
                'updated_at' => $lastMonth->copy()->subDays(rand(1, 28)),
            ]);
        }
    }
}
