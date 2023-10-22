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
            ->published()
            ->when($request->has('q'), function (Builder $query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->input('q')}%")
                    ->orWhere('description', 'LIKE', "%{$request->input('q')}%")
                    ->orWhereHas('company', function (Builder $query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->input('q')}%");
                    });
            })
            ->when($request->has('is_remote'), function (Builder $query) {
                $query->remoteJobs();
            })
            ->orderBy('posted_at', 'desc')
            ->paginate($per_page);

        // Benchmark::dd([
        //     fn() => $jobs
        // ]);

        return JobResource::collection($jobs);
    }

    public function show(string $uuid)
    {
        $job = Job::query()
            ->where('uuid', $uuid)
            ->first();

        abort_if(!$job, response()->json([
            "message" => "Not Found",
        ], 404));

        $job->load('company', 'applyJob');

        return JobResource::make($job);
    }
}
