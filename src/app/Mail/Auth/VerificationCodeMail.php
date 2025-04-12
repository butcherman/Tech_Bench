<?php

namespace App\Mail\Auth;

use App\Models\UserVerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notification email with the users 2FA code.
 */
class VerificationCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected UserVerificationCode $userCode) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tech Bench Verification Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.userVerificationCode',
            with: [
                'user' => $this->userCode->user,
                'code' => $this->userCode,
            ]
        );
    }
}
