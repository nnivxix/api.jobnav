<?php

use App\Models\User;


test('user should be failed proccess register', function () {
    $response = $this->withHeaders(['Accept' => 'application/json'])
        ->postJson(route('api.user.register'), [
            'email'    => 'hanasa@mail.com',
            'password' => 'test-halo'
        ]);

    expect($response['errors']['username'][0])->toEqual('The username field is required.');
    expect($response->status())->toBe(400);
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

    $response = $this->withHeaders(['Accept' => 'application/json'])
        ->postJson(route('api.user.register'), [
            'name'     => 'Hanasa',
            'username' => 'hanasa',
            'email'    => 'hanasa@hanasa.com',
            'password' => 'password'
        ]);

    expect($response['errors']['username'][0])->toBe('The username has already been taken.');
    expect($response->status())->toBe(400);
});

test('user should be success register', function () {
    $response = $this->postJson(route('api.user.register'), [
        'username' => 'hanasa',
        'name'     => 'Hanasa',
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);

    expect($response['message'])->toBe('User created.');
    expect($response->status())->toBe(201);
});
