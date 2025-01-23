<?php

namespace App\Reservation\DTOs;

use App\Models\User;
use Carbon\Carbon;

class CreateReservationDTO
{
    public function __construct(
        public Carbon $reservationDate,
        public int $seatsCount,
        public string $notes,
        public User $user
    ) {

    }
}
