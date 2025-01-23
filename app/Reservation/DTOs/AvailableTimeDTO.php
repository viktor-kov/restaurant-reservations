<?php

namespace App\Reservation\DTOs;

use Carbon\Carbon;

class AvailableTimeDTO
{
    public function __construct(
        public Carbon $dateTime
    ) {

    }
}
