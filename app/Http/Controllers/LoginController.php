<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Users\UserAuthenticator;
use App\Http\Requests\AuthenticateByTwoFactorRequest;
use App\Http\Requests\AuthenticateUserRequest;
use App\Models\TwoFactorAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function authenticate(AuthenticateUserRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = app()->make(UserAuthenticator::class)->handle(
                $request->validated('email'),
                $request->validated('password')
            );

            if ($user === false) {
                return response()->json([
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            $token = $user->createToken('test')->plainTextToken;

            TwoFactorAuth::create([
                'user_id' => $user->id,
                'code' => $otp = random_int(1000, 9999),
            ]);

            Mail::to($user)->send(new \App\Mail\TwoFactorAuthOtp($otp));

            return response()->json([
                'data' => [
                    'token' => $token,
                    'otp' => $otp,
                ]
            ]);
        });
    }

    public function twoFactorAuthentication(AuthenticateByTwoFactorRequest $request)
    {
        $twoFactorAuth = TwoFactorAuth::where('code', $request->validated('otp'))->first();

        if ($twoFactorAuth === null) {
            return response()->json([
                'message' => 'Invalid OTP.',
            ], 401);
        }
        
        return response()->json();
    }
}
