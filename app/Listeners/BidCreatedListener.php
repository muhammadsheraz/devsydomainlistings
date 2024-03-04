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
        $bidExtendedTime = 3;

        // Get the previous highest bid for the domain
        $previousHighestBid = Bid::where('domain_id', $event->bid->domain_id)
            ->where('amount', '<', $event->bid->amount)
            ->orderBy('amount', 'desc')
            ->first();

        if ($previousHighestBid) {
            // Refund the deposit to the previous highest bidder
            $previousHighestBid->user->increment('wallet', $previousHighestBid->amount);
        }

        // Story 7.4: Reset and extend the auction time on the last minute bid
        $auctionEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->bid->domain->ending_date);
        if (Carbon::now()->greaterThanOrEqualTo($auctionEndTime->subMinute())) {
            $event->bid->domain->ending_date = Carbon::now()->addMinutes($bidExtendedTime);
            $event->bid->domain->save();
        }

        // Dispatching job to Send the Bid confirmation email
        $bidConfirmationEmailJob = new SendBidConfirmationEmail($event->bid->user);

        // Sending Bid confirmation email.
        // Story 7.7: A good buffer time is 3-minute which is the same as the time the auction is
        // extended on the last minute bid.
        $bidConfirmationEmailJobId = dispatch($bidConfirmationEmailJob)->delay(now()->addMinutes($bidExtendedTime))->id();

        // Saving job ID in the bids table, so that we can track the job and cancel it within 3 minutes of buffer time.
        $event->bid->job_id = $jobId;
        $event->bid->save();
        // Mail::to($event->bid->user->email)->send(new BidConfirmation($event->bid));
    }
}
