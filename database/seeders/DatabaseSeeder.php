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
            ['Kopi Hitam', 'Minuman', 8000, 50],
            ['Teh Manis', 'Minuman', 6000, 50],
            ['Air Mineral', 'Minuman', 4000, 100],
            ['Roti Bakar', 'Makanan', 15000, 30],
            ['Nasi Goreng', 'Makanan', 20000, 25],
            ['Mie Goreng', 'Makanan', 18000, 25],
            ['Keripik Kentang', 'Snack', 12000, 40],
            ['Cokelat Batang', 'Snack', 10000, 40],
            ['Permen Mint', 'Snack', 3000, 4],
            ['Gula 1kg', 'Sembako', 16000, 3],
        ];

        foreach ($products as $i => [$name, $category, $price, $stock]) {
            Product::updateOrCreate(
                ['sku' => 'PRD-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                compact('name', 'category', 'price', 'stock') + ['is_active' => true],
            );
        }
    }
}
