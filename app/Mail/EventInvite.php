<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The event data for the email.
     */
    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Invite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mailForm',
            with: ['event' => $this->event],
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
    /**
     * Send the event invite email to a recipient.
     *
     * @param string $recipientEmail
     * @param mixed $eventData
     * @return void
     */
    public static function sendNewUserMail($recipientEmail, $eventData)
    {
        \Mail::to($recipientEmail)->send(new self($eventData));
    }

    public static function sendExistingUserMail($recipientEmail, $eventData)
    {
        \Mail::to($recipientEmail)->send(new self($eventData));
    }
}
