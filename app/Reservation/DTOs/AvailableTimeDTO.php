<?php

namespace App\Reservation\DTOs;

use Carbon\CarbonInterface;

class AvailableTimeDTO
{
    public function __construct(
        public CarbonInterface $dateTime
    ) {}
}
