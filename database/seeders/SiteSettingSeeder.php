<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\FiscalPeriod;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            ['key' => 'company_name', 'value' => 'Mari Partner', 'type' => 'text', 'group' => 'company', 'description' => 'Nama perusahaan'],
            ['key' => 'company_tagline', 'value' => 'Solusi Manajemen Gudang Terpercaya', 'type' => 'text', 'group' => 'company', 'description' => 'Tagline perusahaan'],
            ['key' => 'company_address', 'value' => 'Jl. Industri No. 1, Jakarta', 'type' => 'textarea', 'group' => 'company', 'description' => 'Alamat perusahaan'],
            ['key' => 'company_phone', 'value' => '021-1234567', 'type' => 'text', 'group' => 'company', 'description' => 'Nomor telepon'],
            ['key' => 'company_email', 'value' => 'info@maripartner.com', 'type' => 'text', 'group' => 'company', 'description' => 'Email perusahaan'],
            ['key' => 'company_website', 'value' => 'https://maripartner.com', 'type' => 'text', 'group' => 'company', 'description' => 'Website perusahaan'],
            ['key' => 'company_npwp', 'value' => '00.000.000.0-000.000', 'type' => 'text', 'group' => 'company', 'description' => 'NPWP perusahaan'],
            ['key' => 'company_logo', 'value' => 'images/logo.webp', 'type' => 'image', 'group' => 'company', 'description' => 'Logo perusahaan'],

            // Invoice Settings
            ['key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'text', 'group' => 'invoice', 'description' => 'Prefix nomor invoice'],
            ['key' => 'invoice_footer', 'value' => 'Terima kasih atas kepercayaan Anda.', 'type' => 'textarea', 'group' => 'invoice', 'description' => 'Footer invoice'],
            ['key' => 'tax_percentage', 'value' => '11', 'type' => 'number', 'group' => 'invoice', 'description' => 'Persentase PPN'],

            // General Settings
            ['key' => 'currency', 'value' => 'IDR', 'type' => 'text', 'group' => 'general', 'description' => 'Mata uang'],
            ['key' => 'currency_symbol', 'value' => 'Rp', 'type' => 'text', 'group' => 'general', 'description' => 'Simbol mata uang'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'text', 'group' => 'general', 'description' => 'Format tanggal'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'text', 'group' => 'general', 'description' => 'Format waktu'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'text', 'group' => 'general', 'description' => 'Zona waktu'],
            ['key' => 'low_stock_threshold', 'value' => '10', 'type' => 'number', 'group' => 'general', 'description' => 'Batas stok rendah'],

            // Work Hours
            ['key' => 'work_start_time', 'value' => '08:00', 'type' => 'text', 'group' => 'attendance', 'description' => 'Jam mulai kerja'],
            ['key' => 'work_end_time', 'value' => '17:00', 'type' => 'text', 'group' => 'attendance', 'description' => 'Jam selesai kerja'],
            ['key' => 'late_tolerance_minutes', 'value' => '15', 'type' => 'number', 'group' => 'attendance', 'description' => 'Toleransi keterlambatan (menit)'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Create default fiscal period
        FiscalPeriod::updateOrCreate(
            ['name' => '2024'],
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'is_active' => true,
                'is_closed' => false,
            ]
        );

        FiscalPeriod::updateOrCreate(
            ['name' => '2025'],
            [
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'is_active' => false,
                'is_closed' => false,
            ]
        );
    }
}
