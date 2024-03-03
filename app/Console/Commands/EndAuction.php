<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Enums\DomainStatus;
use Carbon\Carbon;

class EndAuction extends Command
{
    protected $signature = 'auction:end';

    protected $description = 'End domain auctions that have reached their Ending time';

    public function handle()
    {
        $domains = Domain::where('status', DomainStatus::UPCOMING)
            ->where('ending_date', '>=', Carbon::now())
            ->get();

        foreach ($domains as $domain) {
            $domain->status = DomainStatus::CLOSED;
            $domain->save();
        }
    }
}
