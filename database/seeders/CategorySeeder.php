<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incomes = [
            'Uang saku',
            'Beasiswa',
            'Penghasilan tambahan',
            'Lainnya',
        ];

        $expenses = [
            'Makanan',
            'Transportasi',
            'Pendidikan',
            'Hiburan',
            'Kebutuhan pribadi',
            'Lainnya',
        ];

        foreach ($incomes as $name) {
            Category::firstOrCreate([
                'user_id' => null,
                'name' => $name,
                'type' => 'income',
            ]);
        }

        foreach ($expenses as $name) {
            Category::firstOrCreate([
                'user_id' => null,
                'name' => $name,
                'type' => 'expense',
            ]);
        }
    }
}
