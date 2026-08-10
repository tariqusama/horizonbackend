<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@horizon.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $this->call([
            ServicesTableSeeder::class,
            SignupPathwaysSeeder::class,
            ChecklistSeeder::class,
            // DynamicFormsSeeder::class, // Obsolete, causes duplicates
            ServiceSeeder::class,
            FormG1145Seeder::class,
            FormI129FSeeder::class,
            FormI130Seeder::class,
            FormI130ASeeder::class,
            FormI485Seeder::class,
            FormI751Seeder::class,
            FormI765Seeder::class,
            FormI765WSSeeder::class,
            FormI821DSeeder::class,
            FormI864Seeder::class,
            FormI90Seeder::class,
            FormN400Seeder::class,
            ServiceFormLinkSeeder::class,
        ]);
    }
}
