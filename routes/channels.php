<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('post.like.userid.{postUserId}', function ($user, $postUserId) {
    Log::info("post-user-id-".$postUserId);
    Log::info("auth user id-".$user->id);
    return $user->id == $postUserId;
});
