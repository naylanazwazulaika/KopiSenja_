<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Demo Users (Admin, Staff, Pelanggan)
        $admin = User::firstOrCreate(
            ['email' => 'admin@kopi.com'],
            [
                'name' => 'Senja Owner Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Senja Raya No. 10, Jakarta Selatan',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@kopi.com'],
            [
                'name' => 'Dimas Barista Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '0813-8899-7766',
                'address' => 'Jl. Kopi Harapan No. 4, Jakarta Selatan',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@kopi.com'],
            [
                'name' => 'Nabila Putri Pelanggan',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0857-1122-3344',
                'address' => 'Jl. Tebet Barat No. 22, Jakarta Selatan',
            ]
        );

        // Also create alias pelanggan@kopi.com for convenience
        User::firstOrCreate(
            ['email' => 'pelanggan@kopi.com'],
            [
                'name' => 'Rian Penikmat Senja',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0878-5544-3322',
                'address' => 'Jl. Kemang Timur No. 18, Jakarta Selatan',
            ]
        );

        // 2. Seed Categories (Wajib sesuai prompt)
        $categoriesData = [
            [
                'name' => 'Espresso Based',
                'slug' => 'espresso-based',
                'description' => 'Ekstraksi kopi espresso murni dan perpaduan susu steam bertekstur lembut.',
            ],
            [
                'name' => 'Signature Coffee',
                'slug' => 'signature-coffee',
                'description' => 'Racikan kopi istimewa khas Kopi Senja dengan gula aren dan sirup rahasia.',
            ],
            [
                'name' => 'Manual Brew',
                'slug' => 'manual-brew',
                'description' => 'Seduhan kopi manual menggunakan filter V60, Japanese Drip, dan Aeropress.',
            ],
            [
                'name' => 'Non-Coffee',
                'slug' => 'non-coffee',
                'description' => 'Pilihan minuman segar non-kopi dari artisan cokelat hingga matcha premium.',
            ],
            [
                'name' => 'Tea',
                'slug' => 'tea',
                'description' => 'Seduhan teh artisan herbal dan buah segar pilihan nusantara.',
            ],
            [
                'name' => 'Pastry',
                'slug' => 'pastry',
                'description' => 'Pastry renyah berlapis mentega Prancis otentik, dipanggang hangat setiap pagi.',
            ],
            [
                'name' => 'Bakery',
                'slug' => 'bakery',
                'description' => 'Roti lembut beraroma butter dan rempah kayu manis favorit keluarga.',
            ],
            [
                'name' => 'Snacks',
                'slug' => 'snacks',
                'description' => 'Camilan gurih teman setia secangkir kopi hangat di sore hari.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::firstOrCreate(['slug' => $cData['slug']], $cData);
        }

        // 3. Seed Suppliers (Wajib sesuai prompt dengan contact_person)
        $suppliersData = [
            [
                'name' => 'Supplier Biji Kopi Nusantara',
                'contact_person' => 'Pak Hendra Saputra',
                'phone' => '0812-7788-9900',
                'email' => 'hendra@kopinusantara.id',
                'address' => 'Perkebunan Gayo & Ciwidey, Takengon - Bandung',
            ],
            [
                'name' => 'Supplier Susu Segar Sejahtera',
                'contact_person' => 'Ibu Ratna Dewi',
                'phone' => '0813-2233-4455',
                'email' => 'sales@sususegarsejahtera.com',
                'address' => 'Peternakan Lembang, Jawa Barat',
            ],
            [
                'name' => 'Supplier Bakery & Pastry Ingredients',
                'contact_person' => 'Chef Arthur Wijaya',
                'phone' => '0821-9988-7766',
                'email' => 'arthur@pastrysupply.id',
                'address' => 'Sentra Bahan Kue Pondok Indah, Jakarta',
            ],
            [
                'name' => 'Supplier Sirup dan Bahan Minuman',
                'contact_person' => 'Kevin Pratama',
                'phone' => '0811-3344-5566',
                'email' => 'info@syrupbeverages.co.id',
                'address' => 'Kawasan Industri Rawa Buaya, Jakarta Barat',
            ],
            [
                'name' => 'Supplier Kemasan Kopi & Cup Ramah Lingkungan',
                'contact_person' => 'Dina Lestari',
                'phone' => '0877-6655-4433',
                'email' => 'packaging@ecosenja.id',
                'address' => 'Kawasan Industri Cikarang, Bekasi',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sData['name']], $sData);
        }

        // 4. Seed Products (Lengkap sesuai contoh prompt dengan foto Unsplash estetik)
        $productsData = [
            // Espresso Based
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-001',
                'name' => 'Espresso Single Origin',
                'description' => 'Konsentrat kopi murni hasil ekstraksi tekanan tinggi dengan crema tebal keemasan.',
                'purchase_price' => 8000,
                'selling_price' => 20000,
                'stock' => 45,
                'minimum_stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-002',
                'name' => 'Americano',
                'description' => 'Espresso ganda dipadukan dengan air panas murni, bersih dan berkarakter kuat.',
                'purchase_price' => 10000,
                'selling_price' => 25000,
                'stock' => 40,
                'minimum_stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-003',
                'name' => 'Cafe Latte',
                'description' => 'Espresso kaya rasa berpadu dengan susu steam lembut dan microfoam halus.',
                'purchase_price' => 14000,
                'selling_price' => 32000,
                'stock' => 30,
                'minimum_stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-004',
                'name' => 'Cappuccino',
                'description' => 'Komposisi seimbang espresso, susu hangat, dan busa tebal berhias taburan kakao.',
                'purchase_price' => 14000,
                'selling_price' => 32000,
                'stock' => 35,
                'minimum_stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-005',
                'name' => 'Spanish Latte',
                'description' => 'Perpaduan espresso mantap dengan sentuhan susu kental manis gurih ala Spanyol.',
                'purchase_price' => 15000,
                'selling_price' => 35000,
                'stock' => 25,
                'minimum_stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'espresso-based',
                'sku' => 'ESP-006',
                'name' => 'Vanilla Latte',
                'description' => 'Espresso dan susu segar dipadukan dengan ekstrak sirup vanila Madagaskar harum.',
                'purchase_price' => 16000,
                'selling_price' => 36000,
                'stock' => 20,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=600&q=80',
            ],

            // Signature Coffee
            [
                'category' => 'signature-coffee',
                'sku' => 'SIG-001',
                'name' => 'Caramel Macchiato Senja',
                'description' => 'Signature latte berlapis vanila, espresso ganda, dan siraman saus karamel legit.',
                'purchase_price' => 17000,
                'selling_price' => 38000,
                'stock' => 22,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'signature-coffee',
                'sku' => 'SIG-002',
                'name' => 'Kopi Senja Aren Signature',
                'description' => 'Espresso blend pilihan disatukan dengan susu segar dan gula aren organik Lebak.',
                'purchase_price' => 12000,
                'selling_price' => 28000,
                'stock' => 50,
                'minimum_stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'signature-coffee',
                'sku' => 'SIG-003',
                'name' => 'Cold Brew Senja Reserve',
                'description' => 'Kopi seduh dingin selama 18 jam, menghasilkan body halus tanpa rasa asam menusuk.',
                'purchase_price' => 15000,
                'selling_price' => 34000,
                'stock' => 18,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=600&q=80',
            ],

            // Manual Brew
            [
                'category' => 'manual-brew',
                'sku' => 'MNL-001',
                'name' => 'V60 Pour Over Aceh Gayo',
                'description' => 'Seduhan manual V60 menonjolkan keasaman buah tropis dan floral notes yang elegan.',
                'purchase_price' => 15000,
                'selling_price' => 32000,
                'stock' => 28,
                'minimum_stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'manual-brew',
                'sku' => 'MNL-002',
                'name' => 'V60 Toraja Sapan Specialty',
                'description' => 'Manual brew biji kopi Toraja Sapan dengan body tebal dan sentuhan rempah herbal.',
                'purchase_price' => 16000,
                'selling_price' => 35000,
                'stock' => 15,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1509785307050-d4066910ec1e?auto=format&fit=crop&w=600&q=80',
            ],

            // Non-Coffee
            [
                'category' => 'non-coffee',
                'sku' => 'NON-001',
                'name' => 'Artisan Matcha Latte',
                'description' => 'Bubuk Uji Matcha Jepang murni berpadu dengan susu oat creamy dan sentuhan manis.',
                'purchase_price' => 17000,
                'selling_price' => 35000,
                'stock' => 20,
                'minimum_stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'non-coffee',
                'sku' => 'NON-002',
                'name' => 'Belgian Dark Chocolate',
                'description' => 'Cokelat hitam Belgia kental dipanaskan bersama susu murni segar, manis-pahit seimbang.',
                'purchase_price' => 16000,
                'selling_price' => 33000,
                'stock' => 24,
                'minimum_stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80',
            ],

            // Tea
            [
                'category' => 'tea',
                'sku' => 'TEA-001',
                'name' => 'Earl Grey Lavender Tea',
                'description' => 'Black tea beraroma citrus bergamot dan kelopak bunga lavender menenangkan jiwa.',
                'purchase_price' => 10000,
                'selling_price' => 26000,
                'stock' => 30,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=600&q=80',
            ],

            // Pastry
            [
                'category' => 'pastry',
                'sku' => 'PST-001',
                'name' => 'Butter Croissant Premium',
                'description' => 'Croissant Prancis berlapis renyah keemasan dengan aroma butter wangi menggoda.',
                'purchase_price' => 12000,
                'selling_price' => 25000,
                'stock' => 18,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'pastry',
                'sku' => 'PST-002',
                'name' => 'Pain Au Chocolat',
                'description' => 'Pastry renyah butter berisi dua batang cokelat couverture hitam meleleh di mulut.',
                'purchase_price' => 14000,
                'selling_price' => 28000,
                'stock' => 12,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1530610476181-d83430b64dcd?auto=format&fit=crop&w=600&q=80',
            ],

            // Bakery
            [
                'category' => 'bakery',
                'sku' => 'BKR-001',
                'name' => 'Cinnamon Roll Glaze',
                'description' => 'Roti gulung lembut kaya bumbu kayu manis harum dengan lelehan cream cheese glaze.',
                'purchase_price' => 15000,
                'selling_price' => 30000,
                'stock' => 14,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'bakery',
                'sku' => 'BKR-002',
                'name' => 'Fudgy Dark Brownies',
                'description' => 'Brownies cokelat panggang tekstur fudgy pekat dengan taburan almond renyah.',
                'purchase_price' => 16000,
                'selling_price' => 32000,
                'stock' => 4, // Low stock product for alert testing!
                'minimum_stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=600&q=80',
            ],

            // Snacks
            [
                'category' => 'snacks',
                'sku' => 'SNK-001',
                'name' => 'Truffle Fries with Dip',
                'description' => 'Kentang goreng renyah bumbu garam laut dan minyak truffle harum disajikan dengan garlic aioli.',
                'purchase_price' => 14000,
                'selling_price' => 28000,
                'stock' => 25,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'snacks',
                'sku' => 'SNK-002',
                'name' => 'Crispy Chicken Wings BBQ',
                'description' => 'Sayap ayam goreng renyah berlumur saus barbecue asap manis gurih.',
                'purchase_price' => 18000,
                'selling_price' => 35000,
                'stock' => 3, // Low stock alert item!
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $products = [];
        foreach ($productsData as $pData) {
            $catSlug = $pData['category'];
            unset($pData['category']);
            $pData['category_id'] = $categories[$catSlug]->id;
            $pData['is_active'] = true;
            $products[$pData['sku']] = Product::create($pData);
        }

        // 5. Seed Historical Purchases (Stock-In Transactions)
        $purchase1 = Purchase::create([
            'supplier_id' => $suppliers[0]->id, // Biji Kopi
            'user_id' => $staff->id,
            'purchase_number' => 'PO-' . date('Ymd') . '-0001',
            'total' => 1250000,
            'status' => 'completed',
            'notes' => 'Pengadaan stok biji kopi untuk espresso dan filter mingguan.',
            'purchased_at' => Carbon::now()->subDays(3),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $products['ESP-001']->id,
            'quantity' => 25,
            'unit_cost' => 8000,
            'subtotal' => 200000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $products['MNL-001']->id,
            'quantity' => 20,
            'unit_cost' => 15000,
            'subtotal' => 300000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $products['SIG-002']->id,
            'quantity' => 30,
            'unit_cost' => 12000,
            'subtotal' => 360000,
        ]);

        $purchase2 = Purchase::create([
            'supplier_id' => $suppliers[2]->id, // Bakery
            'user_id' => $admin->id,
            'purchase_number' => 'PO-' . date('Ymd') . '-0002',
            'total' => 840000,
            'status' => 'completed',
            'notes' => 'Pasokan mentega dan bahan pastry croissant segar.',
            'purchased_at' => Carbon::now()->subDays(1),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $products['PST-001']->id,
            'quantity' => 20,
            'unit_cost' => 12000,
            'subtotal' => 240000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $products['BKR-001']->id,
            'quantity' => 20,
            'unit_cost' => 15000,
            'subtotal' => 300000,
        ]);

        // 6. Seed Historical Sales Transactions (Internal Kasir Walk-in & Online Customer Orders)
        
        // A. Walk-in POS Sale by Staff (Completed)
        $salePos1 = Sale::create([
            'user_id' => $staff->id,
            'staff_id' => $staff->id,
            'sale_number' => 'SENJA-' . date('Ymd') . '-0001',
            'customer_name' => 'Rangga (Walk-in)',
            'customer_phone' => '0812-9988-7711',
            'order_type' => 'pos',
            'payment_method' => 'cash',
            'status' => 'completed',
            'notes' => 'Dine in meja 4, less sugar pada latte.',
            'total' => 97000,
            'sold_at' => Carbon::now()->subHours(5),
        ]);

        SaleItem::create([
            'sale_id' => $salePos1->id,
            'product_id' => $products['ESP-003']->id, // Cafe Latte
            'quantity' => 2,
            'unit_price' => 32000,
            'subtotal' => 64000,
        ]);

        SaleItem::create([
            'sale_id' => $salePos1->id,
            'product_id' => $products['BKR-002']->id, // Fudgy Brownies
            'quantity' => 1,
            'unit_price' => 32000,
            'subtotal' => 32000,
        ]);

        // B. Online Customer Order - Preparing (Sedang Disiapkan)
        $saleOnline1 = Sale::create([
            'user_id' => $customer->id,
            'staff_id' => $staff->id,
            'sale_number' => 'ORD-' . date('Ymd') . '-1001',
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'order_type' => 'take_away',
            'payment_method' => 'qris',
            'status' => 'preparing',
            'notes' => 'Take away, tolong sedotan kertas dan kantong goni ramah lingkungan.',
            'total' => 63000,
            'sold_at' => Carbon::now()->subMinutes(30),
        ]);

        SaleItem::create([
            'sale_id' => $saleOnline1->id,
            'product_id' => $products['SIG-002']->id, // Aren Signature
            'quantity' => 1,
            'unit_price' => 28000,
            'subtotal' => 28000,
        ]);

        SaleItem::create([
            'sale_id' => $saleOnline1->id,
            'product_id' => $products['NON-001']->id, // Matcha Latte
            'quantity' => 1,
            'unit_price' => 35000,
            'subtotal' => 35000,
        ]);

        // C. Online Customer Order - Pending (Menunggu Konfirmasi)
        $saleOnline2 = Sale::create([
            'user_id' => $customer->id,
            'staff_id' => null,
            'sale_number' => 'ORD-' . date('Ymd') . '-1002',
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'order_type' => 'dine_in',
            'payment_method' => 'transfer',
            'status' => 'pending',
            'notes' => 'Makan di tempat meja 7, ekstra cinnamon pada roll.',
            'total' => 55000,
            'sold_at' => Carbon::now()->subMinutes(10),
        ]);

        SaleItem::create([
            'sale_id' => $saleOnline2->id,
            'product_id' => $products['PST-001']->id, // Butter Croissant
            'quantity' => 1,
            'unit_price' => 25000,
            'subtotal' => 25000,
        ]);

        SaleItem::create([
            'sale_id' => $saleOnline2->id,
            'product_id' => $products['BKR-001']->id, // Cinnamon Roll
            'quantity' => 1,
            'unit_price' => 30000,
            'subtotal' => 30000,
        ]);

        // D. Completed Sale Yesterday
        $saleOld = Sale::create([
            'user_id' => $staff->id,
            'staff_id' => $staff->id,
            'sale_number' => 'SENJA-' . date('Ymd', strtotime('-1 day')) . '-0099',
            'customer_name' => 'Bintang Pratama',
            'customer_phone' => '0812-4455-6677',
            'order_type' => 'pos',
            'payment_method' => 'qris',
            'status' => 'completed',
            'notes' => 'Take away cup dingin.',
            'total' => 76000,
            'sold_at' => Carbon::now()->subDay()->setHour(14),
        ]);

        SaleItem::create([
            'sale_id' => $saleOld->id,
            'product_id' => $products['SIG-001']->id, // Caramel Macchiato
            'quantity' => 2,
            'unit_price' => 38000,
            'subtotal' => 76000,
        ]);
    }
}
