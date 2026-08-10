<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BeneficiaryInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $messageText;
    public $manager;
    public $inviteUrl;

    public function __construct($application, $messageText, $manager, $inviteUrl)
    {
        $this->application = $application;
        $this->messageText = $messageText;
        $this->manager = $manager;
        $this->inviteUrl = $inviteUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to complete beneficiary intake',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.beneficiary_invite',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
