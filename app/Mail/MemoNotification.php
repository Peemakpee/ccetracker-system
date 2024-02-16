<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemoNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $memoData;

    /**
     * Create a new message instance.
     */
    public function __construct($memoData)
    {
        $this->memoData = $memoData;
    }

    public function build()
    {
        return $this->subject('Memo Notification')
            ->view('emails.memo') // Use the Blade template for the memo
            ->with('data', $this->memoData); // Pass the memo data
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Memo Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.memo',
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
