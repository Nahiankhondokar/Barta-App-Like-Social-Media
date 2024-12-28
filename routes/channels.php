<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('post.like.{postUserId}', function ($user, $postUserId) {
    Log::info("post-user-id-".$postUserId);
    Log::info("auth user id-".$user->id);
    return (int) $user->id === (int) $postUserId;
});
