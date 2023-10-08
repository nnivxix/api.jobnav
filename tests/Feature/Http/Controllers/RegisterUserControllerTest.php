<?php

use App\Models\User;


test('user should be failed proccess register', function () {
    $this->withHeaders(['Accept' => 'application/json'])
        ->post(route('user.register'), [
            'email'    => 'hanasa@mail.com',
            'password' => 'test-halo'
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                'username' => ['The username field is required.'],
                'name'     => ['The name field is required.'],
            ]
        ]);
});

test('user should be failed proccess register because the credential has been taken', function () {
    $hanasa = User::firstOrCreate([
        'name'              => 'Hanasa',
        'username'          => 'hanasa',
        'email'             => 'hanasa@hanasa.com',
        'email_verified_at' => now(),
    ], [
        'password' => bcrypt('password'),
    ]);

    $this->withHeaders(['Accept' => 'application/json'])
        ->post(route('user.register'), [
            'name'     => 'Hanasa',
            'username' => 'hanasa',
            'email'    => 'hanasa@hanasa.com',
            'password' => 'password'
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                'username' => ['The username has already been taken.'],
                'email'    => ['The email has already been taken.'],
            ]
        ]);
});

test('user should be success register', function () {
    $this->post(route('user.register'), [
        'username' => 'hanasa',
        'name'     => 'Hanasa',
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ])
        ->assertStatus(201)
        ->assertJson([
            'message' => 'user created'
        ]);
});
