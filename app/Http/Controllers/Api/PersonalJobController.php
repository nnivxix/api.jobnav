<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalJobResource;

class PersonalJobController extends Controller
{

    public function index()
    {
        $posts = auth()->user()->jobPosts;

        return JobResource::collection($posts);
    }

    public function store(Request $request)
    {
        $job = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'location'    => 'required',
            'position'    => 'required',
            'salary'      => 'numeric',
            'categories'  => 'string',
        ]);

        $job['company_id'] = $request->company_id;
        $job['uuid'] = Str::uuid();
        $checkCompaniesIsOwned = auth()->user()->companies->where('id', $request->company_id)->count() === 0;

        abort_if($checkCompaniesIsOwned, response()->json([
            'message' => 'Forbidden'
        ], 403));

        $post = new Job($job);
        $post->save();

        return response()->json([
            'meesage' => 'job created',
        ], 201);
    }

    public function show(Job $job)
    {
        $job = $job->load('applicants');

        return PersonalJobResource::make($job);
    }

    public function update(Request $request, $uuid)
    {
        $job = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'location'    => 'required',
            'position'    => 'required',
            'salary'      => 'numeric',
            'categories'  => 'string',
        ]);

        $job['posted_at'] = $request->posted_at;
        $job['company_id'] = $request->company_id;

        $checkCompaniesIsOwned = auth()->user()->companies->where('id', $request->company_id)->count() === 0;

        abort_if($checkCompaniesIsOwned, response()->json([
            'message' => 'Forbidden'
        ], 403));

        Job::where('uuid', $uuid)->update($job);

        return response()->json([
            'meesage' => 'Job updated',
        ], 200);
    }

    public function destroy($id)
    {
        // edit database to cascade on delete
    }
}
