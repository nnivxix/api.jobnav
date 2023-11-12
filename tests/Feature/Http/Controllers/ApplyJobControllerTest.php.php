<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\UploadedFile;

beforeEach(function () {

    $hanasa = User::create([
        'name'     => 'Hanasa',
        'username' => 'hanasa',
        'email'    => 'hanasa@hanasa.com',
        'password' => bcrypt('password')
    ]);
    $user = User::factory()->create();

    $hanasaCompany = Company::factory()
        ->state([
            'owned_by' => $hanasa->id
        ])
        ->create();
    $company = Company::factory()
        ->state([
            'owned_by' => $user->id
        ])
        ->create();

    $jobs = Job::factory(5)
        ->state([
            'company_id' => $company->id
        ])
        ->create();

    $hanasaJobs = Job::factory(4)
        ->state([
            'company_id' => $hanasaCompany->id
        ])
        ->create();

    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ])->json();
});

test('user should be cannot apply job twice', function () {
    $company = Company::firstWhere('owned_by', '!=', auth()->user()->id);
    $job = $company->jobs()->first();

    $attachment = UploadedFile::fake()->create('doc-attacment.pdf', 120, 'application/pdf');

    $this->post(route('api.apply-job.store', [
        'job' => $job->uuid
    ]), [
        'cover_letter' => fake()->sentence(),
        'attachment' => $attachment
    ]);

    $response = $this->post(route('api.apply-job.store', [
        'job' => $job?->uuid
    ]), [
        'cover_letter' => fake()->sentence(),
        'attachment' => $attachment
    ]);

    expect($response->status())->toBe(403);
    expect($response['message'])->toBe('Forbidden.');
});

test('user should be cannot apply job to own job', function () {
    $company = Company::where('owned_by', auth()->user()->id)->first();
    $job = Job::where('company_id', $company->id)->first();

    $attachment = UploadedFile::fake()->create('doc-attacment.pdf', 120, 'application/pdf');

    $response = $this->post(route('api.apply-job.store', $job), [
        'cover_letter' => fake()->sentence(),
        'attachment' => $attachment
    ]);

    expect($response->status())->toBe(403);
    expect($response['message'])->toBe('Forbidden.');
});

test('user should be can apply job', function () {
    $company = Company::firstWhere('owned_by', '!=', auth()->user()->id);
    $job = $company->jobs()->first();
    $attachment = UploadedFile::fake()->create('doc-attacment.pdf', 120, 'application/pdf');

    $response = $this->post(route('api.apply-job.store',  $job), [
        'cover_letter' => fake()->sentence(),
        'attachment' => $attachment
    ]);

    expect($response->status())->toBe(201);
    expect($response['message'])->toBe('Job Applied.');
});
