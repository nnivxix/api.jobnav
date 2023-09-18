@extends('layouts.html')

@section('container')
@include('components.navbar')

<form action="{{ route('apply.store') }}" method="POST" enctype="multipart/form-data" class="grid w-3/4 mx-auto my-4">
    @csrf
    <div class="my-5 flex flex-col w-1/2">
        <label for="letter">Letter</label>
        <textarea name="letter" class="border" id="letter"></textarea>
    </div>
    <input type="hidden" value="{{$job['uuid']}}" name="uuid">
    <div class="my-4 flex flex-col">
        <label for="attachment">attachment</label>
        <input type="file" class="border-2 w-1/2 p-2" accept="application/pdf" id="attachment" name="attachment" />
    </div>
    <button class="bg-carrot-600 text-white text-xl py-4 px-2 w-1/2 ">Apply job</button>
</form>
<x-footer></x-footer>
@endsection