<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ShowReservationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Reservation $reservation
    ): View {
        Gate::authorize('view', $reservation);

        $reservation
            ->load('user');

        $maxSeatsPerTable = config('restaurant.max_seats_per_table');

        return view('admin.reservations.show', compact('reservation', 'maxSeatsPerTable'));
    }
}
