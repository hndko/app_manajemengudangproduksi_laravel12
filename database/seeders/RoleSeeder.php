<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin_akuntansi',
                'display_name' => 'Admin Akuntansi',
                'description' => 'Administrator dengan akses penuh termasuk modul akuntansi dan keuangan',
                'permissions' => [
                    'dashboard.view',
                    'employees.manage',
                    'attendance.manage',
                    'activity_log.view',
                    'accounting.manage',
                    'master_data.manage',
                    'warehouse.manage',
                    'manufacturing.manage',
                    'transactions.manage',
                    'reports.view',
                    'settings.manage',
                    'users.manage',
                    'backup.manage',
                ],
            ],
            [
                'name' => 'warehouse',
                'display_name' => 'Warehouse',
                'description' => 'Staff gudang dengan akses ke material, stok, dan produksi',
                'permissions' => [
                    'dashboard.view',
                    'attendance.own',
                    'master_data.view',
                    'warehouse.manage',
                    'manufacturing.manage',
                    'master_data.consumers.view',
                ],
            ],
            [
                'name' => 'ekspedisi',
                'display_name' => 'Ekspedisi',
                'description' => 'Staff ekspedisi dengan akses ke surat jalan dan pengiriman',
                'permissions' => [
                    'dashboard.view',
                    'attendance.own',
                    'delivery_notes.manage',
                    'returns.manage',
                    'master_data.consumers.view',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
