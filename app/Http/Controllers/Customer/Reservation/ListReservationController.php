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
                'user'
            ])
            ->where('user_id', auth()->id())
            ->orderBy('date')
            ->paginate(8);

        $maxSeatsPerTable = config('restaurant.max_seats_per_table');

        return view('customer.reservations.list', compact('reservations', 'maxSeatsPerTable'));
    }
}
