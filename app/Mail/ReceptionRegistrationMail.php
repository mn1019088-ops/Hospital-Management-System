<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reception;

class ReceptionRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reception;
    public $loginUrl;

    public function __construct(Reception $reception)
    {
        $this->reception = $reception;
        $this->loginUrl = route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to MediCare Hospital - Reception Staff Registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reception-registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}