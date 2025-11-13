<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AssetManagement Database Connection Test ===\n\n";

try {
    // Test 1: Check database connection
    echo "1. Testing database connection...\n";
    DB::connection()->getPdo();
    echo "   ✓ Database connection successful!\n\n";
    
    // Test 2: Check if assets table exists
    echo "2. Checking if 'assets' table exists...\n";
    $tableExists = Schema::hasTable('assets');
    if ($tableExists) {
        echo "   ✓ Assets table exists!\n";
        
        $count = DB::table('assets')->count();
        echo "   ✓ Total assets in database: {$count}\n\n";
    } else {
        echo "   ✗ Assets table does NOT exist. Run migrations first.\n\n";
    }
    
    // Test 3: Check if asset_transactions table exists
    echo "3. Checking if 'asset_transactions' table exists...\n";
    $tableExists = Schema::hasTable('asset_transactions');
    if ($tableExists) {
        echo "   ✓ Asset transactions table exists!\n";
        $count = DB::table('asset_transactions')->count();
        echo "   ✓ Total transactions: {$count}\n\n";
    } else {
        echo "   ✗ Asset transactions table does NOT exist.\n\n";
    }
    
    // Test 4: Check if asset_maintenances table exists
    echo "4. Checking if 'asset_maintenances' table exists...\n";
    $tableExists = Schema::hasTable('asset_maintenances');
    if ($tableExists) {
        echo "   ✓ Asset maintenances table exists!\n";
        $count = DB::table('asset_maintenances')->count();
        echo "   ✓ Total maintenances: {$count}\n\n";
    } else {
        echo "   ✗ Asset maintenances table does NOT exist.\n\n";
    }
    
    // Test 5: Check if asset_warranties table exists
    echo "5. Checking if 'asset_warranties' table exists...\n";
    $tableExists = Schema::hasTable('asset_warranties');
    if ($tableExists) {
        echo "   ✓ Asset warranties table exists!\n";
        $count = DB::table('asset_warranties')->count();
        echo "   ✓ Total warranties: {$count}\n\n";
    } else {
        echo "   ✗ Asset warranties table does NOT exist.\n\n";
    }
    
    // Test 6: Test Asset Model
    echo "6. Testing Asset Model...\n";
    try {
        $assetModel = new \Modules\AssetManagement\Entities\Asset();
        echo "   ✓ Asset model can be instantiated!\n";
        
        // Try to get a sample asset
        $sampleAsset = \Modules\AssetManagement\Entities\Asset::first();
        if ($sampleAsset) {
            echo "   ✓ Sample asset found: {$sampleAsset->name} (Code: {$sampleAsset->asset_code})\n\n";
        } else {
            echo "   ℹ No assets found in database.\n\n";
        }
    } catch (Exception $e) {
        echo "   ✗ Error with Asset model: " . $e->getMessage() . "\n\n";
    }
    
    // Test 7: Check table structure
    echo "7. Checking assets table structure...\n";
    $columns = Schema::getColumnListing('assets');
    if (!empty($columns)) {
        echo "   ✓ Table has " . count($columns) . " columns:\n";
        foreach ($columns as $column) {
            echo "      - {$column}\n";
        }
        echo "\n";
    } else {
        echo "   ✗ Could not retrieve table structure.\n\n";
    }
    
    echo "=== Test Complete ===\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

