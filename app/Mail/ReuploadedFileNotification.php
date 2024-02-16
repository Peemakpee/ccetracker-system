<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReuploadedFileNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $fileData;

    /**
     * Create a new message instance.
     */
    public function __construct($fileData)
    {
        $this->fileData = $fileData;
    }

    public function build()
    {
        return $this->subject('Reuploaded Deliverable')
            ->view('emails.reuploaded_file_notification')
            ->with('data', $this->fileData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reuploaded File Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reuploaded_file_notification',
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
