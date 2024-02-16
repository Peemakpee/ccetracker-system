<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovedByPh extends Mailable
{
    use Queueable, SerializesModels;

    public $approvedPhdata;

    /**
     * Create a new message instance.
     */
    public function __construct($approvedPhdata)
    {
        $this->approvedPhdata = $approvedPhdata;
    }

    public function build()
    {
        return $this->subject('Deliverable Approved by PH')
            ->view('emails.approved_ph')
            ->with('data', $this->approvedPhdata);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Approved By Ph',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.approved_ph',
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
