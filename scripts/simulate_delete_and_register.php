<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

$email = 'simulate_delete@example.com';

// Clean up any previous test rows with this email (hard delete)
User::withTrashed()->where('email', $email)->forceDelete();

echo "Creating initial user with email: $email\n";
$initial = User::create([
    'name' => 'Sim Test',
    'email' => $email,
    'password' => Hash::make('password123'),
    'role' => 'peserta',
    'is_active' => true,
]);

echo "Created user id: {$initial->id}\n";

echo "Soft-deleting the user...\n";
$initial->delete();

$exists = User::withTrashed()->where('email', $email)->count();
echo "Records with that email (including trashed): $exists\n";
$trashed = User::withTrashed()->where('email', $email)->first();
echo "deleted_at for trashed record: " . ($trashed->deleted_at ? $trashed->deleted_at : 'null') . "\n";

// Simulate registration validation
$input = [
    'name' => 'Sim Test 2',
    'email' => $email,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'peserta',
];

$rules = [
    'name' => 'required|string|max:255',
    'email' => [
        'required','string','lowercase','email','max:255',
        Rule::unique('users')->whereNull('deleted_at'),
    ],
    'password' => ['required','confirmed'],
    'role' => 'required|in:peserta,pembimbing',
];

$validator = Validator::make($input, $rules);
if ($validator->fails()) {
    echo "Validation failed:\n";
    foreach ($validator->errors()->all() as $err) {
        echo " - $err\n";
    }
    exit(1);
}

echo "Validation passed. Creating new user with same email...\n";
$new = User::create([
    'name' => $input['name'],
    'email' => $input['email'],
    'password' => Hash::make($input['password']),
    'role' => $input['role'],
    'is_active' => true,
]);

echo "New user created id: {$new->id}\n";

// Show counts
$total = User::withTrashed()->where('email', $email)->count();
echo "Total records with that email (including trashed): $total\n";
$list = User::withTrashed()->where('email', $email)->get();
foreach ($list as $u) {
    echo " - id: {$u->id}, deleted_at: " . ($u->deleted_at ? $u->deleted_at : 'null') . "\n";
}

// Cleanup test data: remove both records
User::withTrashed()->where('email', $email)->forceDelete();
echo "Cleaned up test records.\n";
