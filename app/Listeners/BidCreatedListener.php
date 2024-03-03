<?php

namespace App\Listeners;

use App\Models\Bid;
use App\Events\BidCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendBidConfirmationEmail;

class BidCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BidCreated $event): void
    {
        // Get the previous highest bid for the domain
        $previousHighestBid = Bid::where('domain_id', $event->bid->domain_id)
            ->where('amount', '<', $event->bid->amount)
            ->orderBy('amount', 'desc')
            ->first();

        if ($previousHighestBid) {
            // Refund the deposit to the previous highest bidder
            $previousHighestBid->user->increment('wallet', $previousHighestBid->amount);
        }

        // Reset the auction time
        $auctionEndTime = $event->bid->domain->ending_date;
        if (Carbon::now()->addMinute()->greaterThanOrEqualTo($auctionEndTime)) {
            $event->bid->domain->ending_date = Carbon::now()->addMinutes(3);
            $event->bid->domain->save();
        }

        // Dispatching job to Send the Bid confirmation email
        // This has to be sent in sometime, not immediately
        // In my opinion we should send the email in 3 minutes
        SendBidConfirmationEmail::dispatch($event->bid->user)->delay(now()->addMinutes(3));
        // Mail::to($event->bid->user->email)->send(new BidConfirmation($event->bid));
    }
}
