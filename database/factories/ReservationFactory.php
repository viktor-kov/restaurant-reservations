<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this
            ->faker
            ->dateTimeBetween('-1 week')
            ->format('Y-m-d');

        $times = [
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00',
        ];

        $time = $times[array_rand($times)];

        $dateTime = "{$date} {$time}";

        return [
            'seats_count' => $this->faker->numberBetween(1, 5),
            'notes' => $this->faker->sentence(),
            'date' => $dateTime,
        ];
    }
}
