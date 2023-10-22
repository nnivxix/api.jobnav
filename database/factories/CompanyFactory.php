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
            'name'        => $this->faker->company(),
            'about'       => $this->faker->sentence(10),
            'slug'        => Str::random(8),
            'avatar'      => UploadedFile::fake()->image('avatar' . time() . '.jpg', 400, 400)->store('companies/avatars', 'public'),
            'cover'       => UploadedFile::fake()->image('cover' . time() . '.jpg', 800, 300)->store('companies/covers', 'public'),
            'description' => $this->faker->sentence(200),
            'location'    => $this->faker->country(),
            'address'     => $this->faker->address(),
            'website'     => $this->faker->url(),
        ];
    }
}
