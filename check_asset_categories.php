<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Asset Categories Database Check ===\n\n";

// Check if categories table exists
if (!Schema::hasTable('categories')) {
    echo "✗ Categories table does NOT exist\n";
    exit(1);
}

echo "✓ Categories table exists\n\n";

// First, check what category types exist
echo "Checking all category types in database...\n";
$categoryTypes = DB::table('categories')
    ->select('category_type', DB::raw('COUNT(*) as count'))
    ->groupBy('category_type')
    ->get();

if ($categoryTypes->count() > 0) {
    echo "Category Types Found:\n";
    foreach ($categoryTypes as $type) {
        $typeName = $type->category_type ?? 'NULL/Empty';
        echo "  - {$typeName}: {$type->count} categories\n";
    }
    echo "\n";
} else {
    echo "⚠ No categories found in database at all\n\n";
}

// Get all asset categories (category_type = 'asset')
try {
    $assetCategories = DB::table('categories')
        ->where('category_type', 'asset')
        ->orderBy('name')
        ->get();
    
    echo "Asset Categories Found: " . $assetCategories->count() . "\n\n";
    
    if ($assetCategories->count() > 0) {
        echo "Category List:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-5s %-30s %-15s %-20s\n", "ID", "Name", "Type", "Parent");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($assetCategories as $cat) {
            $parent = $cat->parent_id ? "ID: {$cat->parent_id}" : "None";
            printf("%-5s %-30s %-15s %-20s\n", 
                $cat->id, 
                substr($cat->name ?? 'N/A', 0, 30),
                $cat->category_type ?? 'N/A',
                $parent
            );
        }
        echo str_repeat("-", 80) . "\n\n";
    } else {
        echo "⚠ No asset categories found in database\n\n";
    }
    
    // Check which categories are being used by assets
    echo "Categories Used by Assets:\n";
    $usedCategories = DB::table('assets')
        ->join('categories', 'assets.category_id', '=', 'categories.id')
        ->select('categories.id', 'categories.name', DB::raw('COUNT(assets.id) as asset_count'))
        ->groupBy('categories.id', 'categories.name')
        ->orderBy('asset_count', 'desc')
        ->get();
    
    if ($usedCategories->count() > 0) {
        echo str_repeat("-", 60) . "\n";
        printf("%-5s %-30s %-15s\n", "ID", "Category Name", "Asset Count");
        echo str_repeat("-", 60) . "\n";
        foreach ($usedCategories as $cat) {
            printf("%-5s %-30s %-15s\n", 
                $cat->id, 
                substr($cat->name, 0, 30),
                $cat->asset_count
            );
        }
        echo str_repeat("-", 60) . "\n\n";
    } else {
        echo "⚠ No categories are currently assigned to assets\n\n";
    }
    
    // Check if assets table exists and count assets
    if (Schema::hasTable('assets')) {
        $totalAssets = DB::table('assets')->count();
        echo "Total Assets in database: {$totalAssets}\n";
        
        // Check assets without categories
        $assetsWithoutCategory = DB::table('assets')
            ->whereNull('category_id')
            ->count();
        
        if ($assetsWithoutCategory > 0) {
            echo "⚠ Assets without category: {$assetsWithoutCategory}\n\n";
        } else if ($totalAssets > 0) {
            echo "✓ All assets have categories assigned\n\n";
        } else {
            echo "ℹ No assets in database yet\n\n";
        }
    } else {
        echo "⚠ Assets table does NOT exist\n\n";
    }
    
    // Show category structure
    echo "Category Structure:\n";
    $allCategories = DB::table('categories')
        ->where('category_type', 'asset')
        ->select('id', 'name', 'parent_id', 'category_type')
        ->orderBy('parent_id')
        ->orderBy('name')
        ->get();
    
    $parentCategories = $allCategories->whereNull('parent_id');
    $childCategories = $allCategories->whereNotNull('parent_id');
    
    if ($parentCategories->count() > 0) {
        foreach ($parentCategories as $parent) {
            echo "  ├─ {$parent->name} (ID: {$parent->id})\n";
            $children = $childCategories->where('parent_id', $parent->id);
            foreach ($children as $child) {
                echo "  │  └─ {$child->name} (ID: {$child->id})\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Check Complete ===\n";

