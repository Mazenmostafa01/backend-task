<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    
        // Check credentials
        $user = User::where('email', $request->email)->firstOrFail();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
    
        $response = Http::post(config('app.url') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);
        info($response);
    
        if ($response->successful()) {
            $tokenData = $response->json();
    
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $tokenData['access_token'],
                    'refresh_token' => $tokenData['refresh_token'],
                    'type' => 'bearer',
                    'expires_in' => $tokenData['expires_in'],
                ],
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to issue token',
        ], 500);
    }

//     public function refresh(Request $request)
//     {
//         $request->validate([
//             'refresh_token' => ['required']
//         ]);

//         $user = User::where('refresh_token', $request->refresh_token)->firstOrFail();

//             $token = $user->createToken('authToken')->accessToken;
//             $newRefreshToken = Str::random(60);
//             $user->update(['refresh_token' => Hash::make($newRefreshToken)]);
            
//             return response()->json([
//                 'status' => 'success',
//                 'user' => $user,
//                 'authorisation' => [
//                     'token' => $token,
//                     'type' => 'bearer',
//                     'refresh_token' => $newRefreshToken
//                     ]
//                 ], 200);
//     }

}
