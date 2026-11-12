<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $this->call(CategorySeeder::class);

        // 2. Seed a default demo student user for instant preview/testing
        $demoUser = User::firstOrCreate(
            ['email' => 'mahasiswa@moneytrack.test'],
            [
                'name' => 'Ahmad Dzakwan (Mahasiswa)',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Seed realistic sample transactions for demo user if empty
        if ($demoUser->transactions()->count() === 0) {
            $categories = Category::whereNull('user_id')->get()->keyBy(function ($cat) {
                return $cat->type . ':' . $cat->name;
            });

            $today = Carbon::today();

            $samples = [
                // Income
                [
                    'type' => 'income',
                    'cat' => 'Uang saku',
                    'title' => 'Kiriman Bulanan Orang Tua',
                    'amount' => 1500000,
                    'desc' => 'Uang saku bulanan dari orang tua untuk kos dan makan',
                    'date' => $today->copy()->startOfMonth()->addDays(1)->format('Y-m-d'),
                ],
                [
                    'type' => 'income',
                    'cat' => 'Beasiswa',
                    'title' => 'Pencairan Beasiswa Prestasi',
                    'amount' => 2000000,
                    'desc' => 'Beasiswa semester ganjil kampus',
                    'date' => $today->copy()->startOfMonth()->addDays(3)->format('Y-m-d'),
                ],
                [
                    'type' => 'income',
                    'cat' => 'Penghasilan tambahan',
                    'title' => 'Honor Freelance Desain Poster',
                    'amount' => 350000,
                    'desc' => 'Desain poster kegiatan himpunan mahasiswa',
                    'date' => $today->copy()->subDays(6)->format('Y-m-d'),
                ],
                // Expense
                [
                    'type' => 'expense',
                    'cat' => 'Pendidikan',
                    'title' => 'Buku Referensi Kuliah & Fotokopi Modul',
                    'amount' => 220000,
                    'desc' => 'Buku algoritma & modul praktikum laboratorium',
                    'date' => $today->copy()->startOfMonth()->addDays(4)->format('Y-m-d'),
                ],
                [
                    'type' => 'expense',
                    'cat' => 'Makanan',
                    'title' => 'Makan Siang & Malam Mingguan',
                    'amount' => 185000,
                    'desc' => 'Makan di kantin kampus dan warteg sekitar kos',
                    'date' => $today->copy()->subDays(4)->format('Y-m-d'),
                ],
                [
                    'type' => 'expense',
                    'cat' => 'Transportasi',
                    'title' => 'Bensin Motor Mingguan & Kereta',
                    'amount' => 75000,
                    'desc' => 'Isi bensin motor dan top up kartu KRL',
                    'date' => $today->copy()->subDays(3)->format('Y-m-d'),
                ],
                [
                    'type' => 'expense',
                    'cat' => 'Hiburan',
                    'title' => 'Langganan Spotify & Nonton Bioskop',
                    'amount' => 95000,
                    'desc' => 'Hiburan akhir pekan setelah tugas UTS',
                    'date' => $today->copy()->subDays(2)->format('Y-m-d'),
                ],
                [
                    'type' => 'expense',
                    'cat' => 'Kebutuhan pribadi',
                    'title' => 'Sabun, Sampo, & Kuota Internet',
                    'amount' => 130000,
                    'desc' => 'Paket data 50GB untuk kuliah daring dan perlengkapan mandi',
                    'date' => $today->copy()->subDays(1)->format('Y-m-d'),
                ],
            ];

            foreach ($samples as $item) {
                $category = $categories[$item['type'] . ':' . $item['cat']] ?? null;
                if ($category) {
                    Transaction::create([
                        'user_id' => $demoUser->id,
                        'category_id' => $category->id,
                        'title' => $item['title'],
                        'amount' => $item['amount'],
                        'description' => $item['desc'],
                        'transaction_date' => $item['date'],
                    ]);
                }
            }
        }
    }
}
