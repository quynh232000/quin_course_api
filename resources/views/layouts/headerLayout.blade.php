@extends('layouts.appLayout')
@section('viewTitle')
    Goals Course | Quin
@endsection

@php
    $sidebarshow = false;
@endphp

@section('main')
    <div class="container">
        <div class="row h-100 ">
            <div class="col-md-2 border-end py-4 px-2 rounded-2 d-flex flex-column gap-2" style="background-color: rgba(185, 183, 183, 0.12)">
                <a href="" class="d-flex gap-2 align-items-center border-bottom p-2 rounded-2 btn-hover-primary bg-primary text-white ">
                    <div style="min-width: 26px"><i class="fa-solid fa-tv"></i></div>
                    <span>Course</span>
                </a>
                <a href="" class="d-flex gap-2 align-items-center border-bottom p-2 rounded-2 btn-hover-primary">
                    <div style="min-width: 26px"><i class="fa-regular fa-comment-dots"></i></div>
                    <span>Communication</span>
                </a>
                <a href="" class="d-flex gap-2 align-items-center border-bottom p-2 rounded-2 btn-hover-primary">
                    <div style="min-width: 26px"><i class="fa-solid fa-chart-line"></i></div>
                    <span>Performance</span>
                </a>

            </div>
            <div class="col-md-10">
                @yield('content')
            </div>
        </div>

    </div>
@endsection
