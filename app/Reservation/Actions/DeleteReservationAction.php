<?php

namespace App\Reservation\Actions;

use App\Events\ReservationDeletedEvent;
use App\Models\Reservation;

class DeleteReservationAction
{
    public function handle(
        Reservation $reservation
    ): void {
        $reservation->delete();

        event(
            new ReservationDeletedEvent(
                $reservation->id
            )
        );
    }
}
