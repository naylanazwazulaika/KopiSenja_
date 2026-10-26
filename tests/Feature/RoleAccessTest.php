<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected User $otherCustomer;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Owner Admin',
            'email' => 'admin@kopi.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Barista',
            'email' => 'staff@kopi.com',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $this->customer = User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'customer@kopi.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Pelanggan Lain',
            'email' => 'other@kopi.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->category = Category::create([
            'name' => 'Espresso Based',
            'slug' => 'espresso-based',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'ESP-001',
            'name' => 'Single Espresso',
            'purchase_price' => 10000,
            'selling_price' => 20000,
            'stock' => 50,
            'minimum_stock' => 5,
            'is_active' => true,
        ]);
    }

    /**
     * Test 1: Admin dashboard access restriction
     * Hanya role 'admin' yang dapat membuka /admin/dashboard
     */
    public function test_only_admin_can_access_admin_dashboard()
    {
        // Admin: 200 OK
        $responseAdmin = $this->actingAs($this->admin)->get('/admin/dashboard');
        $responseAdmin->assertStatus(200);

        // Staff: 403 Forbidden
        $responseStaff = $this->actingAs($this->staff)->get('/admin/dashboard');
        $responseStaff->assertStatus(403);

        // Customer: 403 Forbidden
        $responseCustomer = $this->actingAs($this->customer)->get('/admin/dashboard');
        $responseCustomer->assertStatus(403);

        // Guest: 302 Redirect to Login
        auth()->logout();
        $this->flushSession();
        $responseGuest = $this->get('/admin/dashboard');
        $responseGuest->assertRedirect('/login');
    }

    /**
     * Test 2: Staff dashboard access restriction
     * Hanya role 'staff' yang dapat membuka /staff/dashboard
     */
    public function test_only_staff_can_access_staff_dashboard()
    {
        // Staff: 200 OK
        $responseStaff = $this->actingAs($this->staff)->get('/staff/dashboard');
        $responseStaff->assertStatus(200);

        // Admin: 403 Forbidden (Hanya akun role staff yang dapat membuka dashboard staff)
        $responseAdmin = $this->actingAs($this->admin)->get('/staff/dashboard');
        $responseAdmin->assertStatus(403);

        // Customer: 403 Forbidden
        $responseCustomer = $this->actingAs($this->customer)->get('/staff/dashboard');
        $responseCustomer->assertStatus(403);

        // Guest: 302 Redirect to Login
        auth()->logout();
        $this->flushSession();
        $responseGuest = $this->get('/staff/dashboard');
        $responseGuest->assertRedirect('/login');
    }

    /**
     * Test 3: Customer dashboard and customer pages access restriction
     * Hanya role 'customer' yang dapat membuka /customer/dashboard dan /customer/profile
     */
    public function test_only_customer_can_access_customer_pages()
    {
        // Customer: 200 OK
        $responseCustomer = $this->actingAs($this->customer)->get('/customer/dashboard');
        $responseCustomer->assertStatus(200);

        $responseProfile = $this->actingAs($this->customer)->get('/customer/profile');
        $responseProfile->assertStatus(200);

        $responseOrders = $this->actingAs($this->customer)->get('/my-orders');
        $responseOrders->assertStatus(200);

        // Admin: 403 Forbidden
        $responseAdmin = $this->actingAs($this->admin)->get('/customer/dashboard');
        $responseAdmin->assertStatus(403);

        // Staff: 403 Forbidden
        $responseStaff = $this->actingAs($this->staff)->get('/customer/dashboard');
        $responseStaff->assertStatus(403);

        // Guest: 302 Redirect to Login
        auth()->logout();
        $this->flushSession();
        $responseGuest = $this->get('/customer/dashboard');
        $responseGuest->assertRedirect('/login');
    }

    /**
     * Test 4: Customer cannot access Admin and Staff operational pages
     * Pelanggan dilarang keras mengakses /products, /categories, /suppliers, /purchases, /sales, /reports, /users
     */
    public function test_customer_cannot_access_internal_management_pages()
    {
        $restrictedUrls = [
            '/products',
            '/categories',
            '/suppliers',
            '/purchases',
            '/purchases/create',
            '/sales',
            '/sales/create',
            '/customer-orders',
            '/reports',
            '/users',
        ];

        foreach ($restrictedUrls as $url) {
            $response = $this->actingAs($this->customer)->get($url);
            $response->assertStatus(403);
        }
    }

    /**
     * Test 5: Staff cannot access sensitive Admin features
     * Staff dilarang mengelola user, membuat/mengedit master data, atau membatalkan transaksi
     */
    public function test_staff_cannot_access_admin_sensitive_management()
    {
        // Kelola user
        $this->actingAs($this->staff)->get('/users')->assertStatus(403);
        $this->actingAs($this->staff)->get('/users/create')->assertStatus(403);

        // Tambah produk / kategori / supplier baru
        $this->actingAs($this->staff)->get('/products/create/new')->assertStatus(403);
        $this->actingAs($this->staff)->get('/categories/create/new')->assertStatus(403);
        $this->actingAs($this->staff)->get('/suppliers/create/new')->assertStatus(403);
    }

    /**
     * Test 6: Role-based Dashboard Dispatcher (/dashboard)
     * Rute /dashboard mengarahkan otomatis sesuai role pengguna
     */
    public function test_dashboard_route_redirects_to_respective_role_dashboard()
    {
        // Admin -> /admin/dashboard
        $responseAdmin = $this->actingAs($this->admin)->get('/dashboard');
        $responseAdmin->assertRedirect(route('admin.dashboard'));

        // Staff -> /staff/dashboard
        $responseStaff = $this->actingAs($this->staff)->get('/dashboard');
        $responseStaff->assertRedirect(route('staff.dashboard'));

        // Customer -> /customer/dashboard
        $responseCustomer = $this->actingAs($this->customer)->get('/dashboard');
        $responseCustomer->assertRedirect(route('customer.dashboard'));
    }

    /**
     * Test 7: Login authentication redirects each role to their respective dashboard
     */
    public function test_login_redirects_each_role_to_respective_dashboard()
    {
        // 1. Admin / Owner login dengan role 'owner'
        $this->post('/login', [
            'role' => 'owner',
            'email' => 'admin@kopi.com',
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->post('/logout');

        // 2. Staff login dengan role 'staff'
        $this->post('/login', [
            'role' => 'staff',
            'email' => 'staff@kopi.com',
            'password' => 'password123',
        ])->assertRedirect(route('staff.dashboard'));

        $this->post('/logout');

        // 3. Customer login dengan role 'customer'
        $this->post('/login', [
            'role' => 'customer',
            'email' => 'customer@kopi.com',
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));
    }

    /**
     * Test 7B: Login menolak jika role yang dipilih tidak sesuai dengan peran akun di database
     */
    public function test_login_rejects_when_selected_role_mismatches_account_role()
    {
        // Customer mencoba memilih role 'owner'
        $response = $this->post('/login', [
            'role' => 'owner',
            'email' => 'customer@kopi.com',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        // Admin mencoba memilih role 'staff'
        $response2 = $this->post('/login', [
            'role' => 'staff',
            'email' => 'admin@kopi.com',
            'password' => 'password123',
        ]);
        $response2->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 8: Public registration always forces role 'customer' from server
     * Input role 'admin' atau 'staff' diabaikan
     */
    public function test_public_registration_strictly_assigns_customer_role()
    {
        $response = $this->post('/register', [
            'name' => 'Hacker Trying Admin',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Tampering attempt
        ]);

        $user = User::where('email', 'hacker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
        $response->assertRedirect(route('customer.dashboard'));
    }

    /**
     * Test 9: Customer profile update cannot alter user role
     */
    public function test_customer_cannot_elevate_role_via_profile_update()
    {
        $response = $this->actingAs($this->customer)->put('/customer/profile', [
            'name' => 'Updated Customer Name',
            'email' => 'customer@kopi.com',
            'phone' => '08123456789',
            'role' => 'admin', // Injected role attempt
        ]);

        $response->assertSessionHas('success');
        $this->customer->refresh();
        $this->assertEquals('Updated Customer Name', $this->customer->name);
        $this->assertEquals('customer', $this->customer->role); // Role tetap customer
    }

    /**
     * Test 10: Customer cannot view other customer's order receipt
     */
    public function test_customer_cannot_view_other_customer_receipt()
    {
        $sale = Sale::create([
            'user_id' => $this->otherCustomer->id,
            'sale_number' => 'ORD-TEST-999',
            'customer_name' => 'Pelanggan Lain',
            'order_type' => 'dine_in',
            'payment_method' => 'cash',
            'total' => 50000,
            'status' => 'pending',
            'sold_at' => now(),
        ]);

        // Customer trying to view other customer's receipt via customer route
        $response = $this->actingAs($this->customer)->get('/my-orders/' . $sale->id);
        $response->assertStatus(403);

        // Customer who owns the order can view receipt
        $responseOwner = $this->actingAs($this->otherCustomer)->get('/my-orders/' . $sale->id);
        $responseOwner->assertStatus(200);
    }
}
