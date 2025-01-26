<?php

namespace App\Calendar\DTOs;

class CalendarDTO
{
    /**
     * @param WeekDTO[] $weeks
     */
    public function __construct(
        public array $weeks = []
    ) {
        //
    }
}
