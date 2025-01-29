<?php

namespace App\Livewire;

use App\Calendar\DTOs\CalendarDTO;
use App\Calendar\DTOs\DayDTO;
use App\Calendar\Services\CalendarService;
use App\Reservation\Actions\CreateReservationAction;
use App\Reservation\DTOs\CreateReservationDTO;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class CalendarComponent extends Component
{
    public ?string $selectedDate = null;

    public ?string $selectedTime = null;

    public int $seatsCount = 1;

    public string $notes = '';

    public bool $showSuccessMessage = false;

    private CalendarService $calendarService;

    private ?CalendarDTO $calendarDTO = null;

    private ?DayDTO $selectedDateDTO = null;

    public function mount(): void
    {
        $this->setSelectedDate(today());
    }

    public function boot(): void
    {
        $this->hideSuccessMessage();
    }

    public function rendering(): void
    {
        $this->calendarService = new CalendarService(
            CarbonImmutable::parse($this->selectedDate),
        );

        $this->calendarDTO = $this->calendarService->get();
        $this->selectedDateDTO = $this->calendarService->getSelectedDate();

        $this->selectedDate = $this->selectedDateDTO
            ->date
            ->format('Y-m-d');
    }

    public function render(): View
    {
        return view('livewire.calendar-component');
    }

    #[Computed]
    public function getCalendar(): CalendarDTO
    {
        return $this
            ->calendarDTO;
    }

    #[Computed]
    public function getSelectedDate(): DayDTO
    {
        return $this
            ->selectedDateDTO;
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

        $this->reset([
            'selectedTime',
        ]);
    }

    public function addSeats(): void
    {
        $this->seatsCount++;

        if ($this->seatsCount > config('restaurant.max_seats_per_table')) {
            $this->seatsCount = config('restaurant.max_seats_per_table');
        }
    }

    public function removeSeats(): void
    {
        $this->seatsCount--;

        if ($this->seatsCount < 1) {
            $this->seatsCount = 1;
        }
    }

    public function createReservation(): void
    {
        $this->validate([
            'selectedDate' => [
                'required',
                'date',
            ],
            'selectedTime' => [
                'required',
                'date_format:H:i',
            ],
            'seatsCount' => [
                'required',
                'integer',
                'min:1',
                'max:'.config('restaurant.max_seats_per_table'),
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            DB::beginTransaction();

            $reservationDate = CarbonImmutable::parse("{$this->selectedDate} {$this->selectedTime}");

            $createReservationAction = new CreateReservationAction;

            $createReservationAction->handle(
                new CreateReservationDTO(
                    $reservationDate,
                    $this->seatsCount,
                    $this->notes,
                    auth()->user(),
                )
            );

            $this->reset(
                'selectedDate',
                'selectedTime',
                'seatsCount',
                'notes',
            );

            $this->setSelectedDate(today());

            $this->showSuccessMessage();

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();

            $this->addError(
                'generalError',
                __('Unable to create a reservation.')
            );

            report($throwable);
        }
    }

    private function setSelectedDate(
        CarbonInterface $selectedDate
    ): void {
        $this->selectedDate = $selectedDate
            ->format('Y-m-d');
    }

    private function showSuccessMessage(): void
    {
        $this->showSuccessMessage = true;
    }

    private function hideSuccessMessage(): void
    {
        $this->showSuccessMessage = false;
    }
}
