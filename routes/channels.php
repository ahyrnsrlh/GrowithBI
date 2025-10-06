<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// User-specific notification channel
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin Maps channel - only admin users can access
Broadcast::channel('admin-maps', function ($user) {
    return $user && $user->role === 'admin';
});

// Public attendance channel - for general attendance updates
Broadcast::channel('attendance-updates', function ($user) {
    return true; // Public channel, anyone can listen
});