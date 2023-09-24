<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
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
      'bio'    => fake()->paragraph(),
      'avatar' => UploadedFile::fake()->image('avatar' . time() . '.jpg', 400, 400)->store('users/avatars', 'public'),
      'cover'  => UploadedFile::fake()->image('cover' . time() . '.jpg', 800, 300)->store('users/covers', 'public'),
      'skills' => null,
    ]);
    User::factory()
      ->has(Profile::factory())
      ->count(9)->create();
  }
}
