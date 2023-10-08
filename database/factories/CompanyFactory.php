<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Support\Str;
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
            'slug'         => Str::random(8),
            'avatar'       => UploadedFile::fake()->image('avatar' . time() . '.jpg', 400, 400)->store('companies/avatars', 'public'),
            'cover'        => UploadedFile::fake()->image('cover' . time() . '.jpg', 800, 300)->store('companies/covers', 'public'),
            'about'        => $this->faker->sentence(200),
            'owned_by'     => random_int(1, 10),
            'location'     => $this->faker->country(),
            'full_address' => $this->faker->address(),
            'website'      => $this->faker->url(),
            'posted_at'    => $this->faker->dateTimeInInterval('0 week', '+3 week'),
        ];
    }
}
