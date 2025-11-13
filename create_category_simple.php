<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating test asset category...\n";

$business = DB::table('business')->first();
$user = DB::table('users')->first();

if (!$business || !$user) {
    echo "Error: Business or User not found\n";
    exit(1);
}

// Check if exists
$exists = DB::table('categories')
    ->where('name', 'Test Asset Category')
    ->where('category_type', 'asset')
    ->exists();

if ($exists) {
    echo "Category already exists!\n";
} else {
    $id = DB::table('categories')->insertGetId([
        'name' => 'Test Asset Category',
        'business_id' => $business->id,
        'category_type' => 'asset',
        'parent_id' => 0,
        'short_code' => 'TEST',
        'created_by' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Created! ID: $id\n";
}

// Show all asset categories
$cats = DB::table('categories')
    ->where('category_type', 'asset')
    ->get();

echo "\nAsset categories: " . $cats->count() . "\n";
foreach ($cats as $cat) {
    echo "  - {$cat->name} (ID: {$cat->id})\n";
}

