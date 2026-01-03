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

// Primary user notification channel - used by Laravel's notification system
// This is the default channel format for DatabaseNotification broadcasts
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Legacy user channel - kept for backward compatibility
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin Maps channel - only admin users can access
Broadcast::channel('admin-maps', function ($user) {
    return $user && in_array($user->role, ['admin', 'mentor']);
});

// Admin notification channel - for admin-specific broadcasts
Broadcast::channel('admin-notifications', function ($user) {
    return $user && in_array($user->role, ['admin', 'mentor']);
});

// Public attendance channel - for general attendance updates
Broadcast::channel('attendance-updates', function ($user) {
    return true; // Public channel, anyone can listen
});

// Logbook channel - for logbook-specific updates
Broadcast::channel('logbook.{logbookId}', function ($user, $logbookId) {
    // User can listen if they own the logbook or are admin/mentor
    $logbook = \App\Models\Logbook::find($logbookId);
    
    if (!$logbook) {
        return false;
    }
    
    return $user->id === $logbook->user_id || in_array($user->role, ['admin', 'mentor']);
});