<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create test category if it doesn't exist
$business = DB::table('business')->first();
$user = DB::table('users')->first();

if ($business && $user) {
    $exists = DB::table('categories')
        ->where('name', 'Test Asset Category')
        ->where('category_type', 'asset')
        ->where('business_id', $business->id)
        ->exists();
    
    if (!$exists) {
        $id = DB::table('categories')->insertGetId([
            'name' => 'Test Asset Category',
            'business_id' => $business->id,
            'category_type' => 'asset',
            'parent_id' => 0,
            'short_code' => 'TEST-ASSET',
            'created_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        file_put_contents('category_created.txt', "Created category ID: $id\n");
    } else {
        file_put_contents('category_created.txt', "Category already exists\n");
    }
    
    // Show all asset categories
    $cats = DB::table('categories')
        ->where('category_type', 'asset')
        ->where('business_id', $business->id)
        ->get();
    
    file_put_contents('category_created.txt', "Total asset categories: " . $cats->count() . "\n", FILE_APPEND);
    foreach ($cats as $cat) {
        file_put_contents('category_created.txt', "  - {$cat->name} (ID: {$cat->id})\n", FILE_APPEND);
    }
}

echo "Check category_created.txt for results\n";

