<?php

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

beforeEach(function () {
    $users = User::factory(4)
        ->has(Company::factory()
            ->has(Job::factory(2)))
        ->create();
});

test('user can see list jobs', function () {
    $response = $this->get(route('api.job.index'));

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(8);
});

test('user can see only published jobs', function () {
    $job = Job::first()->update([
        'posted_at' => null
    ]);

    $response = $this->get(route('api.job.index'));

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(7);
});

test('user can search jobs by title', function () {
    $job = Job::first();

    $response = $this->get(route('api.job.index', [
        'q' => $job->title
    ]));

    $response
        ->assertJsonCount(1, 'data')
        ->assertStatus(200);
});

test('user can search jobs by company', function () {
    $company = Company::first();

    $response = $this->get(route('api.job.index', [
        'q' => $company->name
    ]));

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(2);
});

test('user should be can see detail job', function () {
    $job = Job::first();

    $response = $this->getJson(route('api.job.show', [
        'uuid' => $job->uuid
    ]));

    $response->assertStatus(200);
    expect($job->title)->toEqual($response['data']['title']);
});

test('user should be get 404 error when input wrong uuid of job', function () {

    $response = $this->getJson(route('api.job.show', [
        'uuid' => fake()->uuid()
    ]));

    $response->assertStatus(404);
    expect($response['message'])->toEqual("Not Found");
});
