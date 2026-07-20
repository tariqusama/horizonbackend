<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@horizonpathways.us',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1 (555) 000-0000',
                'country' => 'United States',
                'status' => 'Active',
                'initials' => 'AU',
                'color' => 'from-[#1B3A64] to-[#3B66A5]',
            ],
            [
                'name' => 'Sarah Jones',
                'email' => 'sarah.j@horizonpathways.us',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '+1 (555) 111-2222',
                'country' => 'United States',
                'status' => 'Active',
                'initials' => 'SJ',
                'color' => 'from-[#2F8A5F] to-[#3EB87F]',
            ],
            [
                'name' => 'David Miller',
                'email' => 'david.m@horizonpathways.us',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '+1 (555) 333-4444',
                'country' => 'Canada',
                'status' => 'Active',
                'initials' => 'DM',
                'color' => 'from-[#B98A0A] to-[#D9A420]',
            ],
            [
                'name' => 'Elena Rustova',
                'email' => 'elena.r@horizonpathways.us',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '+1 (555) 555-6666',
                'country' => 'United States',
                'status' => 'Active',
                'initials' => 'ER',
                'color' => 'from-[#D6497A] to-[#F06899]',
            ],
            [
                'name' => 'Michael Chang',
                'email' => 'michael.c@horizonpathways.us',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '+1 (555) 777-8888',
                'country' => 'China',
                'status' => 'Active',
                'initials' => 'MC',
                'color' => 'from-[#1B3A64] to-[#3B66A5]',
            ]
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
