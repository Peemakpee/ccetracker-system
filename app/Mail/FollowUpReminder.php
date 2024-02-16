<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FollowUpReminder extends Mailable
{
    use Queueable, SerializesModels;
    
    public $followUpData;
    /**
     * Create a new message instance.
     */
    public function __construct($followUpData)
    {
        $this->followUpData = $followUpData;
    }

    public function build()
    {
        return $this->subject('Follow-Up Reminders')
            ->view('emails.followUp_reminders')
            ->with('followUpData', $this->followUpData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Follow Up Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.followUp_reminders',
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
