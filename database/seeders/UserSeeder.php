<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin_akuntansi')->first();
        $warehouseRole = Role::where('name', 'warehouse')->first();
        $ekspedisiRole = Role::where('name', 'ekspedisi')->first();

        // Admin Akuntansi
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'password' => 'password',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // Warehouse Staff
        User::updateOrCreate(
            ['email' => 'warehouse@example.com'],
            [
                'role_id' => $warehouseRole->id,
                'name' => 'Staff Gudang',
                'password' => 'password',
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );

        // Ekspedisi Staff
        User::updateOrCreate(
            ['email' => 'ekspedisi@example.com'],
            [
                'role_id' => $ekspedisiRole->id,
                'name' => 'Staff Ekspedisi',
                'password' => 'password',
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
    }
}
