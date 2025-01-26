<?php

namespace App\Calendar\Services;

use App\Calendar\DTOs\CalendarDTO;
use App\Calendar\DTOs\DayDTO;
use App\Calendar\DTOs\WeekDTO;
use App\Reservation\Services\ReservationService;
use Carbon\CarbonInterface;

class CalendarService
{
    private ReservationService $reservationService;

    private CarbonInterface $start;

    private ?DayDTO $selectedDateDTO = null;

    public function __construct(
        public CarbonInterface $selectedDay,
    ) {
        $this->start = $selectedDay
            ->startOfMonth();

        $this->reservationService = resolve(ReservationService::class, [
            'month' => $this->start,
            'now' => now()
        ]);
    }

    public function get(): CalendarDTO
    {
        return new CalendarDTO(
            weeks: $this->getWeeks(),
        );
    }

    public function getSelectedDate(): ?DayDTO
    {
        return $this->selectedDateDTO;
    }

    private function getWeeks(): array
    {
        $weeksWithDays = [];

        $week = $this->start;

        while ($week->isSameMonth($this->start)) {
            $weeksWithDays[] = new WeekDTO(
                days: $this->getDaysInWeek($week),
            );

            $week = $week->addWeek();
        }

        return $weeksWithDays;
    }

    private function getDaysInWeek(
        CarbonInterface $week,
    ): array {
        $day = $week->startOfWeek();

        $days = [];

        while (
            $day->isSameWeek($week)
        ) {
            $availableTimesForDay = $this
                ->reservationService
                ->getAvailableTimes($day);

            $dayDTO = new DayDTO(
                date: $day,
                availableTimes: $availableTimesForDay,
                available: count($availableTimesForDay)
            );

            $dayDTO = $this->determinateSelectedDay($dayDTO);

            $days[] = $dayDTO;

            $day = $day->addDay();
        }

        return $days;
    }

    private function determinateSelectedDay(
        DayDTO $dayDTO
    ): DayDTO {
        if (
            $dayDTO->date->greaterThanOrEqualTo($this->selectedDay)
            && is_null($this->selectedDateDTO)
            && $dayDTO->available
        ) {
            $this->selectedDateDTO = $dayDTO;

            $dayDTO->selected = true;
        }

        return $dayDTO;
    }
}
