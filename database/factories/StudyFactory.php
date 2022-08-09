<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Study>
 */
class StudyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->words(2);
        $description = $this->faker->text(100);
        $starts_at = $this->faker->dateTimeBetween('-1 year', 'now');
        $ends_at = $this->faker->dateTimeBetween('now', '+1 year');
        
        return [
            'name' => ucfirst(implode(' ', $name)),
            'description' => $description,
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
        ];
    }
}
