<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuctionWon;
use App\Models\User;
use App\Models\Domain;

class SendAuctionWonEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $domain;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Domain $domain)
    {
        $this->user = $user;
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new AuctionWon($this->user->name, $this->domain->domain));
    }
}
