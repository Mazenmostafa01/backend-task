<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
            'token' => ['required']
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
        function($user, $password){
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(10)])
                ->save();
        });

        return $status === Password::PASSWORD_RESET ? response()->json(['message' =>  __($status)], 200)
        : response()->json(['error' => __($status)], 400);
    }
}
