<?php

namespace App\Livewire\Components;

use App\Reservation\Actions\CreateReservationAction;
use App\Reservation\DTOs\CreateReservationDTO;
use App\Reservation\DTOs\ReservationDTO;
use App\Reservation\Services\ReservationAvailabilityService;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class PickReservationComponent extends Component
{
    public ?string $selectedDate = null;
    public ?string $selectedTime = null;
    public int $seatsCount = 1;
    public string $notes = '';

    private ReservationAvailabilityService $reservationAvailabilityService;

    // https://dribbble.com/shots/23193665-Vision-Pro-Reservations-system-Lazarev

    public function mount(): void
    {
        $this->setSelectedDate(today());
    }

    public function rendering(): void
    {
        $this->reservationAvailabilityService = new ReservationAvailabilityService(
            Carbon::parse($this->selectedDate),
        );
    }

    public function render(): View
    {
        return view('livewire.components.pick-reservation-component');
    }

    #[Computed]
    public function getWeeks(): array
    {
        return $this
            ->reservationAvailabilityService
            ->getWeeks();
    }

    #[Computed]
    public function getSelectedDate(): ReservationDTO
    {
        return $this
            ->reservationAvailabilityService
            ->getSelectedDate();
    }

    public function selectDate(
        string $date
    ): void {
        try {
            $carbon = Carbon::parse($date);

            if (! $carbon->isCurrentMonth()) {
                $this->addError(
                    'generalError',
                    __('Please select a valid date.')
                );

                return;
            }
        } catch (Throwable $throwable) {
            $this->addError(
                'generalError',
                __('Please select a valid date.')
            );

            report($throwable);

            return;
        }

        $this->selectedDate = $date;
    }

    public function addSeats(): void
    {
        $this->seatsCount++;

        if ($this->seatsCount > config('restaurant.max_seats_per_table')) {
            $this->seatsCount =  config('restaurant.max_seats_per_table');
        }
    }

    public function removeSeats(): void
    {
        $this->seatsCount--;

        if ($this->seatsCount < 1) {
            $this->seatsCount =  1;
        }
    }

    public function createReservation(): void
    {
        $this->validate([
            'selectedDate' => [
                'required',
                'date'
            ],
            'selectedTime' => [
                'required',
                'date_format:H:i'
            ],
            'seatsCount' => [
                'required',
                'integer',
                'min:1',
                'max:' . config('restaurant.max_seats_per_table')
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        try {
            $reservationDate = Carbon::parse("{$this->selectedDate} {$this->selectedTime}");

            $createReservationAction = new CreateReservationAction();

            $createReservationAction->handle(
                new CreateReservationDTO(
                    $reservationDate,
                    $this->seatsCount,
                    $this->notes,
                    auth()->user(),
                )
            );

            $this->reset(
                'selectedTime',
                'selectedTime',
                'notes',
            );

            $this->setSelectedDate(today());
        } catch (Throwable $throwable) {
            $this->addError(
                'generalError',
                __('Unable to create a reservation.')
            );

            report($throwable);
        }
    }

    private function setSelectedDate(
        Carbon $selectedDate
    ): void {
        $this->selectedDate = $selectedDate
            ->format('Y-m-d');
    }
}
