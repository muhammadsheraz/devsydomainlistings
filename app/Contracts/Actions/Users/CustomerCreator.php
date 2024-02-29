<?php

namespace App\Contracts\Actions\Users;

interface CustomerCreator
{
    public function handle(array $data);
}
