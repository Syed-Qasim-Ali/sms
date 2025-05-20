<?php

namespace App\Mail;

use App\Models\Truck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TruckInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $truck;
    /**
     * Create a new message instance.
     */
    public function __construct(Truck $truck)
    {
        $this->truck = $truck;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Truck Invitation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('Truck Invitation')
            ->view('backend.emails.truck_invitation', ['truck' => $this->truck]);
    }
}
