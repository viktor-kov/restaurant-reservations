<?php

namespace App\Calendar\DTOs;

class WeekDTO
{
    /**
     * @param  DayDTO[]  $days
     */
    public function __construct(
        public array $days
    ) {
        //
    }
}
