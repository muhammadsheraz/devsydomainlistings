<?php

namespace App\Contracts\Actions\Users;

interface UserAuthenticator
{
    public function handle(string $email, string $password);
}
