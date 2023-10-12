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

test('user should be failed log out', function () {
    $this->post(route('user.logout'))
        ->assertJson([
            'message' => 'Unauthenticated.',
        ])
        ->assertStatus(401);
});

test('user should be success log out', function () {
    $user = $this->post(route('user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ]);


    $this->post(route('user.logout'), [
        'Authorization' => "Bearer " . $user['token']
    ])
        ->assertJson([
            'message' => 'Logged out'
        ])
        ->assertStatus(200);
});

test('user should be can update profile username', function () {
    $this->post(route('user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ]);

    $user = $this->get(route('user.current'));

    $response = $this->put(
        route('user.update'),
        [
            'name'     => 'hanasa sofari',
            'username' => 'hanasa',
            'email'    => "hanasa@hanasa.com"
        ],
        [
            'Accept' => "application/json"
        ],
    );

    $response->assertJson([
        'data' => [
            'name' => 'hanasa sofari'
        ]
    ]);
});
