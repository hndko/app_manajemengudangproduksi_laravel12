<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ['email' => 'admin@maripartner.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // Warehouse Staff
        User::updateOrCreate(
            ['email' => 'warehouse@maripartner.com'],
            [
                'role_id' => $warehouseRole->id,
                'name' => 'Staff Gudang',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );

        // Ekspedisi Staff
        User::updateOrCreate(
            ['email' => 'ekspedisi@maripartner.com'],
            [
                'role_id' => $ekspedisiRole->id,
                'name' => 'Staff Ekspedisi',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
    }
}
