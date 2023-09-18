<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'integer|min:1|max:100',
        ]);
        $keyword = $request->get('keyword');
        $jobs = Job::query()
            ->with('company')
            ->whereDate('posted_at', '<=', now())
            ->where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->paginate(16);
        return view('jobs.index', compact('jobs', 'keyword'));
    }

    public function create()
    {
        return view('jobs.add', [
            'companies' => Company::all()->where('ownedby', auth()->user()->id),
        ]);
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'title'       => 'string|required',
            'position'    => 'string|required',
            'location'    => 'string|required',
            'salary'      => 'integer|required',
            'categories'  => 'string|required',
            'posted_at'   => 'date|required',
            'description' => 'string|required',
        ]);
        $validate['company_id'] = $request->company_id;
        $validate['uuid'] = Str::uuid();
        Job::create($validate);
        return redirect('/jobs');
    }

    public function show(Job $job)
    {
        $company_jobs = Job::query()
            ->with('company')
            ->where('company_id', $job->company->id)
            ->whereNot('uuid', $job->uuid)
            ->get();

        $applied = DB::table('apply_jobs')
            ->where('job_uuid', $job->uuid)
            ->where('user_id', auth()->user()->id)
            ->get();

        return view('jobs.show', [
            'job'          => $job,
            'company_jobs' => $company_jobs,
            'applied'      => $applied,
        ]);
    }

    public function edit(Job $job)
    {
        if ($job->company->ownedby !== auth()->user()->id) {
            return redirect('/jobs');
        }
        return view('jobs.edit', [
            'job' => $job,
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $validate = $request->validate([
            'title' => 'string|required',
            'position' => 'string|required',
            'location' => 'string|required',
            'salary' => 'string|required',
            'categories' => 'string|required',
            'posted_at' => 'date|required',
            'description' => 'string|required',
        ]);

        $validate['company_id'] = $job->company_id;
        $validate['uuid'] = $job->uuid;


        $job->update($validate);
        return redirect('/jobs/' . $job->uuid)->with('success', 'Job has been updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        //
    }
}
