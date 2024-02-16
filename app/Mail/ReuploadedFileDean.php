<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReuploadedFileDean extends Mailable
{
    use Queueable, SerializesModels;

    public $reuploadData;
    /**
     * Create a new message instance.
     */
    public function __construct($reuploadData)
    {
        $this->reuploadData = $reuploadData;
    }

    public function build()
    {
        return $this->subject('Reuploaded Deliverable for Dean')
            ->view('emails.reuploaded_file_dean')
            ->with('data', $this->reuploadData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reuploaded File Dean',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reuploaded_file_dean',
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
