<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialsUiTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_storefront_auth_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('همه مصالح ساختمانی');
        $this->get('/login')->assertOk()->assertSee('ورود به حساب کاربری');
        $this->get('/register')->assertOk()->assertSee('ثبت‌نام کاربر جدید');
        $this->get('/admin/login')->assertOk()->assertSee('ورود مدیر سیستم');
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_open_management_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('داشبورد مدیریت');
        $this->actingAs($admin)->get('/admin/products')->assertOk()->assertSee('مدیریت محصولات و موجودی');
        $this->actingAs($admin)->get('/admin/orders')->assertOk()->assertSee('مدیریت سفارش‌ها، استعلام‌ها و مالی');
    }

    public function test_normal_user_cannot_open_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_registration_creates_and_logs_in_user(): void
    {
        $response = $this->post('/register', [
            'name' => 'کاربر تست',
            'email' => 'member@example.test',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'member@example.test', 'role' => 'user']);
    }

    public function test_user_login_and_cart_work(): void
    {
        $user = User::factory()->create([
            'email' => 'buyer@example.test',
            'password' => 'UserPass123!',
            'role' => 'user',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'UserPass123!',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

        $product = Product::firstOrFail();
        $this->post(route('cart.add', $product))
            ->assertRedirect()
            ->assertSessionHas('success');
    }
}
