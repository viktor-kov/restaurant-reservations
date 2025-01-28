<div>
    @error('generalError')
    <div
        class="p-4 bg-red-600 text-white mb-4 rounded-lg"
    >
        {{ $message }}
    </div>
    @enderror

    <div class="flex flex-col lg:flex-row text-white">
        <div
            class="w-full lg:w-1/2 border-b lg:border-r lg:border-b-0 lg:mr-4 border-gray-200"
        >
            <div
                class="mb-4"
            >
                <h1
                    class="text-xl font-semibold text-center"
                >
                    {{ $this->getSelectedDate()->date->format('F Y') }}
                </h1>
            </div>
            <div
                class="w-full grid grid-cols-7 gap-4 mb-8"
            >
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Mo') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Tu') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('We') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Th') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Fr') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Sa') }}
                </div>
                <div
                    class="h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center"
                >
                    {{ __('Su') }}
                </div>
            </div>
            @foreach($this->getCalendar()->weeks as $week)
                <div
                    class="w-full grid grid-cols-7 gap-4 mb-4 text-gray-500"
                >
                    @foreach($week->days as $day)
                        <button
                            type="button"
                            @class(['h-8 lg:h-10 w-8 lg:w-10 font-semibold flex items-center justify-center rounded-lg text-white', 'bg-gray-100 !text-gray-500 disabled' => ! $day->available, 'bg-green-600 text-white' => $day->selected])
                            @if($day->available)
                                wire:click="selectDate('{{ $day->date->format('Y-m-d') }}')"
                            @endif
                            @disabled(! $day->available)
                        >
                            {{ $day->date->format('d') }}
                        </button>
                    @endforeach
                </div>
            @endforeach
            @error('selectedDate')
            <small
                class="text-red-600"
            >
                {{ $message }}
            </small>
            @enderror
        </div>
        <div
            class="w-full lg:w-1/2 mt-4 lg:mt-0"
        >
            @auth()
                <div>
                    <h1
                        class="text-xl font-semibold text-center"
                    >
                        {{ __('Select Seats & Time') }}
                    </h1>
                </div>
                <hr class="my-4 border-gray-200">
                <div
                    class="grid grid-cols-2 gap-4"
                >
                    <div>
                        <h3
                            class="text-lg font-semibold"
                        >
                            {{ __('Seats') }}
                        </h3>
                    </div>
                    <div
                        class="flex justify-end text-gray-900"
                    >
                        <div class="flex gap-4">
                            <button
                                type="button"
                                class="h-8 w-8 flex items-center justify-center bg-gray-100 border border-gray-400 rounded-lg"
                                wire:click="removeSeats"
                            >
                                -
                            </button>

                            <div>
                                <input
                                    type="number"
                                    class="h-8 w-20 flex items-center justify-center  border border-gray-400 rounded-lg"
                                    readonly
                                    wire:model.debounce="seatsCount"
                                >
                            </div>

                            <button
                                type="button"
                                class="h-8 w-8 flex items-center justify-center bg-gray-100 border border-gray-400 rounded-lg"
                                wire:click="addSeats"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="my-4 border-gray-200">
                <div
                    class="mb-4"
                >
                    <div>
                        <h3
                            class="text-lg font-semibold"
                        >
                            {{ __('Time') }}
                        </h3>
                    </div>
                    <div>
                        <select
                            class="w-full rounded-lg text-gray-900"
                            wire:model="selectedTime"
                        >
                            <option
                                hidden
                            >
                                {{ __('Select Time') }}
                            </option>
                            @foreach($this->getSelectedDate()->availableTimes as $availableTime)
                                <option
                                    value="{{ $availableTime->dateTime->format('H:i') }}"
                                >
                                    {{ $availableTime->dateTime->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedTime')
                        <small
                            class="text-red-600"
                        >
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>
                <hr class="my-4 border-gray-200">
                <div class="mb-4">
                    <div>
                        <h3
                            class="text-lg font-semibold"
                        >
                            {{ __('Notes') }}
                        </h3>
                    </div>
                    <div>
                    <textarea
                        class="w-full border-gray-400 rounded-lg text-gray-900"
                        rows="3"
                        wire:model="notes"
                    ></textarea>
                    </div>
                    @error('notes')
                    <small
                        class="text-red-600"
                    >
                        {{ $message }}
                    </small>
                    @enderror
                </div>
                <div
                    class="flex justify-center"
                >
                    <button
                        type="button"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg"
                        wire:click="createReservation"
                    >
                        {{ __('Create Reservation') }}
                    </button>
                </div>
            @endauth

            @guest
                <div
                    class="flex justify-center h-full items-center"
                >
                    <a
                        href="{{ route('login') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg"
                    >
                        {{ __('Log in') }}
                    </a>
                </div>
            @endguest
        </div>
    </div>
</div>
