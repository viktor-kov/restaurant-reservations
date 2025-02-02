<x-app-layout>
    <x-success-message />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-900">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <form action="{{ route('admin.reservations.list') }}">
                    <div class="mb-4">
                        <h1 class="font-semibold">
                            {{ __('Filters') }}
                        </h1>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="mb-2">
                                <label for="date">
                                    {{ __('Date') }}
                                </label>
                            </div>
                            <div>
                                <input
                                    type="date"
                                    name="date"
                                    class="w-full border-gray-400 rounded-lg"
                                    value="{{ old('date', request()->get('date', today()->format('Y-m-d'))) }}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg"
                            >
                                {{ __('Sarch') }}
                            </button>
                        </div>
                        @if(! empty(request()->query()))
                            <div>
                                <a href="{{ route('admin.reservations.list') }}">
                                    <small>
                                        {{ __('Clear') }}
                                    </small>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-900">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col justify-between min-h-[80vh]">
                    <div class="overflow-auto h-full">
                        <table class="table-auto overflow-scroll w-full text-left border-collapse">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b border-white p-4 font-semibold">{{ __('Date & Time') }}</th>
                                <th class="border-b border-white p-4 font-semibold">{{ __('Seats') }}</th>
                                <th class="border-b border-white p-4 font-semibold">{{ __('Name') }}</th>
                                <th class="border-b border-white p-4 font-semibold">{{ __('Notes') }}</th>
                                <th class="text-right border-b border-white p-4 font-semibold">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $reservation)
                                <tr class="bg-white">
                                    <td class="border-b border-white p-4">{{ $reservation->date->format('d.m.Y H:i') }}</td>
                                    <td class="border-b border-white p-4">{{ $reservation->seats_count }} / {{ $maxSeatsPerTable }}</td>
                                    <td class="border-b border-white p-4">{{ $reservation->user->name }}</td>
                                    <td class="border-b border-white p-4">{{ $reservation->notes }}</td>
                                    <td class="border-b border-white p-4">
                                        <div class="flex justify-end gap-4">
                                            <div>
                                                <a
                                                    href="{{ route('admin.reservations.show', ['reservation' => $reservation]) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-white"
                                                >
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                            @if($reservation->date->greaterThan(now()))
                                                <div>
                                                    <form action="{{ route('admin.reservations.delete', ['reservation' => $reservation]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <x-danger-button
                                                            type="submit"
                                                            onclick="return confirm('{{ __('Are you sure?') }}')"
                                                        >
                                                            <i class="fa-solid fa-trash"></i>
                                                        </x-danger-button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">
                        {!! $reservations->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
