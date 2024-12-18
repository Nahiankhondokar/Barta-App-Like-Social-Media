<?php

namespace App\Http\Controllers\API\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Traits\sendApiResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    use sendApiResponse;
    
    public function index()
    {
        $user = auth()->user();
        return $this->sendApiResponse($user, 'User details');
    }

    public function edit(User $user)
    {
        return $this->sendApiResponse($user, 'Edit user');
    }

    public function update(Request $request, User $user)
    {
        if($request->hasFile('newImage')){
            Storage::disk('public')->delete($user->image);
            $file = $request->file('newImage');
            $fileName = md5(rand().time()).'.'.$file->getClientOriginalExtension();
            $pathWithFile = $file->storePubliclyAs('profile', $fileName, 'public');

        }else {
            $pathWithFile = $user->image;
        }

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->bio      = $request->bio;
        $user->username = $request->username;
        $user->image    = $pathWithFile;
        $user->update();

        if(!empty($request->password)){
            $user->password  = Hash::make($request->password);
            $user->update();
        }

        return $this->sendApiResponse($user, 'User updated');
    }

    public function search(Request $request, $pageNum = 5)
    {
        $posts = Post::query()
        ->whereHas('user', function($q) use ($request){
            $q->whereFullText(['name', 'username', 'email'], $request->search);
        })
        ->orWhereFullText(['barta'], $request->search)
        ->paginate($pageNum);

        if(count($posts) == 0){
            $posts = Post::query()->with('user')->orderByDesc('id')->paginate($pageNum);
        }

        return $this->sendApiResponse($posts->load('user'), 'Search result');
    }
}
