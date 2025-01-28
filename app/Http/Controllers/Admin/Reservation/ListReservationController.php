<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reservation\ListReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ListReservationController extends Controller
{
    public function __invoke(
        ListReservationRequest $request
    ): View {
        Gate::authorize('view-any', Reservation::class);

        $reservations = Reservation::query()
            ->with([
                'user',
            ])
            ->orderBy('date')
            ->whereDate('date', $request->validated('date'))
            ->paginate(10);

        $maxSeatsPerTable = config('restaurant.max_seats_per_table');

        return view('admin.reservations.list', compact('reservations', 'maxSeatsPerTable'));
    }
}
