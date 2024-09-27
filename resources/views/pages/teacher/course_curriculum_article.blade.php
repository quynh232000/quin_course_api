@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection

@push('js1')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush

@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="/course/{{ $course->id }}/manage/curriculum">Curriculum</a> / <a
            href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}"> Section</a> / <span>
            Step</span> : <span>Article</span>
    </div>

    <div>
        @if (session('error'))
            <div class="alert alert-danger py-2 text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 text-white">{{ session('success') }}</div>
        @endif
    </div>

    <form method="POST"
        action="{{ route('course.quiz_setduration', ['id' => $course->id, 'section_id' => $section->id, 'step_id' => $step->id]) }}"
        class="border-top mt-4 p-4 d-flex flex-column gap-2">
        @csrf
        <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
            <div class="form-group">
                <label for="" class="form-label">Duration to do this Article ( Seconds/ s ):</label>
                <div class="input-group mb-2 ">
                    <input class="form-control" value="{{ $step->duration }}" style="max-width: 120px" name="duration"
                        type="text">
                    <button type="submit" class="btn btn-info  mb-0" id="btn-check-video">Save</button>
                </div>
            </div>
        </div>

    </form>
    <form method="post" class="p-4" id="form-content" enctype="multipart/form-data">
        @csrf
        <div class="fw-bold text-success mb-4">{{ $section->title }} <span class="text-dark px-2">/</span>
            {{ $step->title }}
        </div>



        <div class="mb-2">
            <label for="" class="fs-6">Content</label>
            <div class="form-group">
                @if ($step->article && $step->article->content)
                    <div id="editor-container" style="min-height: 220px">{!! $step->article->content !!}</div>
                @else
                    <div id="editor-container" style="min-height: 220px"></div>
                @endif
                <input type="hidden" name="description" id="description">
                <div class="fs-8 mt-1">Use 1 or 2 related keywords, and mention 3-4 of the most important areas that
                    you've
                    covered during your course.</div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>



    </form>
@endsection
@section('js')
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow' // or 'bubble'
        });

        var form = document.querySelector('#form-content');
        form.onsubmit = function(e) {
            var contentInput = document.querySelector('input[name="description"]');
           
            contentInput.value = quill.root.innerHTML;
        };
    </script>
@endsection
