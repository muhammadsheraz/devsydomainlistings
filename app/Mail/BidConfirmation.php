<?php

namespace App\Mail;

use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendBidConfirmationEmail;

class BidConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $bid;

    /**
     * Create a new message instance.
     */
    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

        /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your bid is the highest on the "' . $this->bid->domain->name . '" domain!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.bid-confirmation',
            with: [
                'user_name' => $this->userName,
            ],
        );
    }
}
