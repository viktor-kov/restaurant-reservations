<?php

namespace App\Calendar\DTOs;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DayDTO
{
    public function __construct(
        public CarbonInterface $date,
        public array $availableTimes,
        public bool $available,
        public bool $selected = false
    ) {
        //
    }
}
