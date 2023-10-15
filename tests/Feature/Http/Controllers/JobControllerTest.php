<?php

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

beforeEach(function () {
    $users = User::factory(10)
        ->create();
    $companies = Company::factory(8)
        ->state([
            'owned_by' => User::query()->pluck('id')->random()
        ])
        ->create();
    $jobs = Job::factory(4)
        ->state([
            'company_id' => Company::query()->pluck('id')->random()
        ])
        ->create();
});

test('user can see list jobs', function () {
    $response = $this->get(route('job.index'));

    $response
        ->assertJsonCount(4, 'data')
        ->assertStatus(200);
});

test('user can search jobs by title', function () {
    $job = Job::first();

    $response = $this->get(route('job.index', [
        'q' => $job->title
    ]));

    $response
        ->assertJsonCount(1, 'data')
        ->assertStatus(200);
});

test('user can search jobs by company', function () {
    $company = Company::first();

    $response = $this->get(route('job.index', [
        'q' => $company->title
    ]));
    $response
        ->assertJsonCount(4, 'data')
        ->assertStatus(200);
});

test('user should be can see detail job', function () {
    $job = Job::first();

    $response = $this->getJson(route('job.show', [
        'uuid' => $job->uuid
    ]));

    $response->assertStatus(200);
    $this->assertEquals($job->title, $response['data']['title']);
});

test('user should be get 404 error when input wrong uuid of job', function () {

    $response = $this->getJson(route('job.show', [
        'uuid' => fake()->uuid()
    ]));

    $response->assertStatus(404);
    $this->assertEquals("Not Found", $response['message']);
});
