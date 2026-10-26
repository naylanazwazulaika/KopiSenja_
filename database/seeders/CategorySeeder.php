<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Barang;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'ATK',
            'Furniture',
            'Perlengkapan Kantor',
            'Peralatan',
        ];

        foreach ($categories as $name) {
            $category = Category::firstOrCreate([
                'name' => $name,
            ]);

            // Jika ada kategori lama dengan nama yang sama,
            // pindahkan barangnya ke kategori yang benar.
            Category::where('name', $name)
                ->where('id', '!=', $category->id)
                ->get()
                ->each(function ($duplicate) use ($category) {
                    Barang::where('category_id', $duplicate->id)
                        ->update([
                            'category_id' => $category->id,
                            'kategori' => $category->name,
                        ]);

                    $duplicate->delete();
                });
        }
    }
}