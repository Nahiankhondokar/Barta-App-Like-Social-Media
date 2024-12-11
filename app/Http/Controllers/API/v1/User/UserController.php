<?php

namespace App\Http\Controllers\API\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Traits\sendApiResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    use sendApiResponse;
    
    public function index()
    {
        $user = auth()->user();
        return $this->sendApiResponse($user, 'User details');
    }

    public function create(User $user)
    {
        return $this->sendApiResponse($user, 'User created');
    }

    public function update(Request $request, $id)
    {
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = md5(rand().time()).'.'.$file->extension();
            $pathWithFile = 'storage/'.$file->storePubliclyAs('profile', $fileName, 'public');
        }

        $user = User::query()->find($id);
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->bio      = $request->bio;
        $user->username = $request->username;
        $user->image    = $pathWithFile ?? null;
        $user->update();

        if($request->password){
            $user->password  = Hash::make($request->password);
            $user->update();
        }

        return $this->sendApiResponse($user, 'User updated');
    }

    public function search(Request $request)
    {
        $posts = Post::query()
        ->whereHas('user', function($q) use ($request){
            $q->whereFullText(['name', 'username', 'email'], $request->search);
        })
        ->orWhereFullText(['barta'], $request->search)
        ->get();

        return $this->sendApiResponse($posts, 'Search result');
    }
}
