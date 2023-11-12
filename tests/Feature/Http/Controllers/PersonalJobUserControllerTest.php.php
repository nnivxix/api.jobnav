<?php

use App\Enums\StatusJobEnum;
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

    $companies = Company::factory(2)
        ->state([
            'owned_by' => $user->id
        ])
        ->create();

    $jobs = Job::factory(4)
        ->state([
            'company_id' => $companies->pluck('id')->random()
        ])
        ->create();

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


    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ]);
});

test('user should be get detail applicant', function () {
    $job = Job::limit(1)->first();
    $applicant = User::where('id', '!=', auth()->user()->id)->first();

    $response = $this->getJson(route('api.personal-job-user.show', [
        'job' => $job->uuid,
        'id'  => $applicant->id
    ]));

    $response->assertStatus(200);
    $this->assertDatabaseCount('apply_jobs', 1);
});

test('user should be can update status applicant', function () {
    $job = Job::limit(1)->first();
    $applicant = User::where('id', '!=', auth()->user()->id)->first();

    $response = $this->putJson(route('api.personal-job-user.update', [
        'job' => $job->uuid,
        'id'  => $applicant->id
    ]), [
        'status' => StatusJobEnum::Approved
    ])
        ->json();

    $this->assertEquals('approved', $response['data']['status']);
    $this->assertDatabaseCount('apply_jobs', 1);
});
