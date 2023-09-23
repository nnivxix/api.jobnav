<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
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
    $hanasa->profile()->create([
      'header' => fake()->paragraph(),
      'avatar' => fake()->imageUrl(640, 640, 'people'),
      'cover' => fake()->sentence(),
      'skills' => null,
    ]);
    User::factory()
      ->has(Profile::factory())
      ->count(9)->create();
  }
}
