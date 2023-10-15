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

    $response->assertStatus(200);
    $this->assertCount(2, $response['data']);
});

test('user should be can see detail applied job', function () {
    $appliedJob = ApplyJob::limit(1)->first();
    $response = $this->getJson(route('api.personal-apply-job.show', [
        'applyJob' => $appliedJob->uuid
    ]));

    $response
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll([
                    'data.uuid',
                    'data.cover_letter',
                    'data.attachment_url',
                    'data.status',
                    'data.updated_at',
                    'data.job',
                    'data.company'
                ])
        );
});
