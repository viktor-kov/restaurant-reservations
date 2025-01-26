<?php

namespace App\Reservation\Actions;

use App\Models\Reservation;

class DeleteReservationAction
{
    public function handle(
        Reservation $reservation
    ): void {
        $reservation->delete();
    }
}
