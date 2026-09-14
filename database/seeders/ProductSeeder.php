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
            ['category' => 'Cà phê hạt', 'name' => 'Cà phê hạt Arabica Đà Lạt', 'price' => 180000, 'sale_price' => 159000, 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&auto=format&fit=crop'],
            ['category' => 'Cà phê hạt', 'name' => 'Cà phê hạt Robusta rang mộc', 'price' => 150000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop'],
            ['category' => 'Cà phê bột', 'name' => 'Cà phê bột nguyên chất 100%', 'price' => 120000, 'sale_price' => 99000, 'image' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0e9?w=600&auto=format&fit=crop'],
            ['category' => 'Cà phê bột', 'name' => 'Cà phê bột phin truyền thống', 'price' => 95000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=600&auto=format&fit=crop'],
            ['category' => 'Trà pha chế', 'name' => 'Trà xanh Matcha Nhật Bản', 'price' => 210000, 'sale_price' => 189000, 'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop'],
            ['category' => 'Trà pha chế', 'name' => 'Trà đen Ceylon cao cấp', 'price' => 130000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=600&auto=format&fit=crop'],
            ['category' => 'Syrup & Sốt', 'name' => 'Syrup Caramel Monin', 'price' => 165000, 'sale_price' => 149000, 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop'],
            ['category' => 'Syrup & Sốt', 'name' => 'Syrup Vani Torani', 'price' => 165000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&auto=format&fit=crop'],
            ['category' => 'Bột topping', 'name' => 'Bột Cacao nguyên chất', 'price' => 110000, 'sale_price' => 89000, 'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600&auto=format&fit=crop'],
            ['category' => 'Bột topping', 'name' => 'Bột trà sữa truyền thống', 'price' => 85000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=600&auto=format&fit=crop'],
            ['category' => 'Dụng cụ pha chế', 'name' => 'Phin cà phê inox 304', 'price' => 65000, 'sale_price' => null, 'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&auto=format&fit=crop'],
            ['category' => 'Dụng cụ pha chế', 'name' => 'Bình lắc Shaker pha chế', 'price' => 120000, 'sale_price' => 99000, 'image' => 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?w=600&auto=format&fit=crop'],
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
                    'sku' => 'SP'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'image' => $data['image'],
                    'is_featured' => $index % 3 === 0,
                    'is_active' => true,
                ]
            );
        }
    }
}
