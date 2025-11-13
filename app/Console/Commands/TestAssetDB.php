<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestAssetDB extends Command
{
    protected $signature = 'test:asset-db';
    protected $description = 'Test AssetManagement database connection and tables';

    public function handle()
    {
        $this->info('=== AssetManagement Database Connection Test ===');
        $this->newLine();

        // Test 1: Database connection
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful!');
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Test 2: Check tables
        $tables = [
            'assets' => 'Assets',
            'asset_transactions' => 'Asset Transactions',
            'asset_maintenances' => 'Asset Maintenances',
            'asset_warranties' => 'Asset Warranties'
        ];

        foreach ($tables as $table => $name) {
            try {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->info("✓ Table '{$table}' exists ({$count} records)");
                } else {
                    $this->warn("✗ Table '{$table}' does NOT exist");
                }
            } catch (\Exception $e) {
                $this->error("✗ Error checking '{$table}': " . $e->getMessage());
            }
        }

        $this->newLine();

        // Test 3: Asset Model
        try {
            $asset = \Modules\AssetManagement\Entities\Asset::first();
            if ($asset) {
                $this->info("✓ Asset Model works - Sample: {$asset->name} (Code: {$asset->asset_code})");
            } else {
                $this->info('✓ Asset Model works - No assets in database yet');
            }
        } catch (\Exception $e) {
            $this->error('✗ Asset Model error: ' . $e->getMessage());
        }

        $this->newLine();

        // Test 4: Check table columns
        if (Schema::hasTable('assets')) {
            $this->info('Assets table columns:');
            $columns = Schema::getColumnListing('assets');
            foreach ($columns as $column) {
                $this->line("  - {$column}");
            }
        }

        $this->newLine();
        $this->info('=== Test Complete ===');

        return 0;
    }
}

