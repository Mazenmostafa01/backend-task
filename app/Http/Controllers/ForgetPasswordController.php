<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    public function sendResetLinkMail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $status = Password::sendResetLink($request->only('email')); //to send password reset link

        return $status === Password::RESET_LINK_SENT ? response()->json(['message' => __($status)], 200)
         : response()->json(['error' => __($status)], 400);
    }
}
