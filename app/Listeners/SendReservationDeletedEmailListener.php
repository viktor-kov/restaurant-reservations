<?php

namespace App\Listeners;

use App\Events\ReservationDeletedEvent;
use App\Mail\ReservationDeletedEmail;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationDeletedEmailListener implements ShouldQueue
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
    public function handle(ReservationDeletedEvent $event): void
    {
        $reservation = Reservation::withTrashed()
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
                new ReservationDeletedEmail(
                    $reservation
                )
            );
    }
}
