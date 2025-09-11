<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Applications count: " . App\Models\Application::count() . PHP_EOL;

$statusCounts = App\Models\Application::groupBy('status')
    ->selectRaw('status, count(*) as count')
    ->get();

echo "Status distribution:" . PHP_EOL;
foreach ($statusCounts as $status) {
    echo "- {$status->status}: {$status->count}" . PHP_EOL;
}

// Check divisions
echo "\nDivisions count: " . App\Models\Division::count() . PHP_EOL;
echo "Active divisions: " . App\Models\Division::where('is_active', true)->count() . PHP_EOL;
