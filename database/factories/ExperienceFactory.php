<?php

namespace Database\Factories;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    public function definition()
    {
        return [
            'uuid'         => fake()->uuid(),
            'user_id'      => random_int(1, 10),
            'title'        => $this->faker->jobTitle(),
            'company_name' => $this->faker->company(),
            'logo'         => UploadedFile::fake()->image('logo' . time() . '.jpg', 400, 400)->store('experiences/logo', 'public'),
            'location'     => $this->faker->city() . ', ' . $this->faker->country(),
            'description'  => $this->faker->sentence(37),
            'started'      => $this->faker->dateTimeInInterval('0 week', '-9 week'),
            'ended'        => $this->faker->dateTimeInInterval('0 week', '+9 week'),
        ];
    }
}
