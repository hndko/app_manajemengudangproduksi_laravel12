<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // ASET
            ['code' => '1-0000', 'name' => 'ASET', 'type' => 'aset', 'normal_balance' => 'debit', 'is_locked' => true],
            ['code' => '1-1000', 'name' => 'Aset Lancar', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-0000'],
            ['code' => '1-1100', 'name' => 'Kas dan Bank', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1101', 'name' => 'Kas', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],
            ['code' => '1-1102', 'name' => 'Bank BCA', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],
            ['code' => '1-1103', 'name' => 'Bank Mandiri', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],
            ['code' => '1-1200', 'name' => 'Piutang', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1201', 'name' => 'Piutang Usaha', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1200'],
            ['code' => '1-1300', 'name' => 'Persediaan', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1301', 'name' => 'Persediaan Bahan Baku', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1300'],
            ['code' => '1-1302', 'name' => 'Persediaan Barang Jadi', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1300'],
            ['code' => '1-1303', 'name' => 'Persediaan Barang Dalam Proses', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-1300'],
            ['code' => '1-2000', 'name' => 'Aset Tetap', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-0000'],
            ['code' => '1-2100', 'name' => 'Tanah', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-2000'],
            ['code' => '1-2200', 'name' => 'Bangunan', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-2000'],
            ['code' => '1-2201', 'name' => 'Akumulasi Penyusutan Bangunan', 'type' => 'aset', 'normal_balance' => 'kredit', 'parent_code' => '1-2000'],
            ['code' => '1-2300', 'name' => 'Mesin dan Peralatan', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-2000'],
            ['code' => '1-2301', 'name' => 'Akumulasi Penyusutan Mesin', 'type' => 'aset', 'normal_balance' => 'kredit', 'parent_code' => '1-2000'],
            ['code' => '1-2400', 'name' => 'Kendaraan', 'type' => 'aset', 'normal_balance' => 'debit', 'parent_code' => '1-2000'],
            ['code' => '1-2401', 'name' => 'Akumulasi Penyusutan Kendaraan', 'type' => 'aset', 'normal_balance' => 'kredit', 'parent_code' => '1-2000'],

            // LIABILITAS
            ['code' => '2-0000', 'name' => 'LIABILITAS', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'is_locked' => true],
            ['code' => '2-1000', 'name' => 'Liabilitas Jangka Pendek', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-0000'],
            ['code' => '2-1100', 'name' => 'Hutang Usaha', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-1000'],
            ['code' => '2-1200', 'name' => 'Hutang Pajak', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-1000'],
            ['code' => '2-1201', 'name' => 'Hutang PPh 21', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-1200'],
            ['code' => '2-1202', 'name' => 'Hutang PPN', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-1200'],
            ['code' => '2-1300', 'name' => 'Hutang Gaji', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-1000'],
            ['code' => '2-2000', 'name' => 'Liabilitas Jangka Panjang', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-0000'],
            ['code' => '2-2100', 'name' => 'Hutang Bank', 'type' => 'liabilitas', 'normal_balance' => 'kredit', 'parent_code' => '2-2000'],

            // EKUITAS
            ['code' => '3-0000', 'name' => 'EKUITAS', 'type' => 'ekuitas', 'normal_balance' => 'kredit', 'is_locked' => true],
            ['code' => '3-1000', 'name' => 'Modal', 'type' => 'ekuitas', 'normal_balance' => 'kredit', 'parent_code' => '3-0000'],
            ['code' => '3-1100', 'name' => 'Modal Disetor', 'type' => 'ekuitas', 'normal_balance' => 'kredit', 'parent_code' => '3-1000'],
            ['code' => '3-2000', 'name' => 'Laba Ditahan', 'type' => 'ekuitas', 'normal_balance' => 'kredit', 'parent_code' => '3-0000'],
            ['code' => '3-3000', 'name' => 'Laba Tahun Berjalan', 'type' => 'ekuitas', 'normal_balance' => 'kredit', 'parent_code' => '3-0000'],

            // PENDAPATAN
            ['code' => '4-0000', 'name' => 'PENDAPATAN', 'type' => 'pendapatan', 'normal_balance' => 'kredit', 'is_locked' => true],
            ['code' => '4-1000', 'name' => 'Pendapatan Usaha', 'type' => 'pendapatan', 'normal_balance' => 'kredit', 'parent_code' => '4-0000'],
            ['code' => '4-1100', 'name' => 'Penjualan', 'type' => 'pendapatan', 'normal_balance' => 'kredit', 'parent_code' => '4-1000'],
            ['code' => '4-1101', 'name' => 'Diskon Penjualan', 'type' => 'pendapatan', 'normal_balance' => 'debit', 'parent_code' => '4-1000'],
            ['code' => '4-1102', 'name' => 'Retur Penjualan', 'type' => 'pendapatan', 'normal_balance' => 'debit', 'parent_code' => '4-1000'],
            ['code' => '4-2000', 'name' => 'Pendapatan Lain-lain', 'type' => 'pendapatan', 'normal_balance' => 'kredit', 'parent_code' => '4-0000'],

            // BEBAN
            ['code' => '5-0000', 'name' => 'BEBAN', 'type' => 'beban', 'normal_balance' => 'debit', 'is_locked' => true],
            ['code' => '5-1000', 'name' => 'Harga Pokok Penjualan', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-0000'],
            ['code' => '5-1100', 'name' => 'HPP - Bahan Baku', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['code' => '5-1200', 'name' => 'HPP - Tenaga Kerja Langsung', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['code' => '5-1300', 'name' => 'HPP - Overhead', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['code' => '5-2000', 'name' => 'Beban Operasional', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-0000'],
            ['code' => '5-2100', 'name' => 'Beban Gaji', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2200', 'name' => 'Beban Listrik', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2300', 'name' => 'Beban Telepon', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2400', 'name' => 'Beban Air', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2500', 'name' => 'Beban Transportasi', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2600', 'name' => 'Beban Perlengkapan Kantor', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2700', 'name' => 'Beban Penyusutan', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2800', 'name' => 'Beban Pemeliharaan', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2900', 'name' => 'Beban Lain-lain', 'type' => 'beban', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
        ];

        // Create parent accounts first (without parent_id)
        foreach ($accounts as $account) {
            if (!isset($account['parent_code'])) {
                ChartOfAccount::updateOrCreate(
                    ['code' => $account['code']],
                    [
                        'name' => $account['name'],
                        'type' => $account['type'],
                        'normal_balance' => $account['normal_balance'],
                        'is_locked' => $account['is_locked'] ?? false,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Create child accounts
        foreach ($accounts as $account) {
            if (isset($account['parent_code'])) {
                $parent = ChartOfAccount::where('code', $account['parent_code'])->first();

                ChartOfAccount::updateOrCreate(
                    ['code' => $account['code']],
                    [
                        'name' => $account['name'],
                        'type' => $account['type'],
                        'normal_balance' => $account['normal_balance'],
                        'parent_id' => $parent?->id,
                        'is_locked' => $account['is_locked'] ?? false,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
