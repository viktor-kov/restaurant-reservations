<?php

namespace App\Reservation\DTOs;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class AvailableTimeDTO
{
    public function __construct(
        public CarbonInterface $dateTime
    ) {

    }
}
