<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Contracts\Actions\Users\CustomerCreator;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAccount extends Controller
{
    public function __invoke(CreateAccountRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = app()->make(CustomerCreator::class)->handle($request->validated());

            $this->createProfile($user);

            return fractal($user, new UserTransformer())->respond(301);
        });
    }

    public function createProfile(User $user)
    {
        // WARNING: This is a stub implementation.
        // Please don't delete this method or implementation.
        // The goal here is to introduce random failures to simulate what is required from the stories.
        $randomNumber = rand(1, 2);

        if ($randomNumber > 1) {
            throw new \Exception('Failed to create profile.');
        }
    }
}
