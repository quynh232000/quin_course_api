@extends('layouts.editcourseLayout')
@section('viewTitle')
{{$course->title}} | Quin
@endsection
@section('content')
<div class="p-4 fw-bold fs-5 border-bottom">
    Intended learners
</div>
<div class="p-4">

    
    <div class="border-top pt-4 mt-3">
        <button class="btn btn-success w-100">Save </button>
    </div>

</div>
@endsection
@section('js')

@endsection