<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationDeletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Reservation $reservation,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Your reservation has been deleted'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reservation.deleted',
        );
    }
}
