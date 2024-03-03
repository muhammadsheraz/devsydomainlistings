<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Enums\DomainStatus;
use Carbon\Carbon;

class StartAuction extends Command
{
    protected $signature = 'auction:start';

    protected $description = 'Start domain auctions that have reached their start time';

    public function handle()
    {
        $domains = Domain::where('status', DomainStatus::UPCOMING)
            ->where('starting_date', '<=', Carbon::now())
            ->get();

        foreach ($domains as $domain) {
            $domain->status = DomainStatus::ACTIVE;
            $domain->save();
        }
    }
}
