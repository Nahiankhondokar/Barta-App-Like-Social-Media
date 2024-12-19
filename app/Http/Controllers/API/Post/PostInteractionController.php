<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeNotification;
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

    public function likeUnlike(Request $request): JsonResponse
    {
        $likes = Like::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'like_status'   => $request->like_status,
        ]);

        $post = Post::find($request->post_id);
        $user = User::find($request->user_id);

        if($request->like_status == Like::LIKE){
            $message = Like::LIKE;
            $user->notify(new LikeNotification($post, $user));
        }else {
            $message = Like::UNLIKE;
        }

        return $this->sendApiResponse($likes, "$message a post successfully");
    }

    public function commentList(Request $request): JsonResponse
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

    public function commentDelete(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);
        if(!$comment){
            return $this->sendApiResponse('', "Comment not found!");
        }
        $comment->delete();

        return $this->sendApiResponse('', "Commented deleted successfully");
    }

}
