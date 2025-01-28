<?php

namespace Tests\Feature\Admin;

use App\Models\Reservation;
use App\Models\User;
use App\Reservation\Actions\CreateReservationAction;
use App\Reservation\DTOs\CreateReservationDTO;
use App\Role\Enums\RoleEnum;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    public function test_not_logged_in_user_can_not_see_reservations(): void
    {
        $response = $this
            ->get(route('admin.reservations.list'));

        $response
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_customer_can_not_see_reservations(): void
    {
        $user = User::factory([
            'role' => RoleEnum::CUSTOMER->value,
        ])
            ->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.reservations.list'));

        $response
            ->assertRedirect(route('homepage'));
    }

    public function test_logged_in_admin_can_see_reservations(): void
    {
        $user = User::factory([
            'role' => RoleEnum::ADMIN->value,
        ])
            ->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.reservations.list'));

        $response
            ->assertOk();
    }

    public function test_logged_in_admin_can_delete_reservation(): void
    {
        $customer = User::factory()
            ->create();

        $admin = User::factory([
            'role' => RoleEnum::ADMIN->value,
        ])
            ->create();

        $this->actingAs($admin);

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
                $customer,
            )
        );

        $reservation = Reservation::query()
            ->where('user_id', $customer->id)
            ->first();

        $response = $this
            ->delete(route('admin.reservations.delete', $reservation));

        $this->assertSoftDeleted('reservations');

        $response
            ->assertRedirect();
    }
}
