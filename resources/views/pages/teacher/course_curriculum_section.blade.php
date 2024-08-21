@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="">Curriculum</a>/ <a href=""> Section</a>
    </div>
    <div class="p-4">
        <div class="fw-bold text-success mb-4">Section 1: Introduction</div>


        <div class="border-top mt-4 pt-4 d-flex flex-column gap-2">

            <div class="d-flex flex-column gap-2">
                <div class="border py-2  w-100 btn-hover-primary ">
                    <div class="p-2 d-flex justify-content-between ">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Lecture:</div>
                            <div class="d-flex gap-1 align-items-center ">
                                <i class="fa-regular fa-clipboard"></i>
                                <span>Introduction</span>
                            </div>
                        </div>
                        <div class="d-flex gap-4 pe-4">
                            
                            <a title="Add content" href="/course/1/manage/curriculum/section/3/lecture/1" class="border py-1 px-2 mb-0">
                                <i class="fa-solid fa-plus"></i> Content
                            </a>
                            <a title="Delete" href="{{ url('courses/delete_section/' . $course->id . '/1') }}"
                                onclick="return confirm('Are you sure you want to delete this section')"
                                class="text-danger mb-0 border py-1 px-2"><i class="fa-solid fa-trash-can"></i></a>

                        </div>
                    </div>
                </div>
                <div class="border py-2  w-100 btn-hover-primary ">
                    <div class="p-2 d-flex justify-content-between ">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Quiz:</div>
                            <div class="d-flex gap-1 align-items-center ">
                                <i class="fa-solid fa-circle-question"></i>
                                <span>quiz1</span>
                            </div>
                        </div>
                        <div class="d-flex gap-4 pe-4">
                           
                            <a title="Add content" href="/course/1/manage/curriculum/section/3/quiz/2" class="border py-1 px-2 mb-0">
                                <i class="fa-solid fa-plus"></i> Questions
                            </a>
                            <a title="Delete" href="{{ url('courses/delete_section/' . $course->id . '/1') }}"
                                onclick="return confirm('Are you sure you want to delete this section')"
                                class="text-danger mb-0 border py-1 px-2"><i class="fa-solid fa-trash-can"></i></a>
                        </div>
                    </div>
                </div>
                <div class="border py-2  w-100 btn-hover-primary ">
                    <div class="p-2 d-flex justify-content-between ">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Assignment:</div>
                            <div class="d-flex gap-1 align-items-center ">
                                <i class="fa-regular fa-clipboard"></i>
                                <span>Introduction</span>
                            </div>
                        </div>
                        <div class="d-flex gap-4 pe-4">

                            <a title="Add content" href="/course/1/manage/curriculum/section/3/asm/1" class="border py-1 px-2 mb-0">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <a title="Delete" href="{{ url('courses/delete_section/' . $course->id . '/1') }}"
                                onclick="return confirm('Are you sure you want to delete this section')"
                                class="text-danger mb-0 border py-1 px-2"><i class="fa-solid fa-trash-can"></i></a>

                        </div>
                    </div>
                </div>
            </div>

            <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                <div class="form-group">
                    <label for="" class="form-label">Title:</label>
                    <input type="text" class="form-control" placeholder="Enter a Title">
                    <label for="" class="form-label">Quiz Description:</label>
                    <input type="text" class="form-control" placeholder="Enter question quiz">
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-sm btn-outline-warning">Cancel</button>
                        <button class="btn btn-sm btn-outline-primary">Add</button>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-3">
                <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus me-2"></i>Lecture</button>
                <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus me-2"></i>Quiz</button>
                <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus me-2"></i>Assignment</button>
            </div>
        </div>



    </div>
@endsection
@section('js')
@endsection
