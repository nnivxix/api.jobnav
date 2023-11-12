<?php

use App\Models\User;

beforeEach(function () {
    User::create([
        'name'     => 'Hanasa',
        'username' => 'hanasa',
        'email'    => 'hanasa@mail.com',
        'password' => bcrypt('password')
    ]);
});

test('user should be login successfully', function () {
    $response = $this->postJson(route('api.user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);
    expect($response->status())->toBe(200);
    expect($response['message'])->toBe('Login successful.');
});

test('user should be failed login ', function () {
    $response = $this->post(route('api.user.login'), [
        'email'    => 'wrong@test.com',
        'password' => 'wrong'
    ]);

    expect($response['errors'])->toBe("Email or password is wrong.");
    expect($response->status())->toBe(401);
});

test('user should be cannot login twice ', function () {

    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);

    $response = $this->post(route('api.user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);
    
    expect($response['errors'])->toBe("You have logged in.");
    expect($response->status())->toBe(403);
});
