<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

test('user should be get detail data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('api.user.current'));

    expect($response['data'])->toHaveKeys(['id', 'email', 'username', 'jobs', 'experiences']);
});

test('user should be failed get detail data', function () {
    $response = $this->get(route('api.user.current'),  [
        'Accept' => "application/json"
    ]);

    expect($response->status())->toBe(401);
    expect($response['message'])->toBe('Unauthenticated.');
});

test('user should be failed log out', function () {
    $response = $this->postJson(route('api.user.logout'), [
        'Accept' => "application/json"
    ]);

    expect($response->status())->toBe(401);
    expect($response['message'])->toBe('Unauthenticated.');
});

test('user should be success log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('api.user.logout'), [
            'Authorization' => "Bearer " . $user['token']
        ]);

    expect($response['message'])->toBe('Logged out.');
    expect($response->status())->toBe(200);
});

test('user should be can update profile username', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->put(
            route('api.user.update'),
            [
                'name'     => 'hanasa sofari',
                'username' => $user->username,
                'email'    => $user->email
            ],
            [
                'Accept' => "application/json"
            ],
        );

    $response
        ->assertJson([
            'message' => 'Profile updated successfully.'
        ])
        ->assertStatus(200);
});

test('user should be can update profile avatar', function () {
    $user = User::factory()
        ->create();

    $avatar = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)
        ->putJson(
            route('api.user.update'),
            [
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'avatar'   => $avatar
            ],
            [
                'Accept' => "application/json"
            ],
        );

    $response->assertJson([
        'message' => 'Profile updated successfully.'
    ])
        ->assertStatus(200);
    $this->assertNotNull($response['data']['avatar']);
});
