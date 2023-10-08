<?php

beforeEach(function () {
    // uses(Illuminate\Contracts\Auth\Authenticatable::class);
    $this->withHeaders(['Accept' => 'application/json'])
        ->post(route('user.register'), [
            'name'     => 'Hanasa',
            'username' => 'hanasa',
            'email'    => 'hanasa@hanasa.com',
            'password' => 'password'
        ]);
});

test('user should be get detail data', function () {
    $this->post(route('user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ]);

    $this->get(route('user.current'))
        ->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'username',
            ],
        ])
        ->assertStatus(200);
});

test('user should be failed detail data', function () {
    $this->get(route('user.current'))
        ->assertJson([
            'message' => 'Unauthenticated.',
        ])
        ->assertStatus(401);
});
