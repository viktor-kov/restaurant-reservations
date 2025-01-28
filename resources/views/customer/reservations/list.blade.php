<x-app-layout>
    <x-success-message />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-gray-900">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-[80vh]">
                <div class="h-full">
                    @if($reservations->isNotEmpty())
                        <div class="flex flex-col h-full justify-between">
                            <table class="table-fixed w-full text-left border-collapse">
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
                                                    <form action="{{ route('customer.reservations.delete', ['reservation' => $reservation]) }}" method="POST">
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="p-4">
                                {!! $reservations->links() !!}
                            </div>
                        </div>
                    @else
                        <div
                            class="relative h-full flex items-center justify-center"
                        >
                            <a
                                href="{{ route('homepage') }}"
                                type="button"
                                class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg"
                            >
                                {{ __('Create Reservation') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
