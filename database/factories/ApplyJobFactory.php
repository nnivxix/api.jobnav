<?php

namespace Database\Factories;

use App\Enums\StatusJobEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplyJob>
 */
class ApplyJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'      => fake()->randomNumber(),
            'job_id'       => fake()->randomNumber(),
            'uuid'         => fake()->uuid(),
            'cover_letter' => fake()->text(),
            'attachment'   => fake()->sentence(),
            'status'       => StatusJobEnum::Pending
        ];
    }
}
