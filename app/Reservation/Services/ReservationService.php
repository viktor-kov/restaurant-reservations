<?php

namespace App\Reservation\Services;

use App\Models\Reservation;
use App\Reservation\DTOs\AvailableTimeDTO;
use Carbon\CarbonInterface;

class ReservationService
{
    public function __construct(
        private CarbonInterface $month,
        private CarbonInterface $now,
    ) {
        //
    }

    public function getAvailableTimes(
        CarbonInterface $date,
    ): array {
        if (! $date->isSameMonth($this->month)) {
            return [];
        }

        if ($date->lessThan($this->now->startOfDay())) {
            return [];
        }

        if (
            ! config('restaurant.weekends')
            && $date->isWeekend()
        ) {
            return [];
        }

        $availableTime = $this->setTimeTo(
            $date,
            config('restaurant.first_available_time'),
        );

        $lastAvailableTime = $this->setTimeTo(
            $date,
            config('restaurant.last_available_time'),
        );

        $reservationsForDate = $this->getReservationsForDate(
            $date
        );

        $availableTimes = [];

        while ($availableTime->lessThan($lastAvailableTime)) {
            if ($availableTime->lessThan($this->now)) {
                $availableTime = $availableTime->addHour();

                continue;
            }

            $reservationsForTime = $reservationsForDate[$availableTime->format('H:i')] ?? [];

            if (count($reservationsForTime) >= config('restaurant.tables_count')) {
                $availableTime = $availableTime->addHour();

                continue;
            }

            $availableTimes[] = new AvailableTimeDTO(
                dateTime: $availableTime,
            );

            $availableTime = $availableTime->addHour();
        }

        return $availableTimes;
    }

    private function setTimeTo(
        CarbonInterface $date,
        string $time
    ): CarbonInterface {
        return $date->setTimeFromTimeString($time);
    }

    public function getReservationsForDate(
        CarbonInterface $date,
    ): array {
        $reservations = Reservation::query()
            ->whereDate('date', $date->format('Y-m-d'))
            ->get();

        return $reservations->map(function (Reservation $reservation) {
            return [
                'time' => $reservation->date->format('H:i')
            ];
        })
            ->groupBy('time')
            ->toArray();
    }
}
