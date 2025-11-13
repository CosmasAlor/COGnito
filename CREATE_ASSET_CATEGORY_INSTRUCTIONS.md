# Create Test Asset Category - Instructions

## Summary

I've created several scripts to help you create a test asset category. Due to PowerShell output issues, here are multiple ways to create the category:

## Method 1: Run the PHP Script

I've created `create_asset_category.php` which will:
- Find your business and user
- Create "Test Asset Category" if it doesn't exist
- Show all existing asset categories

**Run it:**
```bash
php create_asset_category.php
```

## Method 2: Use Artisan Command

I've created an artisan command:

```bash
php artisan asset:create-test-category
```

This will create:
- Test Asset Category
- IT Equipment
- Office Furniture  
- Vehicles

## Method 3: Direct SQL (Recommended if scripts don't work)

Run this SQL directly in your database (phpMyAdmin, MySQL Workbench, etc.):

```sql
-- First, get your business_id and user_id
SELECT id FROM business LIMIT 1;
SELECT id FROM users LIMIT 1;

-- Then create the test category (replace BUSINESS_ID and USER_ID with actual values)
INSERT INTO categories (name, business_id, category_type, parent_id, short_code, created_by, created_at, updated_at)
VALUES ('Test Asset Category', BUSINESS_ID, 'asset', 0, 'TEST-ASSET', USER_ID, NOW(), NOW());

-- Create additional test categories
INSERT INTO categories (name, business_id, category_type, parent_id, short_code, created_by, created_at, updated_at)
VALUES 
    ('IT Equipment', BUSINESS_ID, 'asset', 0, 'IT-EQ', USER_ID, NOW(), NOW()),
    ('Office Furniture', BUSINESS_ID, 'asset', 0, 'OFF-FURN', USER_ID, NOW(), NOW()),
    ('Vehicles', BUSINESS_ID, 'asset', 0, 'VEH', USER_ID, NOW(), NOW());
```

## Method 4: Using Laravel Tinker

```bash
php artisan tinker
```

Then in tinker:
```php
$business = DB::table('business')->first();
$user = DB::table('users')->first();

DB::table('categories')->insert([
    'name' => 'Test Asset Category',
    'business_id' => $business->id,
    'category_type' => 'asset',
    'parent_id' => 0,
    'short_code' => 'TEST-ASSET',
    'created_by' => $user->id,
    'created_at' => now(),
    'updated_at' => now(),
]);
```

## Verify Category Was Created

Run the check script:
```bash
php check_asset_categories.php
```

Or check directly in database:
```sql
SELECT * FROM categories WHERE category_type = 'asset';
```

## Expected Result

After creating the category, you should see:
- Category ID
- Name: "Test Asset Category"
- Type: "asset"
- It should appear in the asset creation form dropdown

## Files Created

1. `create_asset_category.php` - Simple PHP script
2. `create_test_asset_category.php` - Comprehensive script
3. `app/Console/Commands/CreateTestAssetCategory.php` - Artisan command

## Next Steps

Once the category is created:
1. Go to Asset Management module
2. Try creating a new asset
3. The "Test Asset Category" should appear in the category dropdown
4. Assign the category to test assets

