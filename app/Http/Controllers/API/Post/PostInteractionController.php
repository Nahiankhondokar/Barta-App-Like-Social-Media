<?php

namespace App\Http\Controllers\API\Post;

use App\Events\PostLikeEvent;
use App\Http\Controllers\Controller;
use App\Mail\CommentMail;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\LikeNotification;
use App\Traits\sendApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $like = Like::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'like_status'   => $request->like_status,
        ]);

        $post = Post::query()->with('user')->where('id', $request->post_id)->first();
        $user = User::find($request->user_id);
        $authUser = auth()->user();

        if($like->like_status == Like::LIKE){
            $message = "Like";
            $user->notify(new LikeNotification($post, $user));
            PostLikeEvent::dispatch($post, $authUser);
        }else {
            $message = "Unlike";
        }

        return $this->sendApiResponse($like, "$message a post successfully");
    }

    public function commentList(Request $request, $limit = 5): JsonResponse
    {
        $comment = Comment::query()
        ->with('posts', 'users')
        ->orderBy('id', 'desc')
        ->paginate($limit);

        return $this->sendApiResponse($comment, "Comment list show");
    }

    public function commentStore(Request $request): JsonResponse
    {
        $comment = Comment::query()->updateOrCreate([
            'user_id'       => $request->user_id,
            'post_id'       => $request->post_id,
        ],[
            'comment_status'    => $request->comment_status,
            'comment_message'   => $request->comment_message,
        ]);

        $post = Post::find($request->post_id);
        $user = User::find($request->user_id);

        // Mail::to($user)->send(new CommentMail($user, $post, $comment));

        return $this->sendApiResponse($comment, "Commented a post successfully");
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
