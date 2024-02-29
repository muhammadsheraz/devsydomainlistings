<?php

namespace App\Support\Actions\Domains;

use App\Models\Domain;
use Illuminate\Support\Arr;

class DomainCreatorAction
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
                ['status' => 'UPCOMING']
            )
        );
    }
}
