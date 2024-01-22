<?php

namespace Database\Seeders;

use App\Models\User\Profile;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(Profile::factory()->count(1))
            ->count(10)
            ->create();
    }
}
