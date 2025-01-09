<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('post.like.user.{postUserId}', function ($user, $postUserId) {
    Log::info('user-'. $user->id);
    Log::info('postUserId-'. $postUserId);
    return (int) $user->id == (int) $postUserId;
});
