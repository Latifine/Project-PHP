<?php

namespace App\Mail;

use App\Enum\EmailType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InfoToAllUsers extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $object;
    public EmailType $emailType;
    public $subject;
    public $description;


    /**
     * Create a new message instance.
     */
    public function __construct($user, $object, EmailType $emailType, $subject, $description)
    {
        $this->user = $user;
        $this->object = $object;
        $this->emailType = $emailType;
        $this->subject = $subject;
        $this->description = $description;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(EmailType::EMAIL_ADDRESS->value, EmailType::EMAIL_NAME->value),
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Use the value of the enum to select the correct view
        $markdownView = 'emails.' . $this->emailType->value;

        return new Content(
            markdown: $markdownView,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
