<?php

use App\Models\Post;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('post.like.userid.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});
