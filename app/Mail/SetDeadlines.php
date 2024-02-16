<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SetDeadlines extends Mailable
{
    use Queueable, SerializesModels;

    public $deadlineData;
    /**
     * Create a new message instance.
     */
    public function __construct($deadlineData)
    {
        $this->deadlineData = $deadlineData;
    }

    public function build()
    {
        return $this->subject('New Deadline Notification')
            ->view('emails.deadline') // Use the Blade template for the memo
            ->with('data', $this->deadlineData); // Pass the memo data
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Set Deadlines',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.deadline',
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
