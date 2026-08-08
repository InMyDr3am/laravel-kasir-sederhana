<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::putMany([
            'store_name' => 'Kasir Sederhana',
            'store_address' => 'Jl. Contoh No. 123, Kota',
            'store_phone' => '0812-3456-7890',
            'receipt_footer' => 'Terima kasih atas kunjungan Anda 🙏',
        ]);

        User::updateOrCreate(
            ['email' => 'admin@kasir.test'],
            ['name' => 'Administrator', 'password' => 'password', 'role' => 'admin'],
        );

        User::updateOrCreate(
            ['email' => 'kasir@kasir.test'],
            ['name' => 'Kasir Satu', 'password' => 'password', 'role' => 'kasir'],
        );

        $products = [
            // Minuman
            ['Kopi Hitam', 'Minuman', 8000, 50],
            ['Kopi Susu', 'Minuman', 10000, 40],
            ['Teh Manis', 'Minuman', 6000, 50],
            ['Teh Tarik', 'Minuman', 9000, 35],
            ['Air Mineral', 'Minuman', 4000, 100],
            ['Es Jeruk', 'Minuman', 8000, 30],
            ['Jus Alpukat', 'Minuman', 15000, 20],
            ['Susu Kotak', 'Minuman', 7000, 45],
            ['Air Soda', 'Minuman', 9000, 25],
            // Makanan
            ['Roti Bakar', 'Makanan', 15000, 30],
            ['Nasi Goreng', 'Makanan', 20000, 25],
            ['Mie Goreng', 'Makanan', 18000, 25],
            ['Nasi Uduk', 'Makanan', 13000, 20],
            ['Ayam Goreng', 'Makanan', 17000, 22],
            ['Bakso', 'Makanan', 18000, 20],
            ['Soto Ayam', 'Makanan', 19000, 18],
            ['Gado-Gado', 'Makanan', 16000, 15],
            ['Martabak Telur', 'Makanan', 25000, 12],
            // Snack
            ['Keripik Kentang', 'Snack', 12000, 40],
            ['Cokelat Batang', 'Snack', 10000, 40],
            ['Permen Mint', 'Snack', 3000, 4],
            ['Biskuit', 'Snack', 9000, 35],
            ['Wafer Cokelat', 'Snack', 8000, 30],
            ['Kacang Goreng', 'Snack', 11000, 28],
            ['Kerupuk', 'Snack', 5000, 60],
            ['Es Krim', 'Snack', 7000, 24],
            // Sembako
            ['Gula 1kg', 'Sembako', 16000, 3],
            ['Beras 5kg', 'Sembako', 65000, 10],
            ['Minyak Goreng 1L', 'Sembako', 18000, 8],
            ['Telur 1kg', 'Sembako', 28000, 5],
        ];

        foreach ($products as $i => [$name, $category, $price, $stock]) {
            Product::updateOrCreate(
                ['sku' => 'PRD-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                compact('name', 'category', 'price', 'stock') + ['is_active' => true],
            );
        }
    }
}
