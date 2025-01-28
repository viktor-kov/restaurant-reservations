<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="text-gray-900">
                    <table class="table-fixed w-full text-left border-collapse">
                        <tbody class="border border-collapse border-slate-300">
                            <tr>
                                <td class="bg-gray-100 border border-slate-300 p-4 font-semibold">
                                    {{ __('Date & Time') }}
                                </td>
                                <td class="p-4 text-right border border-slate-300">
                                    {{ $reservation->date->format('d.m.Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray-100 border border-slate-300 p-4 font-semibold">
                                    {{ __('Seats') }}
                                </td>
                                <td class="p-4 text-right border border-slate-300">
                                    {{ $reservation->seats_count }} / {{ $maxSeatsPerTable }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray-100 border border-slate-300 p-4 font-semibold">
                                    {{ __('Name') }}
                                </td>
                                <td class="p-4 text-right border border-slate-300">
                                    {{ $reservation->user->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-gray-100 border border-slate-300 p-4 font-semibold">
                                    {{ __('Notes') }}
                                </td>
                                <td class="p-4 text-right border border-slate-300">
                                    {{ $reservation->notes }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
