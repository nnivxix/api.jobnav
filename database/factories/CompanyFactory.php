<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{

    public function definition()
    {
        return [
            'name'         => $this->faker->company(),
            'slug'         => $this->faker->slug(2, false),
            'avatar'       => UploadedFile::fake()->image('thumbnail' . time() . '.jpg', 400, 400)->store('company/avatars', 'public'),
            'image_cover'  => UploadedFile::fake()->image('thumbnail' . time() . '.jpg', 800, 300)->store('company/covers', 'public'),
            'about'        => $this->faker->sentence(200),
            'owned_by'      => random_int(1, 10),
            'location'     => $this->faker->country(),
            'full_address' => $this->faker->address(),
            'website'      => $this->faker->url(),
            'posted_at'    => $this->faker->dateTimeInInterval('0 week', '+3 week'),
        ];
    }
}
