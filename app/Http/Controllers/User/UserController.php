<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function create(User $user): View
    {
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::query()->find($id);
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->bio      = $request->bio;
        $user->username = $request->username;
        $user->update();

        if($request->password){
            $user->password  = Hash::make($request->password);
            $user->update();
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated');
    }
}
