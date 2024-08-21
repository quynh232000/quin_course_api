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
        <a href="/course/1/manage/curriculum">Curriculum</a> / <a href="/course/1/manage/curriculum/section/3"> Section</a> / <a href=""> Step</a>
    </div>
    <div class="p-4">
        <div class="fw-bold text-success mb-4">Section 1: Introduction > Lecture 1</div>


        <div class="border-top mt-4 pt-4 d-flex flex-column gap-2">
            <div class="border-info p-2 px-3 pb-0 mt-2" style="border:1px dashed green">
                <div class="form-group">
                    <label for="" class="form-label">Title Lecture:</label>
                    <input type="text" class="form-control" placeholder="Enter a Title">
                    <label for="" class="form-label">Quiz Description:</label>
                    <input type="text" class="form-control" placeholder="Enter question quiz">
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-sm btn-outline-primary">Save</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="py-3">
            <div class="mb-2 mt-2">
                <label for="" class="fs-6">Resourse video</label>
                <div class="row">
                    <div class="col-md-5 ">
                        <div class=" " style="height: 186px">
                            <label for="video"
                                class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                                <i class="fa-brands fa-youtube" style="font-size: 80px"></i>
                                {{-- <img style="width: 100%;height:100%" class="w-100 h-100 object-fit-cover rounded-2" src="https://cdn.britannica.com/70/234870-050-D4D024BB/Orange-colored-cat-yawns-displaying-teeth.jpg" alt=""> --}}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <p class="fs-8 lh-sm">Your promo video is a quick and compelling way for students to preview what they’ll learn in your course. Students considering your course are more likely to enroll if your promo video is well-made.</p>
                        {{-- <div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" accept="video/*" id="video">
                            </div>
                        </div> --}}
                        <div class="d-flex justify-content-between py-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkvideo" id="radioimage3"
                                    checked value="imagepc">
                                <label class="form-check-label" for="radioimage3">
                                    Upload from PC
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkvideo" value="imageyoutube"
                                    id="radioimage4">
                                <label class="form-check-label" for="radioimage4">
                                    From Youtube
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" accept="video/*" id="inputvideo"
                                    placeholder="id video youtube">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label for="" class="fs-6">Description</label>
            <div class="form-group">
                <div id="editor-container" style="min-height: 220px"></div>
                <input type="hidden" name="description" id="description">
                <div class="fs-8 mt-1">Use 1 or 2 related keywords, and mention 3-4 of the most important areas that
                    you've
                    covered during your course.</div>
            </div>
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
