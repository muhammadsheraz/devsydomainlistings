<?php

namespace App\Support\Actions\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Contracts\Actions\Users\CustomerCreator;

class CustomerCreatorAction implements CustomerCreator
{
    public function handle(array $data)
    {
        $user = User::create(
            Arr::only($data, [
                'name',
                'email',
                'password',
            ])
        );

        $user->wallet()->create();

        return $user;
    }
}
