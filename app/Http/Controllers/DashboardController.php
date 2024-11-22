<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $posts = DB::table('posts')->orderByDesc('id')->get();
        return view('dashboard', compact('posts'));    
    }
}
