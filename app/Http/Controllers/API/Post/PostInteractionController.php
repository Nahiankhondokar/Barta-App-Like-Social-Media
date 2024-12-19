<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Traits\sendApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    use sendApiResponse;

    public function likeList(): JsonResponse
    {
        $likes = Like::query()->with('posts', 'users')->get();
        return $this->sendApiResponse($likes, "Like list show");
    }

    public function likedStore(Request $request): JsonResponse
    {
        $likes = Like::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'like_status'   => $request->like_status,
        ]);

        return $this->sendApiResponse($likes, "Liked a post successfully");
    }

    public function commentList(): JsonResponse
    {
        $likes = Comment::query()->with('posts', 'users')->get();
        return $this->sendApiResponse($likes, "Comment list show");
    }

    public function commentStore(Request $request): JsonResponse
    {
        $likes = Comment::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'comment_status'    => $request->comment_status,
            'comment_message'   => $request->comment_message,
        ]);

        return $this->sendApiResponse($likes, "Commented a post successfully");
    }

}
