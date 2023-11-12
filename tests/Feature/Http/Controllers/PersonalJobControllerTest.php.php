<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Models\ApplyJob;

beforeEach(function () {

    $user = User::create([
        'name'     => 'Hanasa',
        'username' => 'hanasa',
        'email'    => 'hanasa@hanasa.com',
        'password' => bcrypt('password')
    ]);

    $companies = Company::factory(3)
        ->state([
            'owned_by' => $user->id
        ])
        ->create();

    $jobs = Job::factory(8)
        ->state([
            'company_id' => $companies->pluck('id')->random()
        ])
        ->create();

    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ])->json();
});

test('user should be can see personal job posts', function () {
    $response = $this->get(route('api.personal-job.index'))
        ->json();
    $this->assertCount(8, $response['data']);
});

test('user should be can see applicants on single personal job post', function () {
    $guest = User::factory()->create();
    $job = Job::limit(1)->first();
    $applyJob = ApplyJob::create([
        'user_id'      => $guest->id,
        'uuid'         => fake()->uuid(),
        'job_id'       => $job->id,
        'attachment'   => fake()->sentence(),
        'cover_letter' => fake()->sentence(),
        'status'       => 'pending',
    ]);

    $response = $this->get(route('api.personal-job.show',  $job));

    expect($response['data']['applicants'])->toHaveCount(1);
});

test('user should be can update personal job post', function () {
    $job = Job::limit(1)->first();

    $response = $this->putJson(route('api.personal-job.update', $job), [
        'title'       => $job->title,
        'description' => $job->description,
        'location'    => $job->location,
        'salary'      => 120,
        'categories'  => $job->categories,
    ]);

    expect($response->status())->toBe(200);
    expect($response['message'])->toBe('Job updated.');
    $this->assertDatabaseHas('jobs', [
        'salary'      => 120,
    ]);
});

test('user should be can create a personal job', function () {
    $company = Company::limit(1)->first();

    $response = $this->postJson(route('api.personal-job.store'), [
        'company_id'  => $company->id,
        'title'       => fake()->sentence(),
        'description' => fake()->sentence(),
        'location'    => fake()->sentence(),
        'salary'      => 1200,
        'categories'  => fake()->sentence(),
    ]);

    expect($response->status())->toBe(201);
    $this->assertDatabaseCount('jobs', 9);
});

test('user should be can\'t create personal using other company', function () {

    $response = $this->postJson(route('api.personal-job.store'), [
        'company_id'  => fake()->numberBetween(29, 109),
        'title'       => fake()->sentence(),
        'description' => fake()->sentence(),
        'location'    => fake()->sentence(),
        'position'    => fake()->sentence(),
        'salary'      => 1200,
        'categories'  => fake()->sentence(),
    ]);
    expect($response->json('message'))->toBe('Forbidden.')
        ->and($response->status())->toBe(403);
});

test('user should be can delete own personal job', function () {
    $job = Job::limit(1)->first();
    $response = $this->deleteJson(route('api.personal-job.destroy', [
        'job' => $job->uuid
    ]));

    expect($response->json('message'))->toBe('Job deleted.')
        ->and($response->status())->toBe(200);
    $this->assertModelMissing($job);
});

test('user should be can\'t delete other personal job', function () {
    $user = User::factory(3)
        ->create();
    $company = Company::factory()
        ->state([
            'owned_by' => $user->pluck('id')->random()
        ])
        ->has(Job::factory())
        ->create();
    $job = Job::factory()->state([
        'company_id' => $company->id
    ])
        ->create();

    $response = $this->deleteJson(route('api.personal-job.destroy', [
        'job' => $job->uuid
    ]));

    expect($response->json('message'))->toBe('Forbidden.')
        ->and($response->status())->toBe(403);
});
