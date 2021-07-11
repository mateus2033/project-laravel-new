<?php

namespace Database\Factories;

use App\Models\Goals;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goals::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $value = $this->faker->randomFloat(2, 1000, 1000000);
        $valueObtained = $this->faker->randomFloat(2, 0, $value);

        return [
            "title" => $this->faker->words(4, true),
            "value" => $value,
            "value_obtained" => $valueObtained,
            // "value" => $this->faker->randomFloat(2, 1000, 1000000),
            // "value_obtained" => $this->faker->randomFloat(2, 1000, 1000000),
            "deadline" => $this->faker->dateTimeBetween('now', '+60 years')->format('Y-m-d')
        ];
    }
}
