<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Doctor;

class DoctorRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $loginUrl;

    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->loginUrl = route('login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to MediCare Hospital - Doctor Registration Successful',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.doctor-registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}