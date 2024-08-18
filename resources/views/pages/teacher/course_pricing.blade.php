@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->tittle }} | Quin
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Pricing
    </div>
    <div class="p-4">
        <div>
            <div class="fw-bold">Set a price for your course</div>
            <span class="fs-7">Please select the currency and the price tier for your course. If you’d like to offer your
                course for
                free, it must have a total video length of less than 2 hours. Also, courses with practice tests can not be
                free.</span>
        </div>
        <div>
            <div class="row my-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <div for="" class="fw-bold fs-6">Price show</div>
                        <input type="number" class="form-control" placeholder="499.000 vnd">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div for="" class="fw-bold fs-6">Percent sale</div>
                        <input type="number" class="form-control" value="0">
                    </div>
                </div>
                <div class="col-md-12 d-flex gap-2 align-items-center" style="background-color: rgb(243, 234, 219)">
                    <div for="" class="fw-bold fs-6">Discount for Quin Course</div>
                    <strong class="py-2 px-4  text-warning ">30%</strong>
                </div>
            </div>
        </div>

        <div class="border-top pt-4 mt-3">
            <button class="btn btn-success w-100">Save </button>
        </div>

    </div>
@endsection
@section('js')
@endsection
