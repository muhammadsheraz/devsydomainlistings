<?php

namespace App\Contracts\Actions\Domains;

interface DomainCreator
{
    public function handle(array $data);
}
