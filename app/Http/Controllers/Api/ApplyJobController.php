<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusJobEnum;
use App\Models\Job;
use App\Models\ApplyJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;

class ApplyJobController extends Controller
{
    public function store(ApplyJobRequest $request, Job $job)
    {
        $validate = $request->validated();

        $job->checkAuthorization();

        $attachment = $request->file('attachment');

        if ($attachment) {
            $attachment_name = Str::random(20) . "." . $attachment->getClientOriginalExtension();
            $attachment->storePubliclyAs('jobs/apply/', $attachment_name, 'public');
            $validate['attachment'] = 'jobs/apply/' . $attachment_name;
        }

        $validate['job_id'] = $job->id;
        $validate['user_id'] = auth()->user()->id;
        $validate['uuid'] = Str::uuid();
        $validate['status'] = StatusJobEnum::Pending;

        $job = ApplyJob::create($validate);

        return response()->json([
            'message' => 'Job Applied.'
        ], 201);
    }
}
