<?php

namespace Tests\Feature\Calendar;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_is_not_visible_for_logged_in_user(): void
    {
        $response = $this
            ->get(route('homepage'));

        $response
            ->assertSee('Log in');
    }

    public function test_reservation_is_visible_for_logged_in_user(): void
    {
        $user = User::factory()
            ->create();

        $response = $this
            ->actingAs($user)
            ->get(route('homepage'));

        $response
            ->assertDontSee('Log in');
    }
}
