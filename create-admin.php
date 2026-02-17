<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Delete existing admin
Admin::where('email', 'admin@example.com')->delete();

// Create new admin
$admin = Admin::create([
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'super_admin',
    'is_active' => true,
]);

echo "✅ Admin created successfully!\n";
echo "Email: admin@example.com\n";
echo "Password: password\n";