<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Reservation\Actions\DeleteReservationAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Throwable;

class DeleteReservationController extends Controller
{
    public function __construct(
        private readonly DeleteReservationAction $deleteReservationAction
    ) {
        //
    }

    public function __invoke(Reservation $reservation): RedirectResponse
    {
        Gate::authorize('delete', $reservation);

        try {
            $this
                ->deleteReservationAction
                ->handle($reservation);
        } catch (Throwable $throwable) {
            report($throwable);
        }

        return to_route('admin.reservations.list')
            ->with('success', __('Reservation was successfully deleted.'));
    }
}
