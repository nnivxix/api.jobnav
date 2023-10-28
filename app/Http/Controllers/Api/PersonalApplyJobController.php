<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalApplyJobResource;
use App\Models\ApplyJob;
use Illuminate\Http\Request;

class PersonalApplyJobController extends Controller
{
    public function index()
    {
        $applyJobs = ApplyJob::query()
            ->where('user_id', auth()->user()->id)
            ->get();

        return PersonalApplyJobResource::collection($applyJobs);
    }

    public function show(ApplyJob $applyJob)
    {
        return PersonalApplyJobResource::make($applyJob);
    }
}
