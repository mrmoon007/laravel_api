<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat', function ($user) {
    return true; // Allow all authenticated users to access this channel
});
