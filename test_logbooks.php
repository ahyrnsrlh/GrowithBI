<?php

require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Application;

$user = User::find(10);
if ($user) {
    echo "User found: " . $user->name . "\n";
    
    // Check applications
    $applications = $user->applications;
    echo "Applications count: " . $applications->count() . "\n";
    
    // Check logbooks through applications
    $logbooks = $user->logbooks;
    echo "Logbooks count: " . $logbooks->count() . "\n";
    
    foreach ($applications as $app) {
        echo "Application ID: " . $app->id . " - Division: " . $app->division->name . "\n";
        $appLogbooks = $app->logbooks;
        echo "  - Logbooks for this application: " . $appLogbooks->count() . "\n";
    }
} else {
    echo "User not found\n";
}
