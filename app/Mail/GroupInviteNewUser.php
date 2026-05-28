<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GroupInviteNewUser extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array */
    public $group;

    public function __construct(array $group)
    {
        $this->group = $group;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Invitation til gruppe');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.groupInviteNewUser',
            with: ['group' => $this->group]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
