<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Role\Enums\RoleEnum;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'role' => RoleEnum::ADMIN->value,
            'email' => 'admin@admin.com',
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::factory()
                ->has(
                    Reservation::factory()->count(10),
                    'reservations'
                )
                ->create();
        }
    }
}
