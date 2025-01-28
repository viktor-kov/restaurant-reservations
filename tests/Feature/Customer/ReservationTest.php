<?php

namespace Tests\Feature\Customer;

use App\Models\Reservation;
use App\Models\User;
use App\Reservation\Actions\CreateReservationAction;
use App\Reservation\DTOs\CreateReservationDTO;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_logged_in_user_can_not_see_reservations(): void
    {
        $response = $this
            ->get(route('customer.reservations.list'));

        $response
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_customer_can_see_reservations(): void
    {
        $user = User::factory()
            ->create();

        $response = $this
            ->actingAs($user)
            ->get(route('customer.reservations.list'));

        $response
            ->assertOk();
    }

    public function test_logged_in_customer_can_delete_own_reservation(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                1,
                '',
                $user,
            )
        );

        $reservation = Reservation::query()
            ->first();

        $response = $this
            ->delete(route('customer.reservations.delete', $reservation));

        $response
            ->assertRedirect();

        $this->assertSoftDeleted('reservations');
    }

    public function test_logged_in_customer_can_not_delete_not_own_reservation(): void
    {
        $userOne = User::factory()
            ->create();

        $userTwo = User::factory()
            ->create();

        $this->actingAs($userTwo);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                1,
                '',
                $userOne,
            )
        );

        $reservation = Reservation::query()
            ->where('user_id', $userOne->id)
            ->first();

        $response = $this
            ->delete(route('customer.reservations.delete', $reservation));

        $response
            ->assertForbidden();
    }
}
