<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForRevisionByPh extends Mailable
{
    use Queueable, SerializesModels;

    public $forRevisionPhdata;

    /**
     * Create a new message instance.
     */
    public function __construct($forRevisionPhdata)
    {
        $this->forRevisionPhdata = $forRevisionPhdata;
    }

    public function build()
    {
        return $this->subject('Deliverable Tagged as \'For Revision\' by PH')
            ->view('emails.forRevisionbyPh')
            ->with('data', $this->forRevisionPhdata);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'For Revision By Ph',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.forRevisionbyPh',
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
