<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "Checking applications without users...\n";

// Get applications that don't have a user
$apps = App\Models\Application::whereDoesntHave('user')->get();
echo "Found: " . $apps->count() . " applications without users\n";

foreach($apps as $app) {
    echo "App ID: " . $app->id . ", User ID: " . $app->user_id . "\n";
}

// Also check all applications with their user status
echo "\nChecking all applications:\n";
$allApps = App\Models\Application::with('user')->take(5)->get();

foreach($allApps as $app) {
    $userStatus = $app->user ? "User exists: " . $app->user->name : "No user found";
    echo "App ID: " . $app->id . ", User ID: " . $app->user_id . " - " . $userStatus . "\n";
}
