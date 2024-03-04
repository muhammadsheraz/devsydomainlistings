<?php

namespace App\Support\Actions\Domains;

use App\Models\Domain;
use Illuminate\Support\Arr;
use App\Contracts\Actions\Domains\DomainCreator;

class DomainCreatorAction implements DomainCreator
{
    public function handle(array $data)
    {
        return Domain::create(
            array_merge(
                Arr::only([
                    'domain',
                    'exists_since',
                    'starting_date',
                    'ending_date',
                    'min_bid_increment',
                    'starting_price',
                    'target_price',
                ]),
            )
        );
    }
}
