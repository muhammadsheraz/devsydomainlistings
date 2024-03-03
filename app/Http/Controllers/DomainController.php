<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Domains\DomainCreator;
use App\Http\Requests\CreateAccountRequest;
use App\Contracts\Actions\Users\CustomerCreator;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Transformer\UserTransformer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function add(StoreDomainRequest $request)
    {
        app()->make(DomainCreator::class)->handle($request->validated());

        return response()->json([
            'message' => 'Domain added successfully',
        ], 201);
    }
}
