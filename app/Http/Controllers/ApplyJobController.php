<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\ApplyJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplyJobController extends Controller
{
    public function create(Job $job)
    {
        return view(
            'apply.add',
            [
                'job' => $job,
            ]
        );
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'letter'     => 'string|required',
            'attachment' => 'required|file|max:2000|mimes:pdf'
        ]);

        $attachment = $request->file('attachment');
        if ($attachment) {
            $attachment_name = Str::random(20) . "." . $attachment->getClientOriginalExtension();
            $attachment->storePubliclyAs('job/apply/', $attachment_name, 'public');
            $validate['attachment'] = 'job/apply/' . $attachment_name;
        }

        $validate['job_uuid'] = $request->uuid;
        $validate['user_id'] = auth()->user()->id;
        $validate['created_at'] = now();

        DB::table('apply_jobs')->insert($validate);
        return redirect('/user');
    }
}
