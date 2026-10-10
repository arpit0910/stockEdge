<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SalesUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('SALES_EMAIL', 'sales@sharesrise.com.au')],
            [
                'name' => env('SALES_NAME', 'SharesRise Sales'),
                'password' => Hash::make(env('SALES_PASSWORD', 'SharesRise@Sales2026!')),
                'email_verified_at' => now(),
                'is_sales' => true,
                'is_active' => true,
            ],
        );
    }
}
