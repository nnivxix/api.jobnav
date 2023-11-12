<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalJobRequest;
use App\Http\Resources\PersonalJobResource;

class PersonalJobController extends Controller
{

    public function index()
    {
        $posts = auth()->user()->jobPosts;

        return JobResource::collection($posts);
    }

    public function store(PersonalJobRequest $request)
    {
        $job = $request->validated();

        $job['company_id'] = $request->company_id;
        $job['uuid'] = Str::uuid();
        $checkCompaniesIsOwned = auth()->user()->companies->where('id', $request->company_id)->count() === 0;

        abort_if($checkCompaniesIsOwned, response()->json([
            'message' => 'Forbidden.'
        ], 403));

        $post = new Job($job);
        $post->save();

        return response()->json([
            'meesage' => 'Job created.',
            'uuid'    => $post->uuid
        ], 201);
    }

    public function show(Job $job)
    {
        $job = $job->load('applicants');

        return PersonalJobResource::make($job);
    }

    public function update(PersonalJobRequest $request, Job $job)
    {
        $validated = $request->validated();

        $validated['posted_at'] = $request->posted_at;
        /**TODO
         * Make a Policy to filter is job->company is owned
         * 
         * */

        abort_if(
            !$job->company->isOwned($request->company_id),
            response()->json([
                'message' => 'Forbidden.'
            ], 403)
        );

        $job->update($validated);

        return response()->json([
            'message' => 'Job updated.',
        ], 200);
    }

    public function destroy(Job $job)
    {

        $isCompanyOwnedByUser = $job
            ->company()
            ->where('owned_by', auth()->user()->id)
            ->get();

        abort_if(
            !$isCompanyOwnedByUser,
            response()->json([
                'message' => 'Forbidden.'
            ], 403)
        );

        $job->delete();

        return response()->json([
            'message' => 'Job deleted.'
        ], 200);
    }
}
