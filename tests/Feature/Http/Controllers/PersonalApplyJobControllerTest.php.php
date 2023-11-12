<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Models\ApplyJob;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {

    $hanasa = User::create([
        'name'     => 'Hanasa',
        'username' => 'hanasa',
        'email'    => 'hanasa@hanasa.com',
        'password' => bcrypt('password')
    ]);

    $user = User::factory()
        ->create();

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

    ApplyJob::factory(2)
        ->state([
            'job_id' => $jobs->pluck('id')->random(),
            'user_id' => $hanasa->id
        ])
        ->create();

    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ])->json();
});

test('user should be can see recent applied jobs', function () {
    $response = $this->getJson(route('api.personal-apply-job.index'));

    expect($response->status())->toBe(200);
    expect($response['data'])->toHaveCount(2);
});

test('user should be can see detail applied job', function () {
    $appliedJob = ApplyJob::first();

    $response = $this->getJson(route('api.personal-apply-job.show',  $appliedJob));

    expect($response['data'])->toHaveKeys([
        'uuid',
        'cover_letter',
        'attachment_url',
        'status',
        'updated_at',
        'job',
    ]);
});

test('user should be get 404 error when see detail applied job', function () {
    $response = $this->getJson(route('api.personal-apply-job.show', fake()->uuid));

    expect($response['message'])->toBe('Resource Not Found.');
});
