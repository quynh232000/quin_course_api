@extends('layouts.headerLayout')
@section('viewTitle')
    Manage Courses | Quin
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="p-4 fw-bold fs-5 border-bottom">
            List course
        </div>
        <div class="">

            @if (session('error'))
                <div class="alert alert-danger mt-2 py-2 text-white">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success mt-2 py-2 text-white">{{ session('success') }}</div>
            @endif
        </div>

        <div class="p-4">
            <div class="d-flex justify-content-between">
                <div class=" d-flex gap-4">
                    <div class=" position-relative" style="width: 360px">
                        <input type="text" placeholder="Search your course" class="form-control">
                        <div class="position-absolute " style="top: 50%;right:5px;transform:translateY(-50%)">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <select name="" id="" class="form-select" style="width: 180px">
                        <option value="">Newest</option>
                        <option value="">Oldest</option>
                        <option value="">A-Z</option>
                        <option value="">Z-A</option>
                    </select>

                </div>
                <a href="/course/create/1" class="btn btn-primary">New course</a>

            </div>
            <div class="py-4">
                @forelse ($courses as $item)
                    <div class="border d-flex mb-4">
                        <style>
                            #imgcourse{
                                background-color: rgb(228, 228, 228)
                            }
                        </style>
                        <div class="border-end d-flex justify-content-center align-items-center" id="imgcourse" style=" width:118px;height:118px; ">
                            <img style="width: 100%" class="object-fit-contain"
                                src="{{ $item->image_url ? $item->image_url : 'https://s.udemycdn.com/course/200_H/placeholder.jpg' }}"
                                alt="">
                        </div>
                        <div class="py-2 px-4 flex-1 d-flex justify-content-between">
                            <div class="d-flex flex-column flex-1 pe-4">
                                <h4>{{ $item->title }}</h4>
                                @if ($item->progress()['status'] && $item->published_at)
                                    <div class="fs-7 text-success">Published</div>
                                @else
                                    <div class="fs-7 text-warning">Editing</div>
                                @endif
                                <div class="mt-2 w-60">
                                    <div style="height: 12px" class="progress" role="progressbar" aria-label="Info example"
                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success text-dark text-white"
                                            style="width: {{ $item->progress()['total_percent'] }}%">
                                            {{ $item->progress()['total_percent'] }}%</div>
                                    </div>

                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <a href="/course/{{ $item->id }}/manage/goals"
                                    class="btn btn-sm btn-warning h-fit">Edit</a>
                                <a href="/course/delete/{{ $item->id }}" class="btn btn-sm btn-danger h-fit"
                                    onclick="return confirm('Are you want to delete this course?')">Delete</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center text-white">No Course fund!</div>
                @endforelse
            </div>



        </div>
    </div>
@endsection
@section('js')
@endsection
