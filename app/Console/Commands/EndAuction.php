<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Enums\DomainStatus;
use Carbon\Carbon;
use App\Jobs\SendAuctionWonEmail;

class EndAuction extends Command
{
    protected $signature = 'auction:end';

    protected $description = 'End domain auctions that have reached their Ending time';

    public function handle()
    {
        $domains = Domain::where('status', DomainStatus::ACTIVE)
            ->where('ending_date', '>=', Carbon::now())
            ->get();

            foreach ($domains as $domain) {
                $highestBid = $domain->bids()->orderBy('amount', 'desc')->first();

                if (empty($domain->target_amount) || $highestBid->amount >= (int)$domain->target_amount) {
                    $domain->status = DomainStatus::SOLD;
                    $highestBid->win = 1;
                    $highestBid->save();
                } else {
                    $domain->status = DomainStatus::CLOSED;
                }

                $domain->save();

                // Sending email to the winner
                if ($highestBid->win && !$highestBid->notified) {
                    $winner = $highestBid->user;
                    SendAuctionWonEmail::dispatch($winner, $domain);

                    $highestBid->notified = 1;
                    $highestBid->save();
                }
            }
    }
}
