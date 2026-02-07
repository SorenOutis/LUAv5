<?php
// Quick script to assign super_admin role

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = \Illuminate\Http\Request::capture());

$user = \App\Models\User::first();
if ($user) {
    $user->assignRole('super_admin');
    echo "✓ Assigned super_admin to: " . $user->email . PHP_EOL;
} else {
    echo "✗ No users found in database" . PHP_EOL;
}
