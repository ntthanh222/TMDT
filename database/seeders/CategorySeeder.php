<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Cà phê hạt',
            'Cà phê bột',
            'Trà pha chế',
            'Syrup & Sốt',
            'Bột topping',
            'Dụng cụ pha chế',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Nguyên liệu $name chất lượng cao dành cho pha chế.",
                    'is_active' => true,
                ]
            );
        }
    }
}
