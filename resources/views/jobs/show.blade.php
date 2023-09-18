@extends('layouts.html')

@section('container')
@include('components.navbar')
<main class="w-3/4 mx-auto my-4 grid gap-1 grid-cols-5">
    <div class="col-span-3">
        <div class="flex items-center relative">

            @if(auth()->user() && auth()->user()->id === $job->company->ownedby)
            <span class="absolute right-4 p-1 rounded-sm text-md bg-green-500 top-0"><a href="/jobs/{{$job['uuid']}}/edit">edit job</a></span>
            @endif
            <img class="w-36" src="{{URL::asset('/storage/'.$job->company->avatar)}}" alt="">
            <div class="ml-4">
                <h1 class="text-2xl">{{$job['title']}}</h1>
                <h1 class="text-xl">{{$job['position']}}</h1>
                <h1 class="text-blue-500 text-lg">
                    <a href="/companies/{{$job->company->slug}}">{{$job->company->name}}</a>
                </h1>
                <p class="text-lg">$ {{number_format($job['salary'], 2, "," )}}</p>
                <p class="text-lg">{{$job['location']}}</p>
            </div>
        </div>
        <p class="mt-4 text-lg">Posted at {{ date('d/M/Y', strtotime($job['posted_at']))}}</p>
        @foreach (explode(",", $job['categories']) as $category)
        <button class="mt-5 bg-carrot-600 hover:bg-orange-400 text-white py-1 px-3 rounded-md">{{ $category }}</button>
        @endforeach
        <h1 class="text-2xl mt-6 font-bold"></h1>
        <p class="text-lg text-justify pr-5">{{$job['description']}}</p>
        <div class="my-4">
            @if(count($applied) == 0 && $job->company->ownedby != auth()->user()->id)
            <a href="/jobs/{{$job['uuid']}}/apply" class="bg-carrot-600 text-white p-3">Apply Now</a>
            @elseif ($job->company->ownedby == auth()->user()->id)
            <p class="p-2 bg-carrot-600 text-white">You cannot applied to your own job post</p>

            @else
            <p class="p-2 bg-carrot-600 text-white">You have applied</p>
            @endif
        </div>
    </div>
    <div class="col-span-2">
        <h1 class="text-xl font-medium  text-smoke-700 py-3">Field Job in <span class="font-bold"> {{$job->company->name}}</span></h1>
        @foreach($company_jobs as $company_job)
        <div class="flex my-2 items-center  border-x-smoke-700 border-2">
            <img class="w-24" src="{{URL::asset('/storage/'.$company_job->company->avatar)}}" alt="{{$company_job['title']}}">
            <div class="px-3 w-full">
                <h1 class="text-xl font-medium w-full">
                    <a href="/jobs/{{$company_job['uuid']}}">
                        {{ $company_job['title'] }}
                </h1>
                </a>
                <h2 class="text-lg">{{ $company_job['position'] }}</h2>
                <div class="flex justify-between">
                    <span>{{ $company_job['location'] }}</span>
                    <span>$ {{number_format($company_job['salary'], 2, "," )}}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</main>
@endsection