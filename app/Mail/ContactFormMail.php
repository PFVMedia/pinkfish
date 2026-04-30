<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name: string, email: string, message: string}  $data
     */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact Form: '.$this->data['name'],
            replyTo: [$this->data['email']],
        );
    }

    public function build(): static
    {
        return $this->html(
            '<h2>Contact Form Submission</h2>'.
            '<p><strong>Name:</strong> '.e($this->data['name']).'</p>'.
            '<p><strong>Email:</strong> '.e($this->data['email']).'</p>'.
            '<p><strong>Message:</strong></p>'.
            '<p>'.nl2br(e($this->data['message'])).'</p>'
        );
    }
}
