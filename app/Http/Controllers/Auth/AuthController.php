<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = DB::table('users')->insert([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);
        
        if($user){
            return to_route('login.view')->with('success', 'User registration successful');
        }

        return redirect()->back();
    }   

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password,
        ];
      
        if (Auth::attempt($credentials)) {
            // $user = DB::table('users')->where('email', $request->email)->first();
            // Auth::login($user);
            $request->session()->regenerate();
 
            return to_route('dashboard');
        }

        return redirect()->back()->with('error', 'Wrong credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }
}
