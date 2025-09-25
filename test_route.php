<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Division;

try {
    // Test finding division by ID
    $division = Division::find(1);
    
    if ($division) {
        echo "Division found: " . $division->name . PHP_EOL;
        echo "Is active: " . ($division->is_active ? 'Yes' : 'No') . PHP_EOL;
        echo "Route: /divisi/{$division->id}" . PHP_EOL;
    } else {
        echo "Division with ID 1 not found" . PHP_EOL;
    }
    
    // Test route generation
    $url = route('public.division.detail', ['division' => 1]);
    echo "Generated URL: " . $url . PHP_EOL;
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}