<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@redcard.local'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => '123456',
                'role' => 'admin',
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'User',
                'username' => 'user',
                'password' => '123456',
                'role' => 'user',
            ],
        );
    }
}