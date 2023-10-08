<?php

test('user should be login successfully', function () {
    $this->post(route('user.register'), [
        'username' => 'hanasa',
        'name'     => 'Hanasa',
        'email'    => 'hanasa@mail.com',
        'password' => 'password'
    ]);

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
