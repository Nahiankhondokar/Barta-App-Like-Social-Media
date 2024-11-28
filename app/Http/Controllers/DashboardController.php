<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $posts = Post::query()->with('user')->orderBy('id', 'desc')->get();
        // dd($posts->toArray());
        return view('dashboard', compact('posts'));    
    }
}
