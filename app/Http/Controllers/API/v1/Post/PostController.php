<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Traits\sendApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    use sendApiResponse;

    public function index($pageNum = 20)
    {
        $posts = Post::query()
        ->with('user', 'like')
        ->orderBy('id', 'desc')
        ->paginate($pageNum);

        return $this->sendApiResponse($posts, "Post list show");
    }

    public function store(PostStoreRequest $request)
    {
        try {
           if($request->hasFile('image')){
                $file = $request->file('image');
                $fileName = md5(rand().time()).'.'.$file->extension();
                $pathWithFile = $file->storePubliclyAs('post', $fileName, 'public');
            }

            $post = Post::query()->create([
                'user_id'   => auth()->user()->id,
                'barta'     => $request->barta,
                'image'     => $pathWithFile ?? null,
                'created_at'=> Carbon::now()
            ]);

            return $this->sendApiResponse($post, "Post store successfully"); 
        } catch (\Throwable $e) {
            return $this->sendApiResponse('', $e->getMessage(), '', [], 401);
        }
    }

    public function show(Post $post): JsonResponse
    {
        return $this->sendApiResponse($post->load('user'), "Post details");
    }

    public function edit(Post $post)
    {
        if(auth()->user()->id != $post->user_id){
            return $this->sendApiResponse('', 'Invalid user!');
        }
      
        return $this->sendApiResponse($post, "Post edit");
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        if(auth()->user()->id != $post->user_id){
            return $this->sendApiResponse('', 'Invalid user!');
        }

        if(!$post){
            return $this->sendApiResponse('', 'Post not found!');
        }

        if($request->hasFile('image')){
            Storage::disk('public')->delete($post->image);

            $file = $request->file('image');
            $fileName = md5(rand().time()).'.'.$file->extension();
            $pathWithFile = $file->storePubliclyAs('post', $fileName, 'public');
            
        }else {
            $pathWithFile = $post->image;
        }

        $post->barta = $request->barta;
        $post->image = $pathWithFile ?? null;
        $post->save();

        return $this->sendApiResponse($post, "Post udpated successfully");
    }

    public function destroy(Post $post)
    {
        if(auth()->user()->id != $post->user_id){
            return $this->sendApiResponse('', 'Invalid user!');
        }

        if(!$post){
            return $this->sendApiResponse('', 'Post not found!');
        }
        $post->delete();

        return $this->sendApiResponse('', "Post deleted successfully");
    }
}
