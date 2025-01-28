<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $reservationId,
    ) {
        //
    }
}
