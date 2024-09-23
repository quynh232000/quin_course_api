@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection
@push('js1')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush
@push('css')
    <style>
        .title-body:hover .btn-icon-edit {
            display: block !important;
        }
    </style>
@endpush
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="{{ route('course.manage.course_curriculum', ['id' => $course->id]) }}">Curriculum</a>/ <span> Section</span>
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
        <div class="fw-bold text-success mb-4">{{ $section->title }}</div>


        <div class="border-top mt-4 pt-4 d-flex flex-column gap-2">

            <div class="d-flex flex-column gap-2">
                @forelse ($section->steps as $item)
                    <div class="border py-2  w-100 btn-hover-primary ">
                        <div class="row p-2 d-flex justify-content-between ">
                            <div class="col-md-8 d-flex gap-2 align-items-center flex-1">
                                @if ($item->type == 'lecture')
                                    <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Lecture:</div>
                                @endif
                                @if ($item->type == 'quiz')
                                    <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Quiz:</div>
                                @endif
                                @if ($item->type == 'asm')
                                    <div class="fw-bold"> <i class="fa-solid fa-circle-check me-2"></i>Assignment:</div>
                                @endif
                                @if ($item->type == 'article')
                                    <div class="fw-bold"> <i class="fa-regular fa-newspaper me-2"></i>Article:</div>
                                @endif
                                <div class="d-flex gap-1 align-items-center flex-1 changetitle">
                                    @if ($item->type == 'lecture')
                                        <i class="fa-regular fa-clipboard"></i>
                                    @endif
                                    @if ($item->type == 'quiz')
                                        <i class="fa-regular fa-circle-question"></i>
                                    @endif
                                    @if ($item->type == 'asm')
                                        <i class="fa-regular fa-newspaper"></i>
                                    @endif
                                    <div class="title-body d-flex" id="">
                                        <span>{{ $item->title }}</span>
                                        <span id="" class="ms-3 d-none btn-icon-edit" title="Edit title"
                                            d-title="{{ $item->title }}" d-couser_id= "{{ $course->id }}"
                                            d-section_id= "{{ $section->id }}" d-step_id= "{{ $item->id }}"><i
                                                class="fa-solid fa-pencil"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex gap-4 pe-4 justify-content-end">

                                @if ($item->type == 'lecture')
                                    <a title="Add content"
                                        href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}/{{ $item->type }}/{{ $item->id }}"
                                        class="border py-1 px-2 mb-0">
                                        <i class="fa-solid fa-plus"></i> Content
                                    </a>
                                @endif
                                @if ($item->type == 'article')
                                    <a title="Add content"
                                        href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}/{{ $item->type }}/{{ $item->id }}"
                                        class="border py-1 px-2 mb-0">
                                        <i class="fa-solid fa-plus"></i> Content
                                    </a>
                                @endif
                                @if ($item->type == 'quiz')
                                    <a title="Add content"
                                        href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}/{{ $item->type }}/{{ $item->id }}"
                                        class="border py-1 px-2 mb-0">
                                        <i class="fa-solid fa-plus"></i> Questions
                                    </a>
                                @endif
                                @if ($item->type == 'asm')
                                    <a title="Add content"
                                        href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}/{{ $item->type }}/{{ $item->id }}"
                                        class="border py-1 px-2 mb-0">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                @endif

                                <a title="Delete"
                                    href="{{ route('course.manage.delete_step', ['id' => $course->id, 'section_id' => $section->id, 'step_id' => $item->id]) }}"
                                    onclick="return confirm('Are you sure you want to delete this section')"
                                    class="text-danger mb-0 border py-1 px-2"><i class="fa-solid fa-trash-can"></i></a>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-warning">Add new content right now!</div>
                @endforelse

            </div>

            <form method="POST" id="showform">


            </form>

            <div class="mt-3 d-flex gap-3 justify-content-center">
                <button class="btn btn-outline-primary btn-sm btn-add" data="lecture"><i
                        class="fa-solid fa-plus me-2"></i>Lecture</button>
                <button class="btn btn-outline-primary btn-sm btn-add" data="quiz"><i
                        class="fa-solid fa-plus me-2"></i>Quiz</button>
                <button class="btn btn-outline-primary btn-sm btn-add" data="asm"><i
                        class="fa-solid fa-plus me-2"></i>Assignment</button>
                <button class="btn btn-outline-primary btn-sm btn-add" data="article"><i
                        class="fa-solid fa-plus me-2"></i>Article</button>
            </div>
        </div>



    </div>
@endsection
@section('js')
    <script>
        $('.btn-add').on('click', function() {
            const type = $(this).attr('data')
            let title = 'New Lecture'
            switch (type) {
                case 'quiz':
                    title = 'New Quiz'
                    break;
                case 'asm':
                    title = 'New Assignment'
                    break;
                    case 'article':
                    title = 'New Article'
                    break;

                default:
                    break;
            }
            $('#showform').html(`
                <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="type" value="${type}" hidden>
                        <label for="" class="form-label">${title}:</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter a Title">
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-sm btn-outline-warning btn-cancel">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Add</button>
                        </div>
                    </div>
                </div>
            `)
            $(".btn-cancel").on('click', function() {
                $('#showform').html('')
            })
        })
        // show form edit title 
        $('.btn-icon-edit').on('click', function() {
            const title = $(this).attr('d-title')
            const couser_id = $(this).attr('d-couser_id')
            const section_id = $(this).attr('d-section_id')
            const step_id = $(this).attr('d-step_id')
            $(this).closest(".changetitle").html(`
                <form method="POST" 
                class="w-100"
                action="/course/${couser_id}/manage/curriculum/section/${section_id}/${step_id}">
                    @csrf
                    <div class="input-group  ">
                        <input type="text" name="title" value="${title}" class="form-control">
                        <button type="submit" class="btn  btn-primary input-group-text mb-0">Save</button>
                    </div>
                </form>
            `)
        })
    </script>
@endsection
