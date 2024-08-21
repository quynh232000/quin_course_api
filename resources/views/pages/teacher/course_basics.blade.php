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
        Course landing page
    </div>
    <div>
        @if (session('error'))
            <div class="alert alert-danger py-2 text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 text-white">{{ session('success') }}</div>
        @endif
    </div>
    <form method="POST" enctype="multipart/form-data" class="p-4">
        @csrf
        <p class="fs-7">Your course landing page is crucial to your success on Udemy. If it’s done right, it can also help
            you gain
            visibility in search engines like Google. </p>

        <div>
            <div class="mb-2">
                <label for="" class="fs-6">Course title</label>
                <div class="form-group">
                    <input type="text" class="form-control" name="title" value="{{ $course->title }}">
                    <div class="fs-8">Your title should be a mix of attention-grabbing, informative, and optimized for
                        search</div>
                </div>
            </div>
            <div class="mb-2">
                <label for="" class="fs-6">Course subtitle</label>
                <div class="form-group">
                    <input type="text" class="form-control" name="sub_title" value="{{ $course->sub_title }}">
                    <div class="fs-8">Description should have minimum 200 words.</div>
                </div>
            </div>
            <div class="mb-2">
                <label for="" class="fs-6">Description</label>
                <div class="form-group">
                    <div id="editor-container" style="min-height: 220px">{!! $course->description !!}</div>
                    <input type="hidden" value="{{ $course->description }}" name="description" id="description">
                    <div class="fs-8 mt-1">Use 1 or 2 related keywords, and mention 3-4 of the most important areas that
                        you've
                        covered during your course.</div>
                </div>
            </div>
            <div class="mb-2">
                <label for="" class="fs-6">Level Course</label>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="level_id" id="" class="form-select">
                                @foreach ($levels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <select name="level_id_child" id="level_id_child" class="form-select">
                                <option value="">--Select Subcategory--</option>
                                @foreach ($cate2 as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="mb-2">
                <label for="" class="fs-6">Category</label>
                <div class="row listcategory">
                    @foreach ($allCate as $item)
                        <div class="col-md-4 category_group">
                            <div class="form-group">
                                <select name="category_id[]" class="form-select category_id"
                                    haschild="{{ $item->hasChild() ? 1 : 0 }}">
                                    <option value="">--Select category--</option>
                                    @foreach ($item->sameparent() as $cate)
                                        <option value="{{ $cate->id }}" {{ $cate->id == $item->id ? 'selected' : '' }}>
                                            {{ $cate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                    @if (count($cate->childCategories($course->category_id)) > 0)
                        <div class="col-md-4 category_group">
                            <div class="form-group">
                                <select name="category_id[]" class="form-select category_id"
                                    haschild="{{ $item->hasChild() ? 1 : 0 }}">
                                    <option value="">--Select category--</option>
                                    @foreach ($cate->childCategories($course->category_id) as $cate)
                                        <option value="{{ $cate->id }}">
                                            {{ $cate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="mb-2 ">
                <label for="" class="fs-6">Course image</label>

                <div class="row ">
                    <div class="col-md-5 ">
                        <div class=" " style="height: 186px">
                            <label id="privew-image" for="image"
                                class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                                @if ($course->image_url)
                                    <img id="image_id" style="width: 100%;height:100%"
                                        class="w-100 h-100 object-fit-cover rounded-2" src="{{ $course->image_url }}"
                                        alt="">
                                @else
                                    <i class="fa-regular fa-image" style="font-size: 80px"></i>
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <p class="fs-8 lh-sm">Upload your course image here. It must meet our course image quality standards
                            to be accepted.
                            Important guidelines: 750x422 pixels; .jpg, .jpeg,. gif, or .png. no text on the image.</p>

                        <div class="d-flex justify-content-between py-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkimage" id="radioimage1" checked
                                    value="imagepc">
                                <label class="form-check-label" for="radioimage1">
                                    Upload from PC
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkimage" value="imageyoutube"
                                    id="radioimage2">
                                <label class="form-check-label" for="radioimage2">
                                    From Youtube
                                </label>
                            </div>
                        </div>
                        <div>
                            <div class="input-group mb-2 ">
                                <input type="file" name="image" class="form-control border rounded-2"
                                    accept="image/*" id="inputimage" placeholder="id video youtube">
                                <div class="btn btn-info btn-sm d-none mb-0" id="btn-check-image">Check</div>

                            </div>
                            <div>
                                <small class="text-danger" id="error-image"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-2 mt-2">
                <label for="" class="fs-6">Promotional video</label>
                <div class="row">
                    <div class="col-md-5 ">
                        <div class=" " style="height: 186px">
                            <label id="preview-video" for="video"
                                class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                                @if ($course->video_url)
                                    @if ($course->video_type == 'local')
                                        <video class="w-100 h-100 object-fit-cover rounded-2" controls>
                                            <source src="{{ $course->video_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <iframe class="w-100 h-100 object-fit-cover"
                                            src="{{$course->video_url}}" title="YouTube video player"
                                            frameborder="0"
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
                                <input class="form-check-input" type="radio" name="checkvideo" id="radioimage3"
                                    checked value="videopc">
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
                            <div class="input-group mb-2">
                                <input type="file" class="form-control" name="video" accept="video/*"
                                    id="inputvideo" placeholder="id video youtube">
                                <div class="btn btn-info btn-sm d-none" id="btn-check-video">Check</div>
                            </div>
                            <div>
                                <small class="text-danger" id="error-video"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="border-top pt-4 mt-3">
            <button class="btn btn-success w-100">Save</button>
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

        // choose category 
        function renderCate() {
            $('.category_id').on('change', function() {
                const value = $(this).val();
                $.ajax('/api/category/get_child/' + value)
                    .done(res => {
                        const parent = $(this).closest('.category_group')
                        if (res && res.data.length > 0) {
                            const data = res.data;
                            parent.nextAll().remove();
                            // render list category new
                            const html = data.map(cate => `<option value="${cate.id}">${cate.name}</option>`)
                                .join('')
                            $('.listcategory').append(`
                                <div class="col-md-4 category_group">
                                    <div class="form-group">
                                        <select name="category_id[]" class="form-select category_id">
                                            <option value="">--Select category--</option>
                                    ${html}
                                        </select>
                                    </div>
                                </div>
                            `);
                            renderCate()
                        } else {
                            parent.nextAll().remove();
                        }
                    })
            })
        }
        renderCate();
        // select radio image
        $("input[name='checkimage']").change(function() {
            if ($(this).val() == 'imagepc') {
                $("#inputimage").attr('type', 'file')
                $("#btn-check-image").addClass('d-none')

            } else {
                $("#btn-check-image").removeClass('d-none')
                $("#inputimage").attr('type', 'text')
            }
        })
        // select radio video
        $("input[name='checkvideo']").change(function() {
            if ($(this).val() == 'videopc') {
                $("#inputvideo").attr('type', 'file')
                $("#btn-check-video").removeClass('d-none')

            } else {
                $("#inputvideo").attr('type', 'text')
                $("#btn-check-video").removeClass('d-none')

            }
        })

        // preview image 
        $('input[name="image"]').on('change', function(e) {
            if ($(this).attr('type') == 'file') {
                const [file] = e.target.files
                if (file) {
                    const url = URL.createObjectURL(file)
                    $("#privew-image").html(`
                        <img style="width: 100%;height:100%" class="w-100 h-100 object-fit-cover rounded-2"
                                            src="${url}" alt="">
                    `)
                }
            }

        })
        // preview video 
        $('input[name="video"]').on('change', function(e) {
            if ($(this).attr('type') == 'file') {
                const [file] = e.target.files
                if (file) {
                    const url = URL.createObjectURL(file)
                    $("#preview-video").html(`
                        <video class="w-100 h-100 object-fit-cover rounded-2"  controls>
                                        <source src="${url}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                    `)
                }
            }

        })

        // check image video by id 
        $("#btn-check-image").on('click', function() {
            $("#error-image").text("")
            const value = $("input[name='image']").val();
            if (value) {
                $.ajax('/api/common/video/getinfo/' + value).done(res => {
                    if (res.status) {
                        const thumbnail = res.data.thumbnail
                        $("#privew-image").html(`
                        <img style="width: 100%;height:100%" class="w-100 h-100 object-fit-cover rounded-2"
                                            src="${thumbnail}" alt="">
                        `)
                    } else {
                        $("#error-image").text(res.message)
                    }
                })
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
