<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\sendApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use sendApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::query()->create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);
        
        if($user){
            return $this->sendApiResponse($user, "User registration successfull");
        }
        return $this->sendApiResponse('', "User registration failed");
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

    public function me(): JsonResponse
    {
        $authUser = auth()->user();
        return response()->json($authUser);
    }
}
