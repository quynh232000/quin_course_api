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
            Step</span>
    </div>

    <div>
        @if (session('error'))
            <div class="alert alert-danger py-2 text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 text-white">{{ session('success') }}</div>
        @endif
    </div>


    <form method="post" class="p-4" enctype="multipart/form-data">
        @csrf
        <div class="fw-bold text-success mb-4">{{ $section->title }} <span class="text-dark px-2">/</span>
            {{ $step->title }}
        </div>


        <div class="mb-2 mt-2">
            <label for="" class="fs-6">Promotional video</label>
            <div class="row">
                <div class="col-md-5 ">
                    <div class=" " style="height: 186px">
                        <label id="preview-video" for="video"
                            class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                            @if ($step->lecture && $step->lecture->video_url)
                                @if ($step->lecture->video_type == 'local')
                                    <video class="w-100 h-100 object-fit-cover rounded-2" controls>
                                        <source src="{{ $step->lecture->video_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <iframe class="w-100 h-100 object-fit-cover" src="{{ $step->lecture->video_url }}"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                    </iframe>
                                @endif
                            @else
                                <i class="fa-brands fa-youtube" style="font-size: 80px"></i>
                            @endif
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <p class="fs-8 lh-sm">Your promo video is a quick and compelling way for students to preview what
                        they’ll learn in your course. Students considering your course are more likely to enroll if your
                        promo video is well-made.</p>
                    {{-- <div>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" accept="video/*" id="video">
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-between py-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="checkvideo" id="radioimage3" checked
                                value="videopc">
                            <label class="form-check-label" for="radioimage3">
                                Upload from PC
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="checkvideo" value="videoyoutube"
                                id="radioimage4">
                            <label class="form-check-label" for="radioimage4">
                                From Youtube
                            </label>
                        </div>
                    </div>
                    <div>
                        <div class="input-group mb-2 ">
                            <input type="text" name="duration" value="0" id="duration" hidden>
                            <input type="file" name="video" class="form-control border rounded-2" accept="video/*"
                                id="inputvideo" placeholder="id video youtube">
                            <div class="btn btn-info btn-sm d-none mb-0" id="btn-check-video">Check</div>

                        </div>
                        <div>
                            <small class="text-danger" id="error-video"></small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="mb-2">
            <label for="" class="fs-6">Description</label>
            <div class="form-group">
                <div id="editor-container" style="min-height: 220px">{!! $step->lecture ? $step->lecture->description : '' !!}</div>
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

        var form = document.querySelector('form');
        form.onsubmit = function() {
            var contentInput = document.querySelector('input[name="description"]');
            contentInput.value = quill.root.innerHTML;
        };
        // select radio video
        $("input[name='checkvideo']").change(function() {
            if ($(this).val() == 'videopc') {
                $("#inputvideo").attr('type', 'file')
                $("#btn-check-video").addClass('d-none')
            } else {
                $("#btn-check-video").removeClass('d-none')
                $("#inputvideo").attr('type', 'text')
            }
        })
        // preview video 
        $('input[name="video"]').on('change', function(e) {
            if ($(this).attr('type') == 'file') {
                const [file] = e.target.files
                if (file) {
                    const url = URL.createObjectURL(file)
                    $("#preview-video").html(`
                        <video id='videoel' class="w-100 h-100 object-fit-cover rounded-2"  controls>
                                        <source src="${url}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                    `)
                    $('#videoel').on('loadedmetadata', function() {
                        const duration = Math.floor(this.duration);
                        $('#duration').val(duration);
                    });
                }
            }
        })
        // check image video by id 
        $("#btn-check-video").on('click', function() {
            $("#error-video").text("")
            const value = $("input[name='video']").val();
            if (value) {
                $.ajax('/api/common/video/getinfo/' + value).done(res => {
                    if (res.status) {
                        $("#preview-video").html(`
                        <iframe class="w-100 h-100 object-fit-cover" src="https://www.youtube.com/embed/${value}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                        `)
                    } else {
                        $("#error-video").text(res.message)
                    }
                })
            }
        })
    </script>
@endsection
