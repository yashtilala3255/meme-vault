<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing admins
        Admin::truncate();

        // Create super admin with PIN
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@memevault.com',
            'password' => Hash::make('password'), // Backup password
            'pin_code' => '323231', // ✅ Your PIN code
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create editor (optional)
        Admin::create([
            'name' => 'Editor User',
            'email' => 'editor@memevault.com',
            'password' => Hash::make('password'),
            'pin_code' => '323232', // Different PIN
            'role' => 'editor',
            'is_active' => true,
        ]);
    }
}