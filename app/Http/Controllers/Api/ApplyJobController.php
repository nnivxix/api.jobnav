<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\ApplyJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplyJobController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $validate = $request->validate([
            'cover_letter' => 'required',
            'attachment'   => 'required|file|max:2000|mimes:pdf'
        ]);

        abort_if($job->isAppliedByUser || $job->company->owned_by == auth()->user()->id, response()->json([
            'message' => 'Forbidden'
        ], 403));

        $attachment = $request->file('attachment');
        if ($attachment) {
            $attachment_name = Str::random(20) . "." . $attachment->getClientOriginalExtension();
            $attachment->storePubliclyAs('jobs/apply/', $attachment_name, 'public');
            $validate['attachment'] = 'jobs/apply/' . $attachment_name;
        }

        $validate['job_id'] = $job->id;
        $validate['user_id'] = auth()->user()->id;
        $validate['uuid'] = Str::uuid();

        $job = new ApplyJob($validate);
        $job->save();

        return response()->json([
            'message' => 'Job Applied'
        ], 201);
    }
}
