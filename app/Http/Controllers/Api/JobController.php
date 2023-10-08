<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\ApplyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplyJobResource;
use Illuminate\Database\Eloquent\Builder;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 15);

        $jobs = Job::query()
            ->with('company')
            ->whereNotNull('posted_at')
            ->when($request->has('q'), function (Builder $query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->input('q')}%")
                    ->orWhere('position', 'LIKE', "%{$request->input('q')}%")
                    ->orWhere('description', 'LIKE', "%{$request->input('q')}%")
                    ->orWhereHas('company', function (Builder $query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->input('q')}%");
                    });
            })
            ->orderBy('posted_at', 'desc')
            ->paginate($per_page);

        // Benchmark::dd([
        //     fn() => $jobs
        // ]);

        return JobResource::collection($jobs);
    }

    public function show(Job $job)
    {
        $applyJob = null;

        if (auth('sanctum')->check()) {
            $applyJob = ApplyJob::query()
                ->where('job_id', $job->id)
                ->where('user_id', auth('sanctum')->user()->id)
                ->first();
        }
        // dd($applyJob);
        // Benchmark::dd([
        //     fn() => $job,
        //     fn() => $applyJob,
        //   ], 3);

        return JobResource::make($job->load('company'))
            ->additional([
                'job_status' => $applyJob ? ApplyJobResource::make($applyJob) : null
            ]);
    }
}
