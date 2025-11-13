<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing AssetManagement Database...\n\n";

// Test connection
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connected\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check tables
$tables = ['assets', 'asset_transactions', 'asset_maintenances', 'asset_warranties'];
foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    if ($exists) {
        $count = DB::table($table)->count();
        echo "✓ {$table}: {$count} records\n";
    } else {
        echo "✗ {$table}: NOT FOUND\n";
    }
}

// Test model
try {
    $asset = \Modules\AssetManagement\Entities\Asset::first();
    if ($asset) {
        echo "\n✓ Asset Model OK - Sample: {$asset->name}\n";
    } else {
        echo "\n✓ Asset Model OK - No data yet\n";
    }
} catch (Exception $e) {
    echo "\n✗ Model Error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";

