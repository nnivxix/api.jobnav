<?php

use App\Models\User;
use App\Models\Company;

beforeEach(function () {

    $hanasa = User::create([
        'name'     => 'Hanasa',
        'username' => 'hanasa',
        'email'    => 'hanasa@hanasa.com',
        'password' => bcrypt('password')
    ]);
    $users = User::factory(3)->create();
    $hanasaCompanies = Company::factory(3)
        ->state([
            'owned_by' => $hanasa->id
        ])
        ->create();
    $companies = Company::factory(3)
        ->state([
            'owned_by' => $users->pluck('id')->random()
        ])
        ->create();

    $this->post(route('api.user.login'), [
        'email'    => 'hanasa@hanasa.com',
        'password' => 'password'
    ])->json();
});

test('user should be can see own companies', function () {
    $response = $this->get(route('api.personal-company.index'))
        ->json();

    $this->assertCount(3, $response['data']);
});

test('user should be can create a company', function () {
})->skip(true, "I think a company should be register by admin");

test('user should be can see detail a owned company', function () {

    $company = Company::first();
    $response = $this->get(route('api.personal-company.show', [
        'company' => $company->slug
    ]));

    $this->assertEquals($company->name, $response['data']['name']);
});

test('user should be can update owned personal company', function () {
    $company = Company::where('owned_by', auth()->user()->id)->first();

    $response = $this->put(
        route('api.personal-company.update', [
            'company' => $company->slug
        ]),
        [
            'name'        => 'company edited',
            'title'       => 'company edited',
            'description' => 'description company edited',
            'location'    => 'location company edited',
            'position'    => 'position company edited',
            'salary'      => 2345,
        ]
    );
    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'company updated'
        ]);
});

test('user should be can delete owned company', function () {
    $company = Company::where('owned_by', auth()->user()->id)->first();

    $response = $this->delete(
        route('api.personal-company.destroy', [
            'company' => $company->slug
        ]),
    );

    $response
        ->assertJson([
            'message' => 'Company deleted'
        ])
        ->assertStatus(200);
    $this->assertModelMissing($company);
});

test('user should be get code 404 when get detail deleted owned company', function () {
    $company = Company::where('owned_by', auth()->user()->id)->first();
    $company->delete();

    $response = $this->get(route('api.personal-company.show', [
        'company' => $company['slug']
    ]));

    $response
        ->assertStatus(404);
});
