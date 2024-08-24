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
        .btn-delete ,.btndelete{
            display: none;
            transition: .3s all ease
        }
        /* .fa-rectangle-xmark{
            color: rgb(204, 200, 200)
        } */
        .item:hover .btn-delete,.item:hover .btndelete{
            cursor: pointer;
            display: block;
            transition: .3s all ease;
            color: rgb(255, 0, 34)
        }
    </style>
@endpush

@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="/course/{{ $course->id }}/manage/curriculum">Curriculum</a> / <a
            href="/course/{{ $course->id }}/manage/curriculum/section/{{ $section->id }}"> Section</a> / <span>Quiz</span>

    </div>

    <div>
        @if (session('error'))
            <div class="alert alert-danger py-2 mt-2  text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 mt-2 text-white">{{ session('success') }}</div>
        @endif
    </div>
    <div class="p-4">
        <div class="fw-bold text-success mb-4">{{ $section->title }} <span class="text-dark px-2">/</span>
            {{ $step->title }}
        </div>
        <form method="POST"
            action="{{ route('course.quiz_setduration', ['id' => $course->id, 'section_id' => $section->id, 'step_id' => $step->id]) }}"
             class="border-top mt-4 pt-4 d-flex flex-column gap-2">
            @csrf
            <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                <div class="form-group">
                    <label for="" class="form-label">Duration to do this quiz ( Seconds/ s ):</label>
                    <div class="input-group mb-2 ">
                        <input class="form-control" value="{{ $step->duration }}" style="max-width: 120px" name="duration"
                            type="number">
                        <button type="submit" class="btn btn-info  mb-0" id="btn-check-video">Save</button>

                    </div>
                </div>
            </div>

        </form>

        <form method="POST"
            action="{{ route('course.quiz_addquestion', ['id' => $course->id, 'section_id' => $section->id, 'step_id' => $step->id]) }}"
            id="form-add-question" class="border-top mt-4 pt-4 d-flex flex-column gap-2">
            @csrf
            <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                <div class="form-group">
                    <label for="" class="form-label">Quiz Question:</label>
                    <div class="form-group">
                        <div id="editor-container" style="min-height: 80px">{!! $step->question ? $step->question->content : '' !!}</div>
                        <input type="hidden" name="content" id="content">

                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                    </div>
                </div>
            </div>

        </form>

        <form method="POST" id="form-answer">
            @csrf
            <div class="py-3">
                <label for="" class="form-label">Answer:</label>

                <div class="list">
                    @forelse ($step->answers as $item)
                        <div class=" border-bottom pb-2 mb-2 position-relative item">
                            <div class="d-flex gap-2">
                                <div class="">
                                    <input type="radio" name="checkbox[]" {{ $item->is_correct ? 'checked' : '' }}>
                                    <input type="text" name="id[]" hidden value="{{ $item->id }}">
                                </div>
                                <div class="flex-1">
                                    <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2">{{ $item->content }}</textarea>
                                </div>
                            </div>
                            <div class="mt-2 ps-5">
                                <input type="text" name="explain[]" value="{{ $item->explain }}"
                                    placeholder="Explain why this is or isn't the best anwser" class="form-control">
                            </div>
                            <a href="{{ route('course.quiz_deleteanswer', ['id' => $course->id, 'section_id' => $section->id, 'step_id' => $step->id, 'answer_id' => $item->id]) }}"
                                onclick="return confirm('Do you want to delete this?')" title="Delete "
                                class="position-absolute fs-4 btndelete" style="top: -15px ;right:0px"><i
                                    class="fa-regular fa-rectangle-xmark"></i></a>
                        </div>
                    @empty
                        <div class=" border-bottom pb-2 mb-2 position-relative item">
                            <div class="d-flex gap-2">
                                <div class="">
                                    <input type="radio" name="checkbox[]" checked>
                                    <input type="text" name="id[]" hidden>
                                </div>
                                <div class="flex-1">
                                    <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 ps-5">
                                <input type="text" name="explain[]"
                                    placeholder="Explain why this is or isn't the best anwser" class="form-control">
                            </div>
                            <span title="Delete " class="position-absolute fs-4 btn-delete" style="top: -15px ;right:0px"><i
                                    class="fa-regular fa-rectangle-xmark"></i></span>
                        </div>
                        <div class=" border-bottom pb-2 mb-2 position-relative item">
                            <div class="d-flex gap-2">
                                <div class="">
                                    <input type="radio" name="checkbox[]">
                                    <input type="text" name="id[]" hidden>
                                </div>
                                <div class="flex-1">
                                    <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 ps-5">
                                <input type="text" name="explain[]"
                                    placeholder="Explain why this is or isn't the best anwser" class="form-control">
                            </div>
                            <span title="Delete " class="position-absolute fs-4 btn-delete"
                                style="top: -15px ;right:0px"><i class="fa-regular fa-rectangle-xmark"></i></span>
                        </div>
                        <div class=" border-bottom pb-2 mb-2 position-relative item">
                            <div class="d-flex gap-2">
                                <div class="">
                                    <input type="radio" name="checkbox[]">
                                    <input type="text" name="id[]" hidden>
                                </div>
                                <div class="flex-1">
                                    <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 ps-5">
                                <input type="text" name="explain[]"
                                    placeholder="Explain why this is or isn't the best anwser" class="form-control">
                            </div>
                            <span title="Delete " class="position-absolute fs-4 btn-delete"
                                style="top: -15px ;right:0px"><i class="fa-regular fa-rectangle-xmark"></i></span>
                        </div>
                        <div class=" border-bottom pb-2 mb-2 position-relative item">
                            <div class="d-flex gap-2">
                                <div class="">
                                    <input type="radio" name="checkbox[]">
                                    <input type="text" name="id[]" hidden>
                                </div>
                                <div class="flex-1">
                                    <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mt-2 ps-5">
                                <input type="text" name="explain[]"
                                    placeholder="Explain why this is or isn't the best anwser" class="form-control">
                            </div>
                            <span title="Delete " class="position-absolute fs-4 btn-delete"
                                style="top: -15px ;right:0px"><i class="fa-regular fa-rectangle-xmark"></i></span>
                        </div>
                    @endforelse

                </div>
                <div class="pt-3">
                    <button type="button" class="btn btn-outline-primary btn-add">Add more anwswer</button>
                </div>
            </div>
            <input type="text" hidden name="indexcheck" value="0" id="indexcheck">
            <div class="mt-3">
                <button class="btn btn-primary w-100 ">Save</button>
            </div>
        </form>



    </div>
