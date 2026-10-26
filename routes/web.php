<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KOPI SENJA (Coffee Shop & Pastry Management System)
|--------------------------------------------------------------------------
*/

// 1. Public Landing Page & Catalog
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/menu', [ShopController::class, 'index'])->name('shop.index');
Route::get('/menu/{product}', [ShopController::class, 'show'])->name('shop.show');

// 2. Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 3. Customer Cart System (Public & Authenticated)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// 4. Authenticated Routes
Route::middleware('auth')->group(function () {

    // Role-based Dashboard Dispatcher (mengarahkan otomatis ke dashboard sesuai role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Only Routes (Khusus Pelanggan / Customer)
    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/dashboard', [DashboardController::class, 'customerDashboard'])->name('customer.dashboard');
        Route::get('/customer/profile', [ShopController::class, 'profile'])->name('customer.profile');
        Route::put('/customer/profile', [ShopController::class, 'updateProfile'])->name('customer.profile.update');

        Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/checkout', [CartController::class, 'processCheckout'])->name('cart.process-checkout');
        Route::get('/my-orders', [ShopController::class, 'myOrders'])->name('shop.my-orders');
        Route::get('/my-orders/{sale}', [ShopController::class, 'orderReceipt'])->name('customer.orders.show');
        Route::get('/orders/{sale}/success', [ShopController::class, 'orderSuccess'])->name('shop.order-success');
        Route::post('/my-orders/{sale}/cancel', [SaleController::class, 'customerCancel'])->name('customer.orders.cancel');
    });

    // Staff Only Routes (Hanya akun dengan role staff yang dapat membuka dashboard Staff)
    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', [DashboardController::class, 'staffDashboard'])->name('staff.dashboard');
    });

    // Routes accessible by Admin & Staff (Operasional Coffee Shop & POS)
    Route::middleware('role:admin,staff')->group(function () {
        // Products (viewing)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

        // Suppliers (viewing for staff)
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

        // Categories (viewing for staff)
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        // Purchases (Stock-In)
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

        // Sales (Kasir POS Internal)
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

        // Incoming Customer Orders Management (Staff & Admin)
        Route::get('/customer-orders', [SaleController::class, 'customerOrders'])->name('customer-orders.index');
        Route::patch('/customer-orders/{sale}/status', [SaleController::class, 'updateStatus'])->name('customer-orders.update-status');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    });

    // Admin Only Management (PPT Slide 5: User management, Sensitive master data CRUD, Transaction cancellation)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

        // Product Management (Create, Edit, Delete)
        Route::get('/products/create/new', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Category Management
        Route::get('/categories/create/new', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Supplier Management
        Route::get('/suppliers/create/new', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        // User Management (Admin only)
        Route::resource('users', UserController::class);

        // Transaction Cancellations with consistent stock rollback (Admin only)
        Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
        Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    });
});
