<?php

namespace Database\Factories\User;

use App\Models\User\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Entity\User>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement([Profile::MALE, Profile::FEMALE]);
        return [
            'first_name' => $gender === Profile::MALE ? fake()->firstNameMale : fake()->firstNameFemale,
            'last_name' => fake()->lastName,
            'birth_date' => fake()->date('Y-m-d H:i:s'),
            'gender' => $gender,
            'address' => fake()->address,
        ];
    }
}
