@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Curriculum
    </div>
    <div>
        @if (session('error'))
            <div class="alert alert-danger py-2 mt-2 text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 mt-2 text-white">{{ session('success') }}</div>
        @endif
    </div>
    <div class="p-4">
        <p class="fs-7">Start putting together your course by creating sections, lectures and practice (quizzes, coding
            exercises and assignments).</p>

        <form
            action="{{ $sectiondetail ? route('course.manage._course_curriculum_edit', ['id' => $course->id, 'section_id' => $sectiondetail->id]) : route('course.manage._course_curriculum', ['id' => $course->id]) }}"
            method="POST" class="border border-warning p-4 rounded-2">
            @csrf
            <div class="form-group">
                <label for="" class="form-label">New Section</label>
                <input type="text" name="title"
                    value="{{ old('title') ? old('title') : ($sectiondetail ? $sectiondetail->title : '') }}"
                    class="form-control" placeholder="Enter a Title">
            </div>
            <div class="form-group">
                <label for="" class="form-label">What will students be able to do at the end of this
                    section?</label>
                <input type="text" name="will_learn"
                    value="{{ old('will_learn') ? old('will_learn') : ($sectiondetail ? $sectiondetail->will_learn : '') }}"
                    class="form-control" placeholder="Enter a Learning Objective">
            </div>
            <div class="d-flex justify-content-center gap-3">
                @if ($sectiondetail)
                    <a href="{{ route('course.manage.course_curriculum',['id'=>$course->id]) }}" class="btn btn-info">Add new section</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                @else
                    <button type="submit" class="btn btn-primary">Add Section</button>
                @endif
            </div>
        </form>


        <div class="border-top mt-4 pt-4 d-flex flex-column gap-2">

            @forelse ($sections as $key=>$item)
                <div class="border py-2  w-100 btn-hover-primary ">
                    <div class="p-2 d-flex justify-content-between ali">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="fw-bold">Section {{ $key + 1 }}:</div>
                            <div class="d-flex gap-1 align-items-center ">
                                <i class="fa-regular fa-clipboard"></i>
                                <span>{{ $item->title }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-4 pe-4">
                            <a title="Delete section"
                                href="{{ route('course.manage.delete_section', ['id' => $course->id, 'section_id' => $item->id]) }}"
                                onclick="return confirm('Are you sure you want to delete this section')"
                                class="text-danger mb-0 border py-1 px-2"><i class="fa-solid fa-trash-can"></i></a>
                            <a title="Edit section"
                                href="{{ route('course.manage.course_section_edit', ['id' => $course->id, 'section_id' => $item->id]) }}"
                                class="border py-1 px-2 mb-0"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a title="Add lecture"
                                href="{{ route('course.manage.course_section_edit', ['id' => $course->id, 'section_id' => $item->id]) }}"
                                class="border py-1 px-2 text-primary">
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-danger py-5">
                    Create new section right now!
                </div>
            @endforelse


        </div>

        {{-- <a href="{{ route('course.manage.section', ['course_id' => $course->id,'section_id' => 1]) }}" --}}

    </div>
@endsection
@section('js')
@endsection
