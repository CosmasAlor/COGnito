<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTestAssetCategory extends Command
{
    protected $signature = 'asset:create-test-category';
    protected $description = 'Create a test asset category for testing';

    public function handle()
    {
        $this->info('Creating test asset category...');

        // Get business and user
        $business = DB::table('business')->first();
        $user = DB::table('users')->first();

        if (!$business) {
            $this->error('No business found in database.');
            return 1;
        }

        if (!$user) {
            $this->error('No users found in database.');
            return 1;
        }

        // Check if exists
        $exists = DB::table('categories')
            ->where('name', 'Test Asset Category')
            ->where('category_type', 'asset')
            ->where('business_id', $business->id)
            ->exists();

        if ($exists) {
            $this->warn('Test category already exists!');
        } else {
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
            $this->info("✓ Created test category! ID: {$id}");
        }

        // Create additional test categories
        $categories = [
            ['name' => 'IT Equipment', 'code' => 'IT-EQ'],
            ['name' => 'Office Furniture', 'code' => 'OFF-FURN'],
            ['name' => 'Vehicles', 'code' => 'VEH'],
        ];

        $this->info('Creating additional test categories...');
        foreach ($categories as $cat) {
            $exists = DB::table('categories')
                ->where('name', $cat['name'])
                ->where('category_type', 'asset')
                ->where('business_id', $business->id)
                ->exists();

            if (!$exists) {
                $id = DB::table('categories')->insertGetId([
                    'name' => $cat['name'],
                    'business_id' => $business->id,
                    'category_type' => 'asset',
                    'parent_id' => 0,
                    'short_code' => $cat['code'],
                    'created_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("  ✓ Created: {$cat['name']} (ID: {$id})");
            } else {
                $this->line("  ℹ Already exists: {$cat['name']}");
            }
        }

        // Show all asset categories
        $this->newLine();
        $this->info('All Asset Categories:');
        $cats = DB::table('categories')
            ->where('category_type', 'asset')
            ->where('business_id', $business->id)
            ->orderBy('name')
            ->get();

        if ($cats->count() > 0) {
            $this->table(
                ['ID', 'Name', 'Short Code'],
                $cats->map(function ($cat) {
                    return [$cat->id, $cat->name, $cat->short_code ?? '-'];
                })
            );
        } else {
            $this->warn('No asset categories found.');
        }

        return 0;
    }
}

