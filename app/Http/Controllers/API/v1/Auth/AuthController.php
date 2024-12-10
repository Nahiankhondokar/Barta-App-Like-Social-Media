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
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user();
    
            return $this->sendApiResponse($user, "User login successful");
        }
    
        return $this->sendApiResponse([], "User login failed",'',[], 401);
    }
    

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->sendApiResponse([], "Logged out successfully");
    }

    public function me(): JsonResponse
    {
        $authUser = auth()->user();
        return $this->sendApiResponse($authUser , "LoggedIn user");
    }
}
