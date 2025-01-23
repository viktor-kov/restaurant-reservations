<?php

namespace App\Reservation\Actions;

use App\Models\Reservation;
use App\Reservation\DTOs\CreateReservationDTO;

class CreateReservationAction
{
    public function handle(
        CreateReservationDTO $createReservationDTO
    ): Reservation {
        return Reservation::create([
            'seats_count' => $createReservationDTO->seatsCount,
            'date' => $createReservationDTO->reservationDate->format('Y-m-d H:i:s'),
            'notes' => $createReservationDTO->notes,
            'user_id' => $createReservationDTO->user->id,
        ]);
    }
}
