<?php

namespace App\Http\Controllers\Customer\Reservation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListReservationController extends Controller
{
    public function __invoke(
        Request $request
    ): View {
        $reservations = auth()
            ->user()
            ->reservations;

        $maxSeatsPerTable = config('restaurant.max_seats_per_table');

        return view('customer.reservations.list', compact('reservations', 'maxSeatsPerTable'));
    }
}
