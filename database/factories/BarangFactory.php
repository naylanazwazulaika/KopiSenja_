<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();

        return [
            'kode_barang' => 'BRG' . $this->faker->unique()->numberBetween(100, 999),
            'nama_barang' => $this->faker->randomElement([
                'Laptop',
                'Printer',
                'Monitor',
                'Keyboard',
                'Mouse',
                'Proyektor',
            ]),
            'category_id' => $category?->id,
            'kategori' => $category?->name ?? 'Elektronik',
            'stok' => $this->faker->numberBetween(1, 50),
            'satuan' => $this->faker->randomElement([
                'Unit',
                'Pcs',
                'Buah',
            ]),
            'lokasi' => $this->faker->randomElement([
                'Ruang Lab',
                'Gudang',
                'Ruang Kantor',
            ]),
            'kondisi' => $this->faker->randomElement([
                'Baik',
                'Rusak Ringan',
                'Rusak Berat',
            ]),
        ];
    }
}