<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cafeshop.com'],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@cafeshop.com'],
            [
                'name' => 'Khách hàng',
                'password' => Hash::make('Password@123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
