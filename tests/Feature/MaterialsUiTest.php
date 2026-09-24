<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProjectQuote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialsUiTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_storefront_and_admin_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('همه مصالح ساختمانی');
        $this->get('/admin')->assertOk()->assertSee('داشبورد مدیریت');
        $this->get('/admin/products')->assertOk()->assertSee('مدیریت محصولات و موجودی');
        $this->get('/admin/orders')->assertOk()->assertSee('مدیریت سفارش‌ها، استعلام‌ها و مالی');
        $this->get('/admin/inventory')->assertOk()->assertSee('انبار و موجودی');
        $this->get('/admin/finance')->assertOk()->assertSee('مالی و تسویه');
    }

    public function test_cart_and_project_quote_work(): void
    {
        $product = Product::firstOrFail();

        $this->post(route('cart.add', $product))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(1, session('cart')[$product->id] ?? 0);

        $this->post(route('quote.store'), [
            'project_name' => 'پروژه تست ساختمان',
            'estimated_amount' => 8500000000,
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('project_quotes', [
            'project_name' => 'پروژه تست ساختمان',
            'estimated_amount' => 8500000000,
        ]);
    }
}