<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Main Administrator',
                'email' => env('ADMIN_ONE_EMAIL'),
                'password' => env('ADMIN_ONE_PASSWORD'),
            ],
            [
                'name' => 'Boutique Manager',
                'email' => env('ADMIN_TWO_EMAIL'),
                'password' => env('ADMIN_TWO_PASSWORD'),
            ],
        ];

        foreach ($admins as $admin) {
            if (empty($admin['email']) || empty($admin['password'])) {
                throw new RuntimeException(
                    'Set both admin emails and passwords in your .env file.'
                );
            }

            User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
