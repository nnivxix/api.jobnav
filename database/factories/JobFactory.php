<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{

    public function definition()
    {
        return [
            'uuid'        => fake()->uuid(),
            'company_id'  => random_int(1, 20),
            'title'       => fake()->jobTitle(),
            'position'    => fake()->sentence(3, false),
            'location'    => fake()->country(),
            'salary'      => fake()->numberBetween(200, 10000),
            'description' => fake()->sentence(56, false),
            'categories'  => str_replace(" ", ",", fake()->sentence(7)),
            'posted_at'   => fake()->dateTimeInInterval('now', '+1 year'),
        ];
    }
}
