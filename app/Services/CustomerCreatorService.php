<?php
namespace App\Services;

use App\Contracts\Actions\Users\CustomerCreator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerCreatorService implements CustomerCreator
{
    public function handle(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);

        $user->wallet()->create();

        return $user;
    }
}
