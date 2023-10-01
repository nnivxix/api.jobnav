<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use App\Models\ApplyJob;
use App\Enums\StatusJobEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use App\Http\Resources\ApplyJobResource;
use App\Http\Resources\AuthenticatedUserResource;

class PersonalJobUserController extends Controller
{
    public function show(Job $job, Request $request)
    {
        $id = $request->input('id');

        $user = User::query()
        ->where('id', $id)
        ->first();

        $applicant = ApplyJob::query()
        ->where('job_id', $job->id)
        ->where('user_id', $id )
        ->first();

        /** Benchmark
         * array:3 [ // vendor\laravel\framework\src\Illuminate\Support\Benchmark.php:38
            * 0 => "0.004ms"
            * 1 => "0.001ms"
            * 2 => "0.001ms"
         *]
         Benchmark::dd([
             fn() => $job,
             fn() => $user,
             fn() => $applicant,
            ]);
        */

        return response()->json([
            'user'     => AuthenticatedUserResource::make($user),
            'job'      =>  JobResource::make($job),
            'appicant' => ApplyJobResource::make($applicant)
        ]);

    }
    public function update(Job $job, Request $request)
    {
        abort_if($job->company->owned_by !== auth()->user()->id, 
        response()->json([
            'message' => 'Forbidden'
        ], 403)
    );

        $id = $request->input('id');
        
        $validated = $request->validate([
            'status' => [new Enum(StatusJobEnum::class)]
        ]);
        $applicant = ApplyJob::query()
        ->where('job_id', $job->id)
        ->where('user_id', $id )
        ->first();

        if ($validated['status'] == 'approved') {
            // do send email to user
            // dd($applicant->user->email);
        }
        
        $applicant->update($validated);

        return ApplyJobResource::make($applicant)
        ->additional([
            'message' => 'applicant updated!'
        ]);
    }
}
