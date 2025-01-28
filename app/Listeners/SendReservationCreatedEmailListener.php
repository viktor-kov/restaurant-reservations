<?php

namespace App\Listeners;

use App\Events\ReservationCreatedEvent;
use App\Mail\ReservationCreatedEmail;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedEmailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationCreatedEvent $event): void
    {
        $reservation = Reservation::query()
            ->with([
                'user',
            ])
            ->find(
                $event->reservationId
            );

        if (! $reservation) {
            return;
        }

        Mail::to(
            $reservation->user->email
        )
            ->send(
                new ReservationCreatedEmail(
                    $reservation
                )
            );
    }
}
