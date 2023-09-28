<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PersonalCompanyRequest;
use App\Http\Resources\PersonalCompanyResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonalCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = auth()->user()->companies;
        return response()->json([
            'data' => $companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalCompanyRequest $request)
    {
        $validated = $request->validated();
        // need user email and must be check is exist
        $user = User::query()->where('email', $request->email)->first();

        if (!$user) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "email" => [
                        "email not registered"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $company = new Company($validated);
        $company->slug = Str::random(8);

        // give the status for need to review by admin
        // add field email company
        // add field status ['pending', 'approved', 'deleted']

        $company->owned_by = $user->id;
        $company->save();


        return response()->json([
            'message' => $company
        ], 201);

        // property of company
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return PersonalCompanyResource::make($company);
    }


    public function update(PersonalCompanyRequest $request, Company $company)
    {
        $validated = $request->validated();

        $avatar = $request->file('avatar');
        if ($avatar) {
            $avatar_file_name = Str::random(40) . '.' . $avatar->getClientOriginalExtension();

            if ($company->avatar && Storage::disk('public')->exists($company->avatar)) {
                Storage::delete('public/' . $company->avatar);
            }

            $avatar->storePubliclyAs('companies/avatars', $avatar_file_name, 'public');
            $validated['avatar'] = 'companies/avatars/' . $avatar_file_name;
        }

        $cover = $request->file('cover');
        if ($cover) {
            $cover_file_name = Str::random(40) . '.' . $cover->getClientOriginalExtension();

            if ($company->cover && Storage::disk('public')->exists($company->cover)) {
                Storage::delete('public/' . $company->cover);
            }

            $cover->storePubliclyAs('companies/covers', $cover_file_name, 'public');
            $validated['cover'] = 'companies/covers/' . $cover_file_name;
        }

        $company->update($validated);

        return response()->json([
            'message' => 'company updated'
        ]);
    }

    public function destroy(Company $company)
    {
        abort_if(
            $company->owned_by !== auth()->user()->id,
            response()->json([
                'message' => 'Forbidden'
            ], 403)
        );

        if ($company->cover && Storage::disk('public')->exists($company->cover)) {
            Storage::delete('public/' . $company->cover);
        }
        if ($company->avatar && Storage::disk('public')->exists($company->avatar)) {
            Storage::delete('public/' . $company->avatar);
        }


        $company->delete();

        return response()->json([
            'message' => 'Company deleted'
        ], 200);
    }
}
