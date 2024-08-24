@extends('layouts.appLayout')
@section('viewTitle')
    Goals Course | Quin
@endsection
@push('css')
    <style>
        .accordion-button {
            background-color: rgb(242, 240, 246)
        }

        .step-active {
            background-color: #ffd8d8
        }

        .step-active:hover {
            background-color: #ffd8d8 !important;

        }

        .step-active .icon-step {
            color: rgb(242, 59, 59)
        }
    </style>
@endpush
@php
    $sidebarshow = false;
@endphp
@section('main')
    <div class="w-100">
        <div class="bg-dark px-4 py-2 d-flex justify-content-between">
            <a href="/course/instructor" class="text-white d-flex gap-2 align-items-center"><i
                    class="fa-solid fa-chevron-left"></i>Back your course </a>
            <div >
                <h5 class="text-white">{{ $course->title }}</h5>
            </div>
            <form method="POST" action="{{ route('published_course', ['id' => $course->id]) }}">
                @csrf
                @if ($course->published_at)
                    <button type="submit" class="btn btn-info btn-sm">Private</button>
                @else
                    <button type="submit" class="btn btn-success btn-sm">Pushlish</button>
                @endif
            </form>
        </div>
        @if (session('message'))
            <div>
                <div class="alert alert-success text-white py-2 rounded-0 mb-0">{{ session('message') }}</div>
            </div>
        @endif
        <div class="d-flex">
            <div class="flex-1 d-flex h-100">
                @yield('content')
            </div>
            <div class="border-start" style="width: 380px">
                <div class="d-flex justify-content-between p-3 ">
                    <span class="fw-bold">Course content</span>
                    <span class="cursor-pointer"><i class="fa-solid fa-xmark"></i></span>
                </div>

                <div class="">
                    <div class="accordion accordion-flush text-black" id="accordionFlushExample">

                        @foreach ($course->sections as $key => $item)
                            <div class="accordion-item ">
                                <div class="accordion-header border-top">
                                    <button class="accordion-button collapsed collapsed_item" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $key }}"
                                        aria-expanded="false" aria-controls="flush-collapse{{ $key }}">
                                        <div>
                                            <div class="fw-bold">Section {{ $key + 1 }}: {{ $item->title }}</div>
                                            <div class="text-gray fs-8">
                                                <span>0/{{ $item->total_steps() }}</span>|<span>{{ format_seconds_to_time($item->total_duration()) }}</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                <div id="flush-collapse{{ $key }}"
                                    class="accordion-collapse collapse border-top {{ $step->section->id == $item->id ? 'show' : '' }}"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body text-dark d-flex flex-column p-0 ">
                                        @foreach ($item->steps as $i_step => $step_child)
                                            <a href="{{ route('preview_home', ['id' => $course->id, 'type' => $step_child->type, 'uuid' => $step_child->uuid]) }}"
                                                class="d-flex btn-hover-primary px-3 py-2 ps-4 {{ $step_child->id == $step->id ? 'step-active' : '' }}">
                                                <div class="flex-1">
                                                    <div class="fs-7 d-flex">
                                                        <div class="w-fit" style="min-width: 20px">1.</div>
                                                        {{ $step_child->title }}
                                                    </div>
                                                    <div class="fs-8 text-gray d-flex gap-2 align-items-center">
                                                        <span class="icon-step">
                                                            @if ($step_child->type == 'quiz')
                                                                <i class="fa-solid fa-paste"></i>
                                                            @endif
                                                            @if ($step_child->type == 'lecture')
                                                                <i class="fa-solid fa-circle-play"></i>
                                                            @endif
                                                        </span>
                                                        <span>{{ format_seconds_to_time($step_child->duration) }}</span>
                                                    </div>
                                                </div>
                                                <div style="width: 16px" class="d-flex align-items-center text-success">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- <div class="accordion-item ">
                            <div class="accordion-header border-top">
                                <button class="accordion-button collapsed collapsed_item" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne1" aria-expanded="false"
                                    aria-controls="flush-collapseOne1">
                                    <div>
                                        <div class="fw-bold">Section 1: Introduction</div>
                                        <div class="text-gray fs-8">
                                            <span>0/2</span>|<span>1min</span>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div id="flush-collapseOne1" class="accordion-collapse collapse border-top "
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body text-dark d-flex flex-column p-0 ">
                                    <div class="d-flex btn-hover-primary px-3 py-2 ps-4">
                                        <div class="flex-1">
                                            <div class="fs-7 d-flex">
                                                <div class="w-fit" style="min-width: 20px">1.</div>
                                                Section Intro
                                            </div>
                                            <div class="fs-8 text-gray"><i class="fa-solid fa-circle-play"></i>
                                                <span>02:08</span>
                                            </div>
                                        </div>
                                        <div style="width: 16px" class="d-flex align-items-center text-success">
                                            <i class="fa-regular fa-circle-check"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex btn-hover-primary px-3 py-2 ps-4">
                                        <div class="flex-1">
                                            <div class="fs-7 d-flex">
                                                <div class="w-fit" style="min-width: 20px">1.</div>
                                                Section Intro
                                            </div>
                                            <div class="fs-8 text-gray"><i class="fa-solid fa-circle-play"></i>
                                                <span>02:08</span>
                                            </div>
                                        </div>
                                        <div style="width: 16px" class="d-flex align-items-center text-success">
                                            <i class="fa-regular fa-circle-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
