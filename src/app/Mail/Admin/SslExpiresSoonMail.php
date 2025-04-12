<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SslExpiresSoonMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(public int $daysLeft) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SSL Certificate is about to expire',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.admin.sslExpiresSoon',
        );
    }
}
