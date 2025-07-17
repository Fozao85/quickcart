<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing database connection...\n";
    
    // Test basic connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test products count
    $count = App\Models\Product::count();
    echo "✅ Products in database: $count\n";
    
    // Test first few products
    $products = App\Models\Product::select('id', 'name', 'price')->limit(3)->get();
    echo "✅ Sample products:\n";
    foreach ($products as $product) {
        echo "  - {$product->name} (\${$product->price})\n";
    }
    
    echo "\n🎉 Database is working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "Database path: " . config('database.connections.sqlite.database') . "\n";
    echo "File exists: " . (file_exists(config('database.connections.sqlite.database')) ? 'Yes' : 'No') . "\n";
}
