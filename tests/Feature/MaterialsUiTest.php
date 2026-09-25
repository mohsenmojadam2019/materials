<?php

namespace Tests\Feature;

use App\Models\{User,Product,Order,ProjectQuote,Warehouse,Category,Article,B2BRequest};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

class MaterialsUiTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;

    public function test_public_store_catalog_content_and_seo_render(): void
    {
        $product=Product::firstOrFail();
        $this->get('/')->assertOk()->assertSee('همه مصالح پروژه شما');
        $this->get(route('product.show',$product))->assertOk()->assertSee($product->name)->assertSee('تاریخچه قیمت');
        $this->get('/blog')->assertOk()->assertSee('وبلاگ');
        $this->get('/faq')->assertOk()->assertSee('سوالات متداول');
        $this->get('/sitemap.xml')->assertOk()->assertSee('<urlset',false);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    public function test_auth_register_login_profile_and_password_reset(): void
    {
        $this->get('/login')->assertOk()->assertSee('ورود به حساب کاربری');
        $this->get('/register')->assertOk()->assertSee('ثبت‌نام کاربر جدید');

        $this->post('/register',[
            'name'=>'کاربر تست','email'=>'member@example.test',
            'password'=>'SecurePass123!','password_confirmation'=>'SecurePass123!',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->patch('/account/profile',['name'=>'کاربر ویرایش‌شده','email'=>'member@example.test'])
            ->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('users',['email'=>'member@example.test','name'=>'کاربر ویرایش‌شده']);

        auth()->logout();
        Notification::fake();
        $this->post('/forgot-password',['email'=>'member@example.test'])->assertSessionHasNoErrors();
        Notification::assertSentTo(User::where('email','member@example.test')->first(),ResetPassword::class);
    }

    public function test_admin_is_protected_and_roles_are_enforced(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $user=User::factory()->create(['role'=>'user']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
        $admin=User::factory()->create(['role'=>'admin']);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('داشبورد مدیریت');
    }

    public function test_wishlist_compare_and_cart_are_functional(): void
    {
        $p=Product::where('stock','>',5)->firstOrFail();
        $this->post(route('wishlist.toggle',$p))->assertRedirect();
        $this->assertContains($p->id,session('wishlist'));
        $this->post(route('compare.toggle',$p))->assertRedirect();
        $this->assertContains($p->id,session('compare'));

        $this->post(route('cart.add',$p),['qty'=>2])->assertRedirect();
        $this->assertSame(2,session('cart')[$p->id]);
        $this->get(route('cart.index'))->assertOk()->assertSee($p->name);
        $this->patch(route('cart.update',$p),['qty'=>3])->assertRedirect();
        $this->assertSame(3,session('cart')[$p->id]);
        $this->delete(route('cart.remove',$p))->assertRedirect();
        $this->assertArrayNotHasKey($p->id,session('cart',[]));
    }

    public function test_checkout_creates_order_items_shipment_finance_and_reduces_stock(): void
    {
        $user=User::factory()->create(['role'=>'user']);
        $p=Product::where('stock','>',10)->firstOrFail();
        $before=$p->stock;
        $this->actingAs($user)->post(route('cart.add',$p),['qty'=>2]);
        $response=$this->actingAs($user)->post(route('checkout.store'),[
            'recipient'=>'علی رضایی','phone'=>'09120000000','province'=>'تهران','city'=>'تهران',
            'shipping_address'=>'خیابان تست، پلاک ۱۰','postal_code'=>'1234567890',
            'shipping_method'=>'carrier','payment_method'=>'bank_transfer','invoice_type'=>'personal','notes'=>'تست سفارش',
        ]);
        $order=Order::where('user_id',$user->id)->latest()->firstOrFail();
        $response->assertRedirect(route('account.order',$order));
        $this->assertDatabaseHas('order_items',['order_id'=>$order->id,'product_id'=>$p->id,'qty'=>2]);
        $this->assertDatabaseHas('shipments',['order_id'=>$order->id]);
        $this->assertDatabaseHas('financial_transactions',['order_id'=>$order->id]);
        $this->assertSame($before-2,$p->fresh()->stock);
        $this->assertEmpty(session('cart',[]));
    }

    public function test_customer_account_addresses_orders_and_b2b_requests_work(): void
    {
        $user=User::factory()->create(['role'=>'user']);
        $this->actingAs($user)->post(route('account.addresses.store'),[
            'title'=>'پروژه','recipient'=>'کاربر تست','phone'=>'09121111111',
            'province'=>'تهران','city'=>'تهران','address'=>'آدرس پروژه','postal_code'=>'1111111111',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('addresses',['user_id'=>$user->id,'title'=>'پروژه']);

        $this->actingAs($user)->post(route('b2b.store','wholesale'),[
            'company_name'=>'شرکت تست','national_id'=>'101010','contact_name'=>'کاربر تست',
            'phone'=>'09121111111','amount'=>5000000000,'description'=>'خرید عمده سیمان',
        ])->assertRedirect(route('account.dashboard'));
        $this->assertDatabaseHas('b2b_requests',['user_id'=>$user->id,'type'=>'wholesale']);

        $this->actingAs($user)->post(route('b2b.store','credit'),[
            'company_name'=>'شرکت تست','contact_name'=>'کاربر تست','phone'=>'09121111111',
            'amount'=>9000000000,'term_days'=>60,'description'=>'اعتبار پروژه',
        ])->assertRedirect(route('account.dashboard'));
        $this->assertSame(2,B2BRequest::where('user_id',$user->id)->count());
    }

    public function test_admin_can_manage_product_category_inventory_order_quote_settings_and_content(): void
    {
        $admin=User::factory()->create(['role'=>'admin']);
        $category=Category::firstOrFail();

        $this->actingAs($admin)->post(route('admin.categories.store'),[
            'name'=>'مصالح آزمایشی','slug'=>'test-materials',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('categories',['slug'=>'test-materials']);

        $this->actingAs($admin)->post(route('admin.products.store'),[
            'name'=>'محصول تست','sku'=>'TEST-001','category_id'=>$category->id,'unit'=>'عدد',
            'price'=>2500000,'stock'=>100,'brand'=>'برند تست','origin'=>'ایران',
            'loading_location'=>'انبار مرکزی','tax_percent'=>10,
        ])->assertSessionHas('success');
        $product=Product::where('sku','TEST-001')->firstOrFail();

        $this->actingAs($admin)->patch(route('admin.products.update',$product),[
            'name'=>'محصول تست ویرایش','category_id'=>$category->id,'unit'=>'عدد',
            'price'=>2700000,'stock'=>95,'brand'=>'برند تست','loading_location'=>'انبار مرکزی',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('price_histories',['product_id'=>$product->id,'price'=>2700000]);

        $warehouse=Warehouse::firstOrFail();
        $this->actingAs($admin)->post(route('admin.inventory.update'),[
            'warehouse_id'=>$warehouse->id,'product_id'=>$product->id,'qty'=>77,
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('warehouse_stocks',['warehouse_id'=>$warehouse->id,'product_id'=>$product->id,'qty'=>77]);

        $order=Order::firstOrFail();
        $this->actingAs($admin)->patch(route('admin.orders.update',$order),[
            'status'=>'confirmed','payment_status'=>'paid','shipping_status'=>'ready',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('orders',['id'=>$order->id,'status'=>'confirmed','payment_status'=>'paid']);

        $quote=ProjectQuote::firstOrFail();
        $this->actingAs($admin)->patch(route('admin.quotes.update',$quote),[
            'status'=>'quoted','estimated_amount'=>$quote->estimated_amount,'proforma_no'=>'PF-1001','notes'=>'ارسال شد',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('project_quotes',['id'=>$quote->id,'proforma_no'=>'PF-1001']);

        $this->actingAs($admin)->post(route('admin.settings.save'),[
            'store_name'=>'شرکت مصالح نمونه','store_phone'=>'02112345678','store_email'=>'sales@example.com',
            'store_address'=>'تهران','primary_color'=>'#123456',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('store_settings',['key'=>'store_name','value'=>'شرکت مصالح نمونه']);

        $this->actingAs($admin)->post(route('admin.content.store'),[
            'title'=>'مقاله تست مدیریت','excerpt'=>'خلاصه تست','body'=>'متن کامل مقاله تست','category'=>'راهنمای خرید',
        ])->assertSessionHas('success');
        $this->assertTrue(Article::where('title','مقاله تست مدیریت')->exists());
    }

    public function test_main_admin_pages_render_for_admin(): void
    {
        $admin=User::factory()->create(['role'=>'admin']);
        foreach(['/admin','/admin/products','/admin/orders','/admin/categories','/admin/inventory','/admin/projects','/admin/customers','/admin/finance','/admin/reports','/admin/content','/admin/settings'] as $url){
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_remaining_admin_operations_are_real_and_editable(): void
    {
        $admin=User::factory()->create(["role"=>"admin"]);

        foreach(["/admin/suppliers","/admin/pricing","/admin/logistics","/admin/users","/admin/discounts","/admin/tickets"] as $url){
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $this->actingAs($admin)->post(route("admin.suppliers.store"),[
            "name"=>"تأمین تست","phone"=>"021000000","city"=>"تهران","status"=>"active",
        ])->assertSessionHas("success");
        $this->assertDatabaseHas("suppliers",["name"=>"تأمین تست"]);

        $product=Product::firstOrFail();
        $this->actingAs($admin)->patch(route("admin.pricing.update",$product),[
            "price"=>$product->price+1000,"old_price"=>$product->price,"tax_percent"=>10,
        ])->assertSessionHas("success");

        $shipment=\App\Models\Shipment::firstOrFail();
        $this->actingAs($admin)->patch(route("admin.logistics.update",$shipment),[
            "carrier"=>"باربری تست","tracking_code"=>"TEST-TRACK","status"=>"in_transit","destination"=>"تهران",
        ])->assertSessionHas("success");
        $this->assertDatabaseHas("shipments",["id"=>$shipment->id,"tracking_code"=>"TEST-TRACK"]);

        $user=User::factory()->create(["role"=>"user"]);
        $this->actingAs($admin)->patch(route("admin.users.role",$user),["role"=>"admin"])->assertSessionHas("success");
        $this->assertDatabaseHas("users",["id"=>$user->id,"role"=>"admin"]);

        $this->actingAs($admin)->post(route("admin.discounts.store"),[
            "code"=>"PROJECT10","type"=>"percent","value"=>10,"min_order"=>1000000,
        ])->assertSessionHas("success");
        $this->assertDatabaseHas("coupons",["code"=>"PROJECT10","value"=>10]);

        $ticket=\App\Models\Ticket::firstOrFail();
        $this->actingAs($admin)->patch(route("admin.tickets.update",$ticket),[
            "status"=>"resolved","priority"=>"normal",
        ])->assertSessionHas("success");
        $this->assertDatabaseHas("tickets",["id"=>$ticket->id,"status"=>"resolved"]);
    }
}
