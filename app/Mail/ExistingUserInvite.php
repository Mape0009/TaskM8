<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExistingUserInvite extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array */
    public $event;

    public function __construct(array $event)
    {
        $this->event = $event;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Invitation til begivenhed');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.existingInvite',
            with: ['event' => $this->event]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


