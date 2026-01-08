<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * TwoFactorCodeMail
 * 
 * Email containing the OTP code for two-factor authentication.
 * 
 * Security notes:
 * - Contains clear expiration warning
 * - Includes security notice not to share the code
 * - Provides context about the login attempt
 */
class TwoFactorCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $otpCode;
    public int $expiresInMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $otpCode, int $expiresInMinutes = 5)
    {
        $this->user = $user;
        $this->otpCode = $otpCode;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Kode Verifikasi Login - GrowithBI',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.two-factor-code',
            with: [
                'user' => $this->user,
                'otpCode' => $this->otpCode,
                'expiresInMinutes' => $this->expiresInMinutes,
                'userName' => $this->user->name,
            ],
        );
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
