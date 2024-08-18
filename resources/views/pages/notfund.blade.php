@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Manage Levels course
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">

        <h2 class="text-center my-5">Page error</h2>
        <div>
            <div class="alert alert-danger text-white">
                @if (session('message'))
                    {{ session('message') }}
                @endif
            </div>
        </div>

    </div>
@endsection
