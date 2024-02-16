<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovedByDean extends Mailable
{
    use Queueable, SerializesModels;

    public $approvedDeandata;

    /**
     * Create a new message instance.
     */
    public function __construct($approvedDeandata)
    {
        $this->approvedDeandata = $approvedDeandata;
    }

    public function build()
    {
        return $this->subject('Deliverable Approved by Dean')
            ->view('emails.approved_dean')
            ->with('data', $this->approvedDeandata);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Approved By Dean',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.approved_dean',
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
