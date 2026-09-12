<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Cà phê mới',
            'slug' => 'ca-phe-moi',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Cà phê Sapa',
                'category_id' => $category->id,
                'price' => 150000,
                'sale_price' => 120000,
                'stock_quantity' => 25,
                'sku' => 'CF-002',
                'description' => 'Cà phê thơm ngon',
                'is_active' => '1',
                'is_featured' => '1',
            ]);

        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Cà phê Sapa',
            'category_id' => $category->id,
            'price' => 150000,
            'sale_price' => 120000,
            'stock_quantity' => 25,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Cà phê mới',
            'slug' => 'ca-phe-moi',
            'is_active' => true,
        ]);

        $product = \App\Models\Product::factory()->create([
            'name' => 'Cà phê ban đầu',
            'category_id' => $category->id,
            'price' => 150000,
            'sale_price' => 120000,
            'stock_quantity' => 10,
            'sku' => 'CF-UPDATE-01',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk();

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), [
                'name' => 'Cà phê đã cập nhật',
                'category_id' => $category->id,
                'price' => 170000,
                'sale_price' => 150000,
                'stock_quantity' => 15,
                'sku' => 'CF-UPDATE-02',
                'description' => 'Mô tả mới',
                'is_active' => '1',
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Cà phê đã cập nhật',
            'price' => 170000,
            'sale_price' => 150000,
            'stock_quantity' => 15,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Trà mới',
            'slug' => 'tra-moi',
            'is_active' => true,
        ]);

        $product = \App\Models\Product::factory()->create([
            'name' => 'Trà đào',
            'category_id' => $category->id,
            'price' => 90000,
            'stock_quantity' => 5,
            'sku' => 'TR-DELETE-01',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_can_update_product_image(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Trà sữa',
            'slug' => 'tra-sua',
            'is_active' => true,
        ]);

        $product = \App\Models\Product::factory()->create([
            'name' => 'Trà sữa truyền thống',
            'category_id' => $category->id,
            'price' => 120000,
            'stock_quantity' => 18,
            'sku' => 'TS-IMG-01',
            'image' => null,
        ]);

        $file = UploadedFile::fake()->create('product-update.png', 500, 'image/png');

        $this->actingAs($admin)
            ->patch(route('admin.products.update', $product), [
                'name' => 'Trà sữa truyền thống',
                'category_id' => $category->id,
                'price' => 120000,
                'sale_price' => 100000,
                'stock_quantity' => 18,
                'sku' => 'TS-IMG-02',
                'description' => 'Mô tả mới',
                'is_active' => '1',
                'is_featured' => '1',
                'image' => $file,
            ])
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sku' => 'TS-IMG-02',
        ]);

        $this->assertNotNull($product->fresh()->image);
        $this->assertStringContainsString('products/', $product->fresh()->image);
    }
}
