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

class CoffeeInventoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $customer;
    protected $otherCustomer;
    protected $category;
    protected $supplier;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@kopi.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Toko',
            'email' => 'staff@kopi.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->customer = User::create([
            'name' => 'Customer Penikmat',
            'email' => 'customer@kopi.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Other Customer',
            'email' => 'other@kopi.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->category = Category::create([
            'name' => 'Biji Kopi Arabika',
            'slug' => 'biji-kopi-arabika',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'Petani Gayo',
            'contact_person' => 'Pak Hendra',
            'phone' => '081234567890',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'KOP-GYO-250',
            'name' => 'Gayo Wine 250g',
            'purchase_price' => 60000,
            'selling_price' => 90000,
            'stock' => 20,
            'minimum_stock' => 5,
            'is_active' => true,
        ]);
    }

    /**
     * 1. Auth Test: Guest tidak dapat membuka dashboard; password salah ditolak.
     */
    public function test_guest_cannot_access_dashboard_and_wrong_password_rejected()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $loginResponse = $this->post('/login', [
            'email' => 'admin@kopi.com',
            'password' => 'wrongpassword123',
        ]);
        $loginResponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * 2. Auth Test: Registrasi publik selalu menghasilkan role pelanggan.
     */
    public function test_registration_always_creates_customer_role_even_if_role_submitted()
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'newuser@kopi.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Tampering attempt!
        ]);

        $user = User::where('email', 'newuser@kopi.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role); // Must be customer, not admin!
    }

    /**
     * 3. Authorization: Pelanggan tidak dapat membuka dashboard staff atau admin.
     */
    public function test_customer_cannot_access_staff_or_admin_dashboard()
    {
        $responseAdmin = $this->actingAs($this->customer)->get('/admin/dashboard');
        $responseAdmin->assertStatus(403);

        $responseStaff = $this->actingAs($this->customer)->get('/staff/dashboard');
        $responseStaff->assertStatus(403);

        $responsePurchases = $this->actingAs($this->customer)->get('/purchases');
        $responsePurchases->assertStatus(403);
    }

    /**
     * 4. Authorization: Staff tidak dapat mengakses fungsi Admin.
     */
    public function test_staff_cannot_access_admin_user_management_or_cancel_routes()
    {
        $responseUser = $this->actingAs($this->staff)->get('/users');
        $responseUser->assertStatus(403);

        $responseCreateProduct = $this->actingAs($this->staff)->get('/products/create/new');
        $responseCreateProduct->assertStatus(403);
    }

    /**
     * 5. Product Test: SKU duplikat ditolak; harga negatif ditolak.
     */
    public function test_duplicate_sku_and_negative_price_are_rejected()
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'sku' => 'KOP-GYO-250', // Duplikat
            'name' => 'Duplicate Gayo',
            'category_id' => $this->category->id,
            'purchase_price' => 50000,
            'selling_price' => 80000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);
        $response->assertSessionHasErrors('sku');

        $responseNeg = $this->actingAs($this->admin)->post('/products', [
            'sku' => 'KOP-NEW-111',
            'name' => 'Valid Name',
            'category_id' => $this->category->id,
            'purchase_price' => -5000, // Negatif
            'selling_price' => 80000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);
        $responseNeg->assertSessionHasErrors('purchase_price');
    }

    /**
     * 6. Purchase Test: Pembelian sukses menambah stok sesuai kuantitas.
     */
    public function test_successful_purchase_increments_stock_properly()
    {
        $initialStock = $this->product->stock; // 20
        $qtyToBuy = 15;

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'purchased_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToBuy,
                    'unit_cost' => 60000,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $this->product->refresh();
        $this->assertEquals($initialStock + $qtyToBuy, $this->product->stock); // 35
    }

    /**
     * 7. Sale Test: Penjualan sukses mengurangi stok sesuai kuantitas.
     */
    public function test_successful_sale_decrements_stock_properly()
    {
        $initialStock = $this->product->stock; // 20
        $qtyToSell = 5;

        $response = $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'Budi',
            'order_type' => 'pos',
            'payment_method' => 'cash',
            'sold_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToSell,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $this->product->refresh();
        $this->assertEquals($initialStock - $qtyToSell, $this->product->stock); // 15
    }

    /**
     * 8. Insufficient stock: Penjualan dengan stok tidak cukup ditolak tanpa perubahan data.
     */
    public function test_sale_with_insufficient_stock_is_rejected_without_data_change()
    {
        $initialStock = $this->product->stock; // 20
        $excessiveQty = 999;

        $response = $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'Budi',
            'order_type' => 'pos',
            'payment_method' => 'cash',
            'sold_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $excessiveQty,
                ]
            ]
        ]);

        $response->assertSessionHasErrors('items');
        $this->product->refresh();
        $this->assertEquals($initialStock, $this->product->stock);
        $this->assertEquals(0, Sale::count());
    }

    /**
     * 9. Atomicity: Jika satu item transaksi gagal, seluruh transaksi rollback.
     */
    public function test_transaction_atomicity_rollbacks_entirely_when_one_item_fails()
    {
        $p2 = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'KOP-TRJ-250',
            'name' => 'Toraja Sapan 250g',
            'purchase_price' => 50000,
            'selling_price' => 85000,
            'stock' => 2,
            'minimum_stock' => 5,
        ]);

        $p1InitialStock = $this->product->stock; // 20

        $response = $this->actingAs($this->staff)->post('/sales', [
            'customer_name' => 'Test Atomicity',
            'order_type' => 'pos',
            'payment_method' => 'cash',
            'sold_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 3, // Valid
                ],
                [
                    'product_id' => $p2->id,
                    'quantity' => 10, // Invalid, exceeds stock 2!
                ]
            ]
        ]);

        $response->assertSessionHasErrors('items');
        $this->product->refresh();
        $p2->refresh();
        $this->assertEquals($p1InitialStock, $this->product->stock);
        $this->assertEquals(2, $p2->stock);
        $this->assertEquals(0, Sale::count());
    }

    /**
     * 10. Customer Cart & Checkout: Menyimpan transaksi, menghitung total di server, mengurangi stok.
     */
    public function test_customer_can_add_to_cart_and_checkout_successfully()
    {
        $initialStock = $this->product->stock; // 20
        $qtyOrder = 2;

        // 1. Add to cart
        $this->actingAs($this->customer)->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => $qtyOrder,
        ]);

        $cart = session('cart');
        $this->assertArrayHasKey($this->product->id, $cart);
        $this->assertEquals($qtyOrder, $cart[$this->product->id]['quantity']);

        // 2. Checkout
        $responseCheckout = $this->actingAs($this->customer)->post('/checkout', [
            'customer_name' => 'Customer Penikmat',
            'customer_phone' => '08123456789',
            'order_type' => 'dine_in',
            'payment_method' => 'qris',
            'notes' => 'Less sugar',
        ]);

        // Assert redirect to success
        $this->product->refresh();
        $this->assertEquals($initialStock - $qtyOrder, $this->product->stock); // 18
        $this->assertEmpty(session('cart')); // Cart cleared!

        $sale = Sale::latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('pending', $sale->status);
        $this->assertEquals(90000 * $qtyOrder, $sale->total); // Server calculation!
    }

    /**
     * 11. Authorization: Pelanggan tidak dapat melihat transaksi / nota orang lain.
     */
    public function test_customer_cannot_view_other_customer_orders()
    {
        $order = Sale::create([
            'user_id' => $this->otherCustomer->id,
            'sale_number' => 'ORD-TEST-001',
            'customer_name' => 'Other Customer',
            'total' => 90000,
            'status' => 'pending',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)->get('/sales/' . $order->id);
        $response->assertStatus(403);
    }

    /**
     * 12. Staff can update order status along the workflow.
     */
    public function test_staff_can_update_order_status()
    {
        $order = Sale::create([
            'user_id' => $this->customer->id,
            'sale_number' => 'ORD-TEST-002',
            'customer_name' => 'Customer',
            'total' => 90000,
            'status' => 'pending',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($this->staff)->patch('/customer-orders/' . $order->id . '/status', [
            'status' => 'confirmed',
        ]);

        $order->refresh();
        $this->assertEquals('confirmed', $order->status);
    }

    /**
     * 13. Cancellation restores stock and prevents double-restoration.
     */
    public function test_cancellation_restores_stock_and_prevents_double_restoration()
    {
        // Setup a sale that decremented stock by 3
        $initialStock = $this->product->stock; // 20
        $qtySold = 3;

        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'sale_number' => 'ORD-TEST-003',
            'customer_name' => 'Customer Penikmat',
            'total' => 270000,
            'status' => 'pending',
            'sold_at' => now(),
        ]);

        $sale->items()->create([
            'product_id' => $this->product->id,
            'quantity' => $qtySold,
            'unit_price' => 90000,
            'subtotal' => 270000,
        ]);
        $this->product->decrement('stock', $qtySold); // Stock becomes 17

        $this->assertEquals(17, $this->product->fresh()->stock);

        // First cancellation (by customer when pending)
        $this->actingAs($this->customer)->post('/my-orders/' . $sale->id . '/cancel');

        $this->assertEquals(20, $this->product->fresh()->stock); // Restored to 20!
        $this->assertEquals('cancelled', $sale->fresh()->status);

        // Attempt second cancellation - must NOT restore again!
        $this->actingAs($this->admin)->post('/sales/' . $sale->id . '/cancel');

        $this->assertEquals(20, $this->product->fresh()->stock); // Still 20, not 23!
    }

    /**
     * 14. Search & Filter
     */
    public function test_search_and_filter_yield_matching_results()
    {
        $response = $this->actingAs($this->admin)->get('/products?search=Gayo');
        $response->assertStatus(200);
        $response->assertSee('Gayo Wine 250g');

        $responseEmpty = $this->actingAs($this->admin)->get('/products?search=NonExistentSKU999');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertDontSee('Gayo Wine 250g');
    }
}
