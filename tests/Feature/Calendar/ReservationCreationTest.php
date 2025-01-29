<?php

namespace Tests\Feature\Calendar;

use App\Models\User;
use App\Reservation\Actions\CreateReservationAction;
use App\Reservation\DTOs\CreateReservationDTO;
use App\Reservation\Exceptions\UnableToCreateReservationException;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_can_be_created(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(12, 0, 0)
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

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_only_1_reservations_on_same_time_can_be_made(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        $tablesCount = 1;

        config()
            ->set('restaurant.tables_count', $tablesCount);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(12, 0, 0)
        );

        $createReservationAction = new CreateReservationAction;

        for ($i = 0; $i <= $tablesCount; $i++) {
            $createReservationAction->handle(
                new CreateReservationDTO(
                    now(),
                    1,
                    '',
                    $user,
                )
            );
        }

        $this->assertDatabaseCount('reservations', config('restaurant.tables_count'));
    }

    public function test_reservation_can_not_be_created_in_past_month(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $dateInPast = now()
            ->subHour();

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                $dateInPast,
                1,
                '',
                $user,
            )
        );
    }

    public function test_reservation_can_not_be_created_in_next_month(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $dateInFuture = now()
            ->addMonth();

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                $dateInFuture,
                1,
                '',
                $user,
            )
        );
    }

    public function test_reservation_seats_can_not_be_lower_than_one(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                0,
                '',
                $user,
            )
        );
    }

    public function test_reservation_seats_can_not_be_lower_than_set(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        config()
            ->set('restaurant.max_seats_per_table', 2);

        CarbonImmutable::setTestNow(
            now()
                ->subDays(now()->dayOfWeek - 1)
                ->setTime(15, 0, 0)
        );

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                config('restaurant.max_seats_per_table') + 1,
                '',
                $user,
            )
        );
    }

    public function test_reservation_can_not_be_created_at_weekends(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->previousWeekendDay()
                ->setTime(15, 0, 0)
        );

        config()
            ->set('restaurant.weekends', false);

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                1,
                '',
                $user,
            )
        );
    }

    public function test_reservation_can_be_created_at_weekends(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        CarbonImmutable::setTestNow(
            now()
                ->previousWeekendDay()
                ->setTime(15, 0, 0)
        );

        config()
            ->set('restaurant.weekends', true);

        $createReservationAction = new CreateReservationAction;

        $createReservationAction->handle(
            new CreateReservationDTO(
                now(),
                1,
                '',
                $user,
            )
        );

        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_reservation_can_not_be_created_before_first_available_time(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->previousWeekendDay()
                ->setTimeFromTimeString(config('restaurant.first_available_time'))
                ->subHour()
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
    }

    public function test_reservation_can_not_be_created_after_last_available_time(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user);

        $this->expectException(UnableToCreateReservationException::class);

        CarbonImmutable::setTestNow(
            now()
                ->previousWeekendDay()
                ->setTimeFromTimeString(config('restaurant.last_available_time'))
                ->addHour()
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
    }
}
