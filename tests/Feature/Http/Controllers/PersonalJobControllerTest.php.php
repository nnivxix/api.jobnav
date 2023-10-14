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

    $this->post(route('user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ])->json();
});

test('user should be can see personal job posts', function () {
    $response = $this->get(route('personal-job.index'))
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

    $response = $this->get(route('personal-job.show', [
        'job' => $job->uuid
    ]))
        ->json();

    $this->assertCount(1, $response['data']['applicants']);
});

test('user should be can update personal job post', function () {
    $job = Job::limit(1)->first();

    $response = $this->putJson(route('personal-job.update', [
        'uuid' => $job->uuid
    ]), [
        'title'       => $job->title,
        'description' => $job->description,
        'location'    => $job->location,
        'position'    => $job->position,
        'salary'      => 120,
        'categories'  => $job->categories,
    ]);

    $response->assertStatus(200);
    $this->assertEquals('Job updated', $response['message']);
    $this->assertDatabaseHas('jobs', [
        'salary'      => 120,
    ]);
});
