<?php

namespace App\Reservation\DTOs;

use Carbon\Carbon;

class ReservationDTO
{
    public function __construct(
        public Carbon $date,
        public array $availableTimes = [],
        public bool $available = false,
        public bool $selected = false
    ) {

    }
}
