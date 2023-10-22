<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;

beforeEach(function () {
    $user = User::factory(2)
        ->has(Company::factory(2)
            ->has(Job::factory(2)))
        ->create();
});

test('user get all list companies', function () {
    $response = $this->get(route('api.company.index'));

    expect($response['data'])->toHaveCount(4);
    expect($response['data'])->toBeArray();
});

test('user only get all list companies that have jobs', function () {
    // insert new company without job
    User::factory()
        ->has(Company::factory())
        ->create();

    $response = $this->get(route('api.company.index'));

    expect($response['data'])->toHaveCount(4);
    expect($response['data'])->toBeArray();
});

test('user can search a company', function () {
    $company = Company::first();

    $response = $this->get(route('api.company.index', [
        'q' => $company->name,
    ]));

    expect($response['data'])->toHaveCount(1);
    expect($response['data'])->toBeArray();
});

test('user search a company with wrong name', function () {
    $response = $this->get(route('api.company.index', [
        'q' => fake()->name(),
    ]));

    expect($response['data'])->toHaveCount(0);
    expect($response['data'])->toBeFalsy();
});

test('user can see detail company', function () {
    $company = Company::first();
    $response = $this->get(route('api.company.show', $company->slug));

    expect($response['data'])->toBeArray();
    expect($response['data']['jobs_count'])->toBeInt();
    expect($response['data']['jobs_count'])->toEqual(2);
    expect($response['data']['jobs'])->toHaveCount(2);
    expect($response['data']['name'])->toEqual($company->name);
});

test('user can see error 404 when input wrong data company', function () {
    $response = $this->get(route('api.company.show', fake()->slug));

    expect($response->status())->toEqual(404);
    expect(fn () => throw new Exception('Not Found.'))->toThrow('Not Found.');
});
