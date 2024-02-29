<?php

namespace App\Support\Actions\Users;

use App\Models\User;
use Illuminate\Support\Arr;

class CustomerCreatorAction
{
    public function handle(array $data)
    {
        return User::create(
            Arr::only($data, [
                'name',
                'email',
                'password',
            ])
        );
    }
}
