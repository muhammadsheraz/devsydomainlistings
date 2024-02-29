<?php

namespace App\Support\Actions\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Contracts\Actions\Users\UserAuthenticator;

class UserAuthenticatorAction implements UserAuthenticator
{
    public function handle(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return false;
        }

        return $user;
    }
}
