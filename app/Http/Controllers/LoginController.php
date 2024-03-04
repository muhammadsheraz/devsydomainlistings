<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Users\UserAuthenticator;
use App\Http\Requests\AuthenticateByTwoFactorRequest;
use App\Http\Requests\AuthenticateUserRequest;
use App\Models\TwoFactorAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Session\TokenMismatchException;

class LoginController extends Controller
{
    /**
     * Authenticate a user using email and password.
     *
     * @param  \App\Http\Requests\AuthenticateUserRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function authenticate(AuthenticateUserRequest $request)
    {
        /** Story 1 Solution
         * This is just a manual implementation to demonstrate the CSRF tokens validation.
         * Whereas Laravel already has a middleware to handle this, we only need to add
         * the middleware to the route and @csrf (blade directive) to the form.
        */

        // Retrieve the CSRF token from the request
        $token = $request->input('_token');

        // Compare the CSRF token in the request with the one stored in the session
        if (! $request->session()->token() == $token) {
            // If the tokens do not match, throw a TokenMismatchException
            throw new TokenMismatchException;
        }
        /********************************************************************************/

        // Authenticate the user
        return DB::transaction(function () use ($request) {
            $user = app()->make(UserAuthenticator::class)->handle(
                $request->validated('email'),
                $request->validated('password')
            );

            // If the user is not found, return a 401 response
            if ($user === false || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.',
                ], 401);
            }

            $token = $user->createToken('test')->plainTextToken;

            // Create a new TwoFactorAuth record

            /** Story 2 Solution
             * Added expires_at column to the two_factor_auths table to store the expiration date of the OTP.
             * With the expiration time of 2 minutes
            */
            TwoFactorAuth::create([
                'user_id' => $user->id,
                'code' => $otp = random_int(1000, 9999),
                'expires_at' => now()->addMinutes(2),
            ]);
            Log::info('Login OTP ' . $otp . ' generated.');


            // Send the OTP to the user
            Mail::to($user)->send(new \App\Mail\TwoFactorAuthOtp($otp));

            Log::info('Login OTP ' . $otp . ' Sent to User email : ' . $user->email);

            return response()->json([
                'data' => [
                    'token' => $token,
                    'otp' => $otp,
                ]
            ]);
        });
    }

    /**
     * Authenticate a user using two-factor authentication.
     */
    public function twoFactorAuthentication(AuthenticateByTwoFactorRequest $request)
    {
        $twoFactorAuth = TwoFactorAuth::where('code', $request->validated('otp'))->first();

        if ($twoFactorAuth === null || now()->greaterThan($twoFactorAuth->expires_at)) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 401);
        }

        return response()->json();
    }
}
