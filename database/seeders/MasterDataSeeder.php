<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\PriceType;
use App\Models\Warehouse;
use App\Models\InstallmentType;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories
        $categories = [
            ['code' => 'CAT-MAT-001', 'name' => 'Bahan Baku Utama', 'type' => 'material'],
            ['code' => 'CAT-MAT-002', 'name' => 'Bahan Baku Pendukung', 'type' => 'material'],
            ['code' => 'CAT-MAT-003', 'name' => 'Bahan Kemasan', 'type' => 'material'],
            ['code' => 'CAT-PRD-001', 'name' => 'Produk Jadi', 'type' => 'produk'],
            ['code' => 'CAT-PRD-002', 'name' => 'Produk Setengah Jadi', 'type' => 'produk'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['code' => $category['code']],
                array_merge($category, ['is_active' => true])
            );
        }

        // Units
        $units = [
            ['code' => 'PCS', 'name' => 'Pieces', 'description' => 'Satuan buah/unit'],
            ['code' => 'KG', 'name' => 'Kilogram', 'description' => 'Satuan berat kilogram'],
            ['code' => 'GR', 'name' => 'Gram', 'description' => 'Satuan berat gram'],
            ['code' => 'M', 'name' => 'Meter', 'description' => 'Satuan panjang meter'],
            ['code' => 'CM', 'name' => 'Centimeter', 'description' => 'Satuan panjang centimeter'],
            ['code' => 'L', 'name' => 'Liter', 'description' => 'Satuan volume liter'],
            ['code' => 'ML', 'name' => 'Mililiter', 'description' => 'Satuan volume mililiter'],
            ['code' => 'BOX', 'name' => 'Box', 'description' => 'Satuan kotak/dus'],
            ['code' => 'PAK', 'name' => 'Pak', 'description' => 'Satuan pak/kemasan'],
            ['code' => 'SET', 'name' => 'Set', 'description' => 'Satuan set/paket'],
            ['code' => 'ROLL', 'name' => 'Roll', 'description' => 'Satuan gulungan'],
            ['code' => 'LBR', 'name' => 'Lembar', 'description' => 'Satuan lembar'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['code' => $unit['code']],
                array_merge($unit, ['is_active' => true])
            );
        }

        // Price Types
        $priceTypes = [
            ['name' => 'Harga Retail', 'discount_percentage' => 0, 'description' => 'Harga untuk pembeli satuan'],
            ['name' => 'Harga Grosir', 'discount_percentage' => 10, 'description' => 'Harga untuk pembelian dalam jumlah besar'],
            ['name' => 'Harga Distributor', 'discount_percentage' => 15, 'description' => 'Harga khusus untuk distributor'],
            ['name' => 'Harga Reseller', 'discount_percentage' => 20, 'description' => 'Harga khusus untuk reseller'],
        ];

        foreach ($priceTypes as $priceType) {
            PriceType::updateOrCreate(
                ['name' => $priceType['name']],
                array_merge($priceType, ['is_active' => true])
            );
        }

        // Warehouses
        $warehouses = [
            [
                'code' => 'WH-001',
                'name' => 'Gudang Utama',
                'address' => 'Jl. Industri No. 1',
                'phone' => '021-1234567',
                'person_in_charge' => 'Budi Santoso',
                'is_default' => true,
            ],
            [
                'code' => 'WH-002',
                'name' => 'Gudang Bahan Baku',
                'address' => 'Jl. Industri No. 2',
                'phone' => '021-1234568',
                'person_in_charge' => 'Andi Wijaya',
                'is_default' => false,
            ],
            [
                'code' => 'WH-003',
                'name' => 'Gudang Produk Jadi',
                'address' => 'Jl. Industri No. 3',
                'phone' => '021-1234569',
                'person_in_charge' => 'Siti Rahayu',
                'is_default' => false,
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['code' => $warehouse['code']],
                array_merge($warehouse, ['is_active' => true])
            );
        }

        // Installment Types
        $installmentTypes = [
            ['name' => 'Tunai', 'tenor' => 0, 'interest_rate' => 0, 'description' => 'Pembayaran tunai'],
            ['name' => 'Cicilan 3 Bulan', 'tenor' => 3, 'interest_rate' => 0, 'description' => 'Cicilan 3x tanpa bunga'],
            ['name' => 'Cicilan 6 Bulan', 'tenor' => 6, 'interest_rate' => 1.5, 'description' => 'Cicilan 6x dengan bunga 1.5%'],
            ['name' => 'Cicilan 12 Bulan', 'tenor' => 12, 'interest_rate' => 2.5, 'description' => 'Cicilan 12x dengan bunga 2.5%'],
        ];

        foreach ($installmentTypes as $installmentType) {
            InstallmentType::updateOrCreate(
                ['name' => $installmentType['name']],
                array_merge($installmentType, ['is_active' => true])
            );
        }
    }
}
