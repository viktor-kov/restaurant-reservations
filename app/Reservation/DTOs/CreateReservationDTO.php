<?php

namespace App\Reservation\DTOs;

use App\Models\User;
use Carbon\CarbonInterface;

class CreateReservationDTO
{
    public function __construct(
        public CarbonInterface $reservationDate,
        public int $seatsCount,
        public string $notes,
        public User $user
    ) {}
}
