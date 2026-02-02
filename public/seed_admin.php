<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

header('Content-Type: text/plain');

echo "=== Admin User Seeding ===\n";

try {
    // Check if admin user already exists
    $email = 'admin@example.com';
    $existingAdmin = User::where('email', $email)->first();
    
    if (!$existingAdmin) {
        User::create([
            'fullName' => 'Admin User',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phoneNo' => '1234567890',
        ]);
        echo "SUCCESS: Admin user created!\n";
        echo "Email: admin@example.com\n";
        echo "Password: password\n";
    } else {
        echo "INFO: Admin user already exists.\n";
    }
} catch (\Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

echo "\n=== End of Seeding ===\n";
