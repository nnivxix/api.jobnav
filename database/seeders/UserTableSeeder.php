<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Experience;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Database\Factories\ExperienceFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $hanasa = User::firstOrCreate([
      'name'              => 'Hanasa',
      'username'          => 'hanasa',
      'email'             => 'hanasa@hanasa.com',
      'email_verified_at' => now(),
    ], [
      'password' => bcrypt('password'),
    ]);

    $hanasa->flag('admin');
    $hanasa->profile()->update([
      'bio'    => fake()->paragraph(),
      'avatar' => UploadedFile::fake()->image('avatar' . time() . '.jpg', 400, 400)->store('users/avatars', 'public'),
      'cover'  => UploadedFile::fake()->image('cover' . time() . '.jpg', 800, 300)->store('users/covers', 'public'),
      'skills' => null,
    ]);

    User::factory()
      ->has(Experience::factory(3))
      ->has(
        Company::factory(3)
          ->has(Job::factory(3)
            ->sequence(
              ['is_remote' => false],
              ['is_remote' => true],
            ))
      )
      ->count(9)
      ->create();
  }
}
