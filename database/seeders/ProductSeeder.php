<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category' => 'Cà phê hạt', 'name' => 'Cà phê hạt Arabica Đà Lạt', 'price' => 180000, 'sale_price' => 159000],
            ['category' => 'Cà phê hạt', 'name' => 'Cà phê hạt Robusta rang mộc', 'price' => 150000, 'sale_price' => null],
            ['category' => 'Cà phê bột', 'name' => 'Cà phê bột nguyên chất 100%', 'price' => 120000, 'sale_price' => 99000],
            ['category' => 'Cà phê bột', 'name' => 'Cà phê bột phin truyền thống', 'price' => 95000, 'sale_price' => null],
            ['category' => 'Trà pha chế', 'name' => 'Trà xanh Matcha Nhật Bản', 'price' => 210000, 'sale_price' => 189000],
            ['category' => 'Trà pha chế', 'name' => 'Trà đen Ceylon cao cấp', 'price' => 130000, 'sale_price' => null],
            ['category' => 'Syrup & Sốt', 'name' => 'Syrup Caramel Monin', 'price' => 165000, 'sale_price' => 149000],
            ['category' => 'Syrup & Sốt', 'name' => 'Syrup Vani Torani', 'price' => 165000, 'sale_price' => null],
            ['category' => 'Bột topping', 'name' => 'Bột Cacao nguyên chất', 'price' => 110000, 'sale_price' => 89000],
            ['category' => 'Bột topping', 'name' => 'Bột trà sữa truyền thống', 'price' => 85000, 'sale_price' => null],
            ['category' => 'Dụng cụ pha chế', 'name' => 'Phin cà phê inox 304', 'price' => 65000, 'sale_price' => null],
            ['category' => 'Dụng cụ pha chế', 'name' => 'Bình lắc Shaker pha chế', 'price' => 120000, 'sale_price' => 99000],
        ];

        foreach ($products as $index => $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => "Sản phẩm {$data['name']} chất lượng cao, nhập khẩu và tuyển chọn kỹ càng.",
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'stock_quantity' => 100,
                    'sku' => 'SP' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'is_featured' => $index % 3 === 0,
                    'is_active' => true,
                ]
            );
        }
    }
}
