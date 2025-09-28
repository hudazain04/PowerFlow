<?php

namespace App\Mail;

use App\Types\GeneratorRequests;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerRequestStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $status,
        protected string $generator_name,
        protected ?string $admin_notes = null
    )
    {
        //
    }
    public function via($notifiable): array
    {
        return ['mail'];
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === GeneratorRequests::APPROVED
            ? __('Customer Request Approved')
            : __('Customer Request Rejected');

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.customerRequest.status',
            with: [
            'status' => $this->status,
            'generator_name'=>$this->generator_name,
            'admin_notes' => $this->admin_notes,
        ]);
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
