<?php

namespace App\Support\Actions\Domains;

use App\Models\Domain;
use Illuminate\Support\Arr;
use App\Contracts\Actions\Domains\DomainCreator;
use Carbon\Carbon;
use App\Enums\DomainStatus;

class DomainCreatorAction implements DomainCreator
{
    /**
     * Handle the creation of a new domain.
     *
     * @param array $data
     * @return Domain
     * @throws \Exception
     */
    public function handle(array $data)
    {
        // Transform the dates to the 'Y-m-d H:i:s' format
        $data['starting_date'] = Carbon::parse($data['starting_date'])->format('Y-m-d H:i:s');
        $data['ending_date'] = Carbon::parse($data['ending_date'])->format('Y-m-d H:i:s');

        // Story 6.3: Set the status to "Active" if the current time is equal to the starting date
        if (Carbon::now()->startOfMinute()->equalTo(Carbon::parse($data['starting_date'])->startOfMinute())) {
            $data['status'] = DomainStatus::ACTIVE;
        }

        // Save new domain with the provided data
        return Domain::create(
            array_merge(
                Arr::only($data, [
                    'domain',
                    'exists_since',
                    'starting_date',
                    'ending_date',
                    'target_price',
                    'min_bid_increment',
                    'starting_price',
                    'status',
                    'deposit_type',
                    'deposit_amount',
                ])
            )
        );
    }
}
