<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;

class PersonalJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = auth()->user()->jobPosts;

        return JobResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
