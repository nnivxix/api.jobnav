<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{

    public function definition()
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'bio'     => fake()->paragraph(),
            'avatar'  => UploadedFile::fake()->image('thumbnail' . time() . '.jpg', 400, 400)->store('users/avatars', 'public'),
            'cover'   => UploadedFile::fake()->image('thumbnail' . time() . '.jpg', 800, 300)->store('users/covers', 'public'),
            'skills'  => null,
        ];
    }
}
