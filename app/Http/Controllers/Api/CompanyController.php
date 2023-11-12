<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Database\Eloquent\Builder;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 15);

        $companies = Company::query()
            ->whereHas('jobs')
            ->withCount('jobs')
            ->when($request->has('q'), function (Builder $query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->input('q')}%");
            })
            ->latest()
            ->paginate($per_page);

        return CompanyResource::collection($companies);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Company $company)
    {
        $company
            ->loadCount('jobs')
            ->load('jobs');

        return CompanyResource::make($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
