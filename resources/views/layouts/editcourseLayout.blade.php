@extends('layouts.appLayout')
@section('viewTitle')
    Goals Course | Quin
@endsection

@php
    $sidebarshow =  false;
@endphp

@section('main')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12 border-bottom px-2">
                <h4>{{ $course->title?? 'Mange Courses' }}</h4>
            </div>
        </div>
        <div class="row py-3">
            <div class="col-md-3">
                @include('components.sidebarcourse')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body p-0">

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