@endsection
@section('js')
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow' // or 'bubble'
        });

        var form = document.querySelector('#form-add-question');
        form.onsubmit = function(e) {
            var contentInput = document.querySelector('#content');
            contentInput.value = quill.root.innerHTML;

        };

        // submit answer 
        $("#form-answer").on('submit', function(e) {
            $("input[type='radio']").each((index, item) => {
                if ($(item).prop('checked')) {
                    $('#indexcheck').val(index)
                }
            })
        })

        // add answer 
        $('.btn-add').on('click', function() {
            $('.list').append(`
                <div class=" border-bottom pb-2 mb-2 position-relative item">
                    <div class="d-flex gap-2">
                        <div class="">
                            <input type="radio" name="checkbox[]">
                            <input type="text" name="id[]" hidden>
                        </div>
                        <div class="flex-1">
                            <textarea name="answer[]" class="form-control" id="answer1" placeholder="Add an answer." rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-2 ps-5">
                        <input type="text" name="explain[]" placeholder="Explain why this is or isn't the best anwser"
                            class="form-control">
                    </div>
                    <span title="Delete " class="position-absolute fs-4 btn-delete" style="top: -15px ;right:0px"><i class="fa-regular fa-rectangle-xmark"></i></span>
                </div>
            `)
            $('.btn-delete').on('click', function() {
                $(this).closest('.item').remove();
            })
        })
        $('.btn-delete').on('click', function() {
            $(this).closest('.item').remove();
        })
    </script>
@endsection
