<?php

namespace App\Reservation\Services;

use App\Models\Reservation;
use App\Reservation\DTOs\AvailableTimeDTO;
use App\Reservation\DTOs\ReservationDTO;
use Carbon\Carbon;

class ReservationAvailabilityService
{
    public function __construct(
        private Carbon $selectedDate
    ) {
        //
    }

    private bool $dayIsSelected = false;

    public function getWeeks(): array
    {
        $date = now()
            ->startOfMonth();

        $weeks = [];
        $weekIndex = 0;

        if (! $date->isMonday()) {
            $date = $date
                ->subDays($date->dayOfWeek() - 1);
        }

        while (
            $date->isLastMonth()
            || $date->isCurrentMonth()
        ) {
            $isAvailable = $this->isAvailableDay(
                $date,
            );

            $selected = false;

            if (
                $isAvailable
                && ! $this->dayIsSelected
                && $date->greaterThanOrEqualTo($this->selectedDate)
            ) {
                $selected = true;

                if ($date->greaterThan($this->selectedDate)) {
                    $this->selectedDate = $date->copy()
                        ->addDay();
                }

                $this->dayIsSelected = true;
            }

            $weeks[$weekIndex][] = new ReservationDTO(
                date: $date->clone(),
                availableTimes: [],
                available: $isAvailable,
                selected:$selected
            );

            if ($date->isSunday()) {
                $weekIndex++;
            }

            $date->addDay();
        }

        return $weeks;
    }

    private function getAvailableTimesForDay(Carbon $date): array
    {
        $availableTimes = [];

        $time = $date
            ->clone()
            ->setTimeFromTimeString(config('restaurant.first_available_time'));

        $endTime = $date
            ->clone()
            ->setTimeFromTimeString(config('restaurant.last_available_time'));

        $reservationsForThisDay = Reservation::query()
            ->select([
                'date'
            ])
            ->whereDate('date', $date->format('Y-m-d'))
            ->get();

        while ($time->lt($endTime)) {
            if (
                ! $reservationsForThisDay->contains('date', $time->format('Y-m-d H:i:s'))
            ) {
                if ($time->lessThan(now())) {
                    $time->addHour();

                    continue;
                }

                $availableTimes[] = new AvailableTimeDTO(
                    dateTime: $time->clone()
                );
            }

            $time->addHour();
        }

        return $availableTimes;
    }

    public function getSelectedDate(): ReservationDTO
    {
        return new ReservationDTO(
            date: $this->selectedDate,
            availableTimes: $this->getAvailableTimesForDay($this->selectedDate)
        );
    }

    private function isAvailableDay(
        Carbon $day,
    ): bool {
        if (! $day->isCurrentMonth()) {
            return false;
        }

        if ($day->lessThan(today())) {
            return false;
        }

        $isAvailable = true;

        if (! config('restaurant.weekends')) {
            $isAvailable = $day->isWeekday();
        }

        if ($isAvailable) {
            $isAvailable = (bool) count($this->getAvailableTimesForDay($day));
        }

        return $isAvailable;
    }
}
