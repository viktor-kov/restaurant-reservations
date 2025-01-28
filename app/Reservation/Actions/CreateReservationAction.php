<?php

namespace App\Reservation\Actions;

use App\Models\Reservation;
use App\Reservation\DTOs\AvailableTimeDTO;
use App\Reservation\DTOs\CreateReservationDTO;
use App\Reservation\Exceptions\UnableToCreateReservationException;
use App\Reservation\Services\ReservationService;

class CreateReservationAction
{
    /**
     * @throws UnableToCreateReservationException
     */
    public function handle(
        CreateReservationDTO $createReservationDTO
    ): Reservation {
        $reservationService = resolve(ReservationService::class);

        $availableTimes = $reservationService->getAvailableTimes(
            $createReservationDTO->reservationDate
        );

        $availableTimes = array_map(function (AvailableTimeDTO $availableTime) {
            return $availableTime->dateTime->format('H:i');
        }, $availableTimes);

        if (
            ! in_array(
                $createReservationDTO->reservationDate->format('H:i'),
                $availableTimes
            )
        ) {
            throw new UnableToCreateReservationException;
        }

        if (
            $createReservationDTO->seatsCount <= 0
            || $createReservationDTO->seatsCount > config('restaurant.max_seats_per_table')
        ) {
            throw new UnableToCreateReservationException;
        }

        return Reservation::create([
            'seats_count' => $createReservationDTO->seatsCount,
            'date' => $createReservationDTO->reservationDate->format('Y-m-d H:i:s'),
            'notes' => $createReservationDTO->notes,
            'user_id' => $createReservationDTO->user->id,
        ]);
    }
}
