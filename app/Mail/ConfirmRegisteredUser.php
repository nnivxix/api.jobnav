<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmRegisteredUser extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $signedUrl)
    {
        //
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Confirm Registered User',
        );
    }


    public function content()
    {
        return new Content(
            markdown: 'mail.registered-user',
            with: [
                'url' => $this->signedUrl
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
