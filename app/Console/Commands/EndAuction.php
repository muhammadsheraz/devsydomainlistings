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
        $targetReached = false;

        $domains = Domain::where('status', DomainStatus::ACTIVE)
            ->where('ending_date', '>=', Carbon::now())
            ->get();

            foreach ($domains as $domain) {
                $domainBid = $domain->bids()
                ->orderBy('amount', 'desc')
                ->orderBy('created_at', 'asc')
                ->first();

                if (empty($domain->target_amount) || $domainBid->amount >= (int)$domain->target_amount) {
                    $domain->status = DomainStatus::SOLD;
                    $domainBid->win = 1;
                    $domainBid->save();

                    $targetReached = true;
                } else {
                    $domain->status = DomainStatus::CLOSED;
                }

                $domain->save();
                if ($targetReached) {
                    // Target achieved: Sending email to the winner
                    if ($domainBid->win && !$domainBid->notified) {
                        $winner = $domainBid->user;
                        SendAuctionWonEmail::dispatch($winner, $domain);

                        $domainBid->notified = 1;
                        $domainBid->save();
                    }

                    // Sending email to all bidders who lost
                    // TBD
                } else {
                    // Target not achieved: Sending email to all bidders
                    $bidders = $domain->bids()->with('user')->get();
                    foreach ($bidders as $bidder) {
                        SendAuctionLostEmail::dispatch($bidder->user, $domain);
                    }
                }

            }
    }
}
