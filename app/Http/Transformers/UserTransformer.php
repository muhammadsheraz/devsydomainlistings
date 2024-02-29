<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'name',
        'email',
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
        ];
    }

    public function includeName(User $user)
    {
        return $this->primitive($user->name);
    }

    public function includeEmail(User $user)
    {
        return $this->primitive($user->email);
    }
}
