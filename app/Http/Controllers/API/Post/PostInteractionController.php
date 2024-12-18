<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Traits\sendApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    use sendApiResponse;

    public function likeList(): JsonResponse
    {
        $likes = Like::query()->with('post', 'user')->get();
        return $this->sendApiResponse($likes, "Like list show");
    }

    public function liked(Request $request): JsonResponse
    {
        $likes = Like::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'like_status'   => $request->like_status,
        ]);

        return $this->sendApiResponse($likes, "Liked a post successfully");
    }
}
