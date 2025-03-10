<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);
        
        $credintials = $request->only('email', 'password');
        // Auth::attempt($credintials)
        $user = User::whereEmail($request->email)->firstOrFail();
        if(Hash::check($request->password,$user->password)){
            $token = $user->createToken('authToken')->accessToken;
    
             return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unauthorized',
        ], 401);
    }

}
