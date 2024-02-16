<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForRevisionByDean extends Mailable
{
    use Queueable, SerializesModels;

    public $revisionData;
    /**
     * Create a new message instance.
     */
    public function __construct($revisionData)
    {
        $this->revisionData = $revisionData;
    }

    public function build()
    {
        return $this->subject('Deliverable Reviewed by Dean Requires Revision')
            ->view('emails.for_revision_dean')
            ->with('data', $this->revisionData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'For Revision By Dean',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.for_revision_dean',
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
