<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->lastName();
        $gender = ['M', 'F'][rand(0, 1)];
        $firstname = $gender === 'M' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();

        return [
            'name' => $name,
            'firstname' => $firstname,
            'email' => sprintf('%s.%s@example.ch', strtolower($name), strtolower($firstname)),
            'gender' => $gender,
            'birthdate' => $this->faker->date(),
        ];
    }
}
