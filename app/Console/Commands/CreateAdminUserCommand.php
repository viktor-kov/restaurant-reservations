<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Role\Enums\RoleEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUserCommand extends Command
{
    protected $signature = 'app:create-admin-user-command {name} {email} {password?}';

    protected $description = 'Creates admin user.';

    public function handle(): int
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $password ??= Str::password();

        $user = User::create([
            'role' => RoleEnum::ADMIN->value,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Created admin user {$user->name} with password: {$password}");

        return Command::SUCCESS;
    }
}
