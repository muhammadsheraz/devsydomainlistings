<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionWon extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $domain;
    public $user_name;

    /**
     * Create a new message instance.
     */
    public function __construct(String $domain, String $user_name)
    {
        $this->domain = $domain;
        $this->user_name = $user_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Auction Won',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return $this->subject('You won "' . $this->domain . '" domain!')
            ->view('emails.auctionWon');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
