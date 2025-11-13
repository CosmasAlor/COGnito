<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AssetManagement Database Test ===\n\n";

// Test database connection
try {
    DB::connection()->getPdo();
    echo "✓ Database connected\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check tables
$tables = ['assets', 'asset_transactions', 'asset_maintenances', 'asset_warranties'];

foreach ($tables as $table) {
    try {
        $exists = Schema::hasTable($table);
        if ($exists) {
            $count = DB::table($table)->count();
            echo "✓ Table '{$table}' exists ({$count} records)\n";
        } else {
            echo "✗ Table '{$table}' does NOT exist\n";
        }
    } catch (Exception $e) {
        echo "✗ Error checking '{$table}': " . $e->getMessage() . "\n";
    }
}

// Test Asset model
try {
    $asset = \Modules\AssetManagement\Entities\Asset::first();
    if ($asset) {
        echo "\n✓ Asset Model works - Sample: {$asset->name} ({$asset->asset_code})\n";
    } else {
        echo "\n✓ Asset Model works - No assets in database yet\n";
    }
} catch (Exception $e) {
    echo "\n✗ Asset Model error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";

