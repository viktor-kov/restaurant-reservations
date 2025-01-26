<x-app-layout>
    <x-success-message />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-[80vh]">
                <div class="p-6 text-gray-900">
                    <table class="table-fixed w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-slate-200">
                            <th class="border-b border-slate-300 p-2 font-semibold">{{ __('Date & Time') }}</th>
                            <th class="border-b border-slate-300 p-2 font-semibold">{{ __('Seats') }}</th>
                            <th class="border-b border-slate-300 p-2 font-semibold">{{ __('Name') }}</th>
                            <th class="border-b border-slate-300 p-2 font-semibold">{{ __('Notes') }}</th>
                            <th class="text-right border-b border-slate-300 p-2 font-semibold">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td class="border-b border-slate-300 p-2">{{ $reservation->date->format('d.m.Y H:i') }}</td>
                                    <td class="border-b border-slate-300 p-2">{{ $reservation->seats_count }} / {{ $maxSeatsPerTable }}</td>
                                    <td class="border-b border-slate-300 p-2">{{ $reservation->user->name }}</td>
                                    <td class="border-b border-slate-300 p-2">{{ $reservation->notes }}</td>
                                    <td class="border-b border-slate-300 p-2">
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
                    @if($reservations->isEmpty())
                        <div
                            class="flex justify-center h-full items-center mt-16"
                        >
                            <a
                                href="{{ route('homepage') }}"
                                type="button"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg"
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
