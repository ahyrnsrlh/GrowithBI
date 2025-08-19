<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check all users
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "User ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Photo path: " . ($user->profile_photo_path ?? 'NULL') . "\n";
    if ($user->profile_photo_path) {
        echo "Photo exists: " . (file_exists(public_path('storage/' . $user->profile_photo_path)) ? 'Yes' : 'No') . "\n";
    }
    echo "---\n";
}
