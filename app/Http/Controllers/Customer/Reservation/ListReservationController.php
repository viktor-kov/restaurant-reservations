<?php

namespace App\Http\Controllers\Customer\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListReservationController extends Controller
{
    public function __invoke(
        Request $request
    ): View {
        $reservations = Reservation::query()
            ->with([
                'user',
            ])
            ->where('user_id', auth()->id())
            ->whereDate('date', '>=', today()->format('Y-m-d'))
            ->orderBy('date')
            ->paginate(config('tables.default_pagination'));

        $maxSeatsPerTable = config('restaurant.max_seats_per_table');

        return view('customer.reservations.list', compact('reservations', 'maxSeatsPerTable'));
    }
}
