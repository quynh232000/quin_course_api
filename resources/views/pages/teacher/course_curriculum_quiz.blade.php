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
        <a href="/course/1/manage/curriculum">Curriculum</a> / <a href="/course/1/manage/curriculum/section/3"> Section</a> /
        <a href=""> Quiz</a>
    </div>
    <div class="p-4">
        <div class="fw-bold text-success mb-4">Section 1: Introduction > Quiz 1</div>


        <div class="border-top mt-4 pt-4 d-flex flex-column gap-2">
            <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                <div class="form-group">
                    <label for="" class="form-label">Title Quiz:</label>
                    <input type="text" class="form-control" placeholder="Enter a Title">
                    <label for="" class="form-label">Quiz Question:</label>
                    <div>
                        <div id="editor-container" style="min-height: 80px"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-sm btn-outline-primary">Save</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="py-3">
            <label for="" class="form-label">Answer:</label>

            <div>
                <div class=" border-bottom pb-2 mb-2">
                    <div class="d-flex gap-2">
                        <div class="">
                            <input type="radio">
                        </div>
                        <div class="flex-1">
                            <textarea name="" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-2 ps-5">
                        <input type="text" placeholder="Explain why this is or isn't the best anwser" class="form-control">
                    </div>
                </div>
                <div class=" border-bottom pb-2 mb-2">
                    <div class="d-flex gap-2">
                        <div class="">
                            <input type="radio">
                        </div>
                        <div class="flex-1">
                            <textarea name="" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-2 ps-5">
                        <input type="text" placeholder="Explain why this is or isn't the best anwser" class="form-control">
                    </div>
                </div>
                <div class=" border-bottom pb-2 mb-2">
                    <div class="d-flex gap-2">
                        <div class="">
                            <input type="radio">
                        </div>
                        <div class="flex-1">
                            <textarea name="" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-2 ps-5">
                        <input type="text" placeholder="Explain why this is or isn't the best anwser" class="form-control">
                    </div>
                </div>
                <div class=" border-bottom pb-2 mb-2">
                    <div class="d-flex gap-2">
                        <div class="">
                            <input type="radio">
                        </div>
                        <div class="flex-1">
                            <textarea name="" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-2 ps-5">
                        <input type="text" placeholder="Explain why this is or isn't the best anwser" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary w-100">Save</button>
        </div>



    </div>
@endsection
@section('js')
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow' // or 'bubble'
        });
        // select radio video
        $("input[name='checkvideo']").change(function() {
            if ($(this).val() == 'imagepc') {
                $("#inputvideo").attr('type', 'file')
            } else {
                $("#inputvideo").attr('type', 'text')
            }
        })
    </script>
@endsection
