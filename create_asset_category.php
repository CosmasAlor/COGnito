<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

print "=== Creating Test Asset Category ===\n\n";

try {
    $business = DB::table('business')->first();
    if (!$business) {
        print "ERROR: No business found\n";
        exit(1);
    }
    print "Business ID: {$business->id}\n";
    
    $user = DB::table('users')->first();
    if (!$user) {
        print "ERROR: No users found\n";
        exit(1);
    }
    print "User ID: {$user->id}\n\n";
    
    // Check if exists
    $exists = DB::table('categories')
        ->where('name', 'Test Asset Category')
        ->where('category_type', 'asset')
        ->where('business_id', $business->id)
        ->first();
    
    if ($exists) {
        print "Category already exists!\n";
        print "ID: {$exists->id}\n";
        print "Name: {$exists->name}\n\n";
    } else {
        $id = DB::table('categories')->insertGetId([
            'name' => 'Test Asset Category',
            'business_id' => $business->id,
            'category_type' => 'asset',
            'parent_id' => 0,
            'short_code' => 'TEST-ASSET',
            'created_by' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        print "SUCCESS: Created category!\n";
        print "Category ID: {$id}\n";
        print "Name: Test Asset Category\n\n";
    }
    
    // Show all asset categories
    $cats = DB::table('categories')
        ->where('category_type', 'asset')
        ->where('business_id', $business->id)
        ->orderBy('name')
        ->get();
    
    print "Total asset categories: " . $cats->count() . "\n\n";
    if ($cats->count() > 0) {
        print "Category List:\n";
        foreach ($cats as $cat) {
            print "  - {$cat->name} (ID: {$cat->id}, Code: " . ($cat->short_code ?? 'N/A') . ")\n";
        }
    }
    
    print "\n=== Done ===\n";
    
} catch (Exception $e) {
    print "ERROR: " . $e->getMessage() . "\n";
    print "File: " . $e->getFile() . "\n";
    print "Line: " . $e->getLine() . "\n";
}

