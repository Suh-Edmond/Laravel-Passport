<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    public function create(Request $request)
    {
        $validateEmail = $request->validate([
            'email' => 'required|email|string'
        ]);
        $user = User::where('email', $validateEmail['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'We cannot find a user with this Email address'], 404);
        }
        $passwordreset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => Str::random(120)]
        );
        if ($user && $passwordreset) {
            $user->notify(new PasswordResetNotification($passwordreset->token));
        }
        return response()->json(['message' => 'We have e-mailed your password reset link']);
    }
    //find token function
    public function find($token)
    {
        $passwordreset = PasswordReset::where('token', $token)->first();
        if (!$passwordreset) {
            return response()->json(["message" => "Password reset token is Invalid"], 404);
        }
        if (Carbon::parse($passwordreset->updated_at)->addMinutes(720)->isPast()) {
            $passwordreset->delete();
            return response()->json(['message' => 'Password Reset token has expire', 'status' => 'expire']);
        }
        return response()->json($passwordreset);
    }
    //reset the users password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string'
        ]);
        $passwordreset = PasswordReset::where([
            ['email', $request->email],
            ['token', $request->token]
        ])->first();

        if (!$passwordreset) {
            return response()->json(['message' => "Password Reset Token not found"], 200);
        }
        $user = User::where('email', $passwordreset->email)->first();
        if (!$user) {
            return response()->json(['message' => "We can't find a user with that email, please check your email"], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordreset->delete();
        $user->notify(new PasswordResetSuccess($passwordreset));
        return response()->json($user);
    }
}
