<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/login",
     *      summary="login",
     *      tags={"login"},  
     * @OA\RequestBody(
     *      required=true,
     *      description="pass credintials",
     *   @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="test@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *     ),
     *   ),
     * @OA\Response(
     *    response=200,
     *    description="log in success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="user found log in success")
     *        ),
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="wrong credintials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="wrong mail or password")
     *   ),
     *  ),
     * )
     */


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
    
        $tokenRequest = Request::create('/oauth/token','POST', [
            'grant_type' => 'password',
            'client_id' =>  env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' =>env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        $response = app()->handle($tokenRequest);

        $tokenData = (array)json_decode($response->getContent());
        $user['access_token'] = $tokenData['access_token'];
        $user['refresh_token'] = $tokenData['refresh_token'];

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
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
