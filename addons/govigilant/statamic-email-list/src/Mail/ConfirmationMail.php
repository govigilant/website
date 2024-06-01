<?php

namespace Vigilant\EmailList\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Vigilant\EmailList\Models\Entry;

class ConfirmationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected Entry $entry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm your E-mail address',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emaillist::mail.confirm',
            with: [
                'url' => URL::signedRoute('email-list.confirm', ['entry' => $this->entry])
            ]
        );
    }
}
