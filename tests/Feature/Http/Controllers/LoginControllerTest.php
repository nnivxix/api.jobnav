<?php
beforeEach(function () {
    $this->withHeaders(['Accept' => 'application/json'])
        ->post(route('user.register'), [
            'name'     => 'Hanasa',
            'username' => 'hanasa',
            'email'    => 'hanasa@mail.com',
            'password' => 'password'
        ]);
});

test('user should be login successfully', function () {
    $this->post(route('user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ])
        ->assertStatus(200);
});

test('user should be failed login ', function () {
    $this->post(route('user.login'), [
        'email'    => 'wrong@test.com',
        'password' => 'wrong'
    ])
        ->assertJson([
            "errors" => [
                "message" => [
                    "email or password wrong"
                ]
            ]
        ])
        ->assertStatus(401);
});

test('user should be cannot login twice ', function () {

    $this->post(route('user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);

    $this->post(route('user.login'), [
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ])
        ->assertJson([
            'errors' => [
                'message' => 'You have logged in'
            ]
        ])
        ->assertStatus(403);
});
