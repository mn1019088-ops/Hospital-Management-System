<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Patient;

class PatientRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $loginUrl;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
        $this->loginUrl = route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to MediCare Hospital - Registration Successful',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.patient-registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}