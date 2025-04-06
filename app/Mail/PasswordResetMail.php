<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;

    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Password Reset</title>
                </head>
                <body>
                    <p>Здравствуйте,</p>
                    <p>Мы получили запрос на сброс вашего пароля. Нажмите на ссылку ниже, чтобы сбросить свой пароль:</p>
                    <a href='" . env('DOMEN') . $this->resetLink . "'>Reset Password</a>
                    <p>Если вы не запрашивали сброс пароля, пожалуйста, проигнорируйте это письмо.</p>
                </body>
                </html>
            ",
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
