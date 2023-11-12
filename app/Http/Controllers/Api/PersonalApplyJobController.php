<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalApplyJobResource;
use App\Models\ApplyJob;

class PersonalApplyJobController extends Controller
{
    public function index()
    {
        $applyJobs = ApplyJob::where('user_id', auth()->user()->id)->get();

        $applyJobs->load('job', 'job.company');

        return PersonalApplyJobResource::collection($applyJobs);
    }

    public function show(ApplyJob $applyJob)
    {
        $applyJob->load('job', 'job.company');

        return PersonalApplyJobResource::make($applyJob);
    }
}
