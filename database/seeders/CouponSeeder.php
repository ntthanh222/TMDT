<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'GOODCAFE10'],
            [
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 100000,
                'max_discount' => 50000,
                'is_active' => true,
                'expires_at' => now()->addMonths(6),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'XINCHAO20K'],
            [
                'type' => 'fixed',
                'value' => 20000,
                'min_order_amount' => 50000,
                'is_active' => true,
                'expires_at' => now()->addMonths(6),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'EXPIRED50'],
            [
                'type' => 'percent',
                'value' => 50,
                'min_order_amount' => 10000,
                'is_active' => true,
                'expires_at' => now()->subDays(10),
            ]
        );
    }
}
