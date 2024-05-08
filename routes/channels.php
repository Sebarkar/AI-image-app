<?php

use Illuminate\Support\Facades\Broadcast;
use \App\Models\User;

Broadcast::channel('task.{id}', function (User $user) {
    return ['ably-capability' => ["subscribe", "history"]];
});

Broadcast::channel('user.{id}', function (User $user) {
    return ['ably-capability' => ["subscribe", "history"]];
});
