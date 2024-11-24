<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        try {
            DB::table('posts')->insert([
                'barta'     => $request->barta,
                'created_at'=> Carbon::now()
            ]);

            return redirect()->back()->with('success', 'Post created successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('post.single', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if(!$post){
            return redirect()->route('dashboard')->with('success', 'Post not found!');
        }

        $post->barta = $request->barta;
        $post->save();

        return redirect()->route('dashboard')->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if(!$post){
            return redirect()->route('dashboard')->with('success', 'Post not found!');
        }
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post deleted successfully');
    }
}
