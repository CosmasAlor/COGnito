<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Creating Test Asset Category ===\n\n";

try {
    // Get the first business ID
    $business = DB::table('business')->first();
    if (!$business) {
        echo "✗ No business found in database. Please create a business first.\n";
        exit(1);
    }
    
    $businessId = $business->id;
    echo "Using Business ID: {$businessId}\n";
    
    // Get the first user ID (for created_by)
    $user = DB::table('users')->first();
    if (!$user) {
        echo "✗ No users found in database.\n";
        exit(1);
    }
    
    $userId = $user->id;
    echo "Using User ID: {$userId}\n\n";
    
    // Check if test category already exists
    $existing = DB::table('categories')
        ->where('name', 'Test Asset Category')
        ->where('category_type', 'asset')
        ->where('business_id', $businessId)
        ->first();
    
    if ($existing) {
        echo "⚠ Test category already exists (ID: {$existing->id})\n";
        echo "Category: {$existing->name}\n";
        exit(0);
    }
    
    // Create test asset category
    $categoryId = DB::table('categories')->insertGetId([
        'name' => 'Test Asset Category',
        'business_id' => $businessId,
        'category_type' => 'asset',
        'parent_id' => 0,
        'short_code' => 'TEST-ASSET',
        'created_by' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✓ Test asset category created successfully!\n";
    echo "  Category ID: {$categoryId}\n";
    echo "  Name: Test Asset Category\n";
    echo "  Type: asset\n";
    echo "  Business ID: {$businessId}\n\n";
    
    // Create a few more test categories
    $testCategories = [
        ['name' => 'IT Equipment', 'short_code' => 'IT-EQ'],
        ['name' => 'Office Furniture', 'short_code' => 'OFF-FURN'],
        ['name' => 'Vehicles', 'short_code' => 'VEH'],
    ];
    
    echo "Creating additional test categories...\n";
    foreach ($testCategories as $cat) {
        $exists = DB::table('categories')
            ->where('name', $cat['name'])
            ->where('category_type', 'asset')
            ->where('business_id', $businessId)
            ->exists();
        
        if (!$exists) {
            $id = DB::table('categories')->insertGetId([
                'name' => $cat['name'],
                'business_id' => $businessId,
                'category_type' => 'asset',
                'parent_id' => 0,
                'short_code' => $cat['short_code'],
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "  ✓ Created: {$cat['name']} (ID: {$id})\n";
        } else {
            echo "  ℹ Already exists: {$cat['name']}\n";
        }
    }
    
    echo "\n=== Verification ===\n";
    $assetCategories = DB::table('categories')
        ->where('category_type', 'asset')
        ->where('business_id', $businessId)
        ->orderBy('name')
        ->get();
    
    echo "Total asset categories: " . $assetCategories->count() . "\n\n";
    echo "Category List:\n";
    foreach ($assetCategories as $cat) {
        echo "  - {$cat->name} (ID: {$cat->id}, Code: {$cat->short_code})\n";
    }
    
    echo "\n✓ Test categories created successfully!\n";
    echo "You can now use these categories when creating assets.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

