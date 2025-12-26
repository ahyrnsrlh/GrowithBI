<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Application $application;
    public string $status;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, string $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->status === 'approved' ? 'Diterima' : 'Ditolak';
        
        return new Envelope(
            subject: '📢 Informasi Pengajuan Magang Anda - Bank Indonesia KPW Lampung',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application.status',
            with: [
                'application' => $this->application,
                'status' => $this->status,
                'statusText' => $this->status === 'approved' ? 'DITERIMA' : 'DITOLAK',
                'statusColor' => $this->status === 'approved' ? '#10B981' : '#EF4444',
                'userName' => $this->application->name,
                'divisionName' => $this->application->division->name ?? 'N/A',
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
