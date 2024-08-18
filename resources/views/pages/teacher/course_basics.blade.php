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
    <div class="p-4">
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
                    <input type="text" class="form-control" name="subtitle" value="{{ $course->subtitle }}">
                    <div class="fs-8">Description should have minimum 200 words.</div>
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
                                <select name="category_id" class="form-select category_id"
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
                                <select name="category_id" class="form-select category_id"
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
                            <label for="image"
                                class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                                <i class="fa-regular fa-image" style="font-size: 80px"></i>
                                {{-- <img style="width: 100%;height:100%" class="w-100 h-100 object-fit-cover rounded-2" src="https://cdn.britannica.com/70/234870-050-D4D024BB/Orange-colored-cat-yawns-displaying-teeth.jpg" alt=""> --}}
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
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" accept="image/*" id="inputimage"
                                    placeholder="id video youtube">
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
                            <label for="video"
                                class=" w-100 h-100 d-flex justify-content-center align-items-center border   rounded-2">
                                <i class="fa-brands fa-youtube" style="font-size: 80px"></i>
                                {{-- <img style="width: 100%;height:100%" class="w-100 h-100 object-fit-cover rounded-2" src="https://cdn.britannica.com/70/234870-050-D4D024BB/Orange-colored-cat-yawns-displaying-teeth.jpg" alt=""> --}}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <p class="fs-8 lh-sm">Upload your course image here. It must meet our course image quality
                            standards
                            to be accepted.
                            Important guidelines: 750x422 pixels; .jpg, .jpeg,. gif, or .png. no text on the image.</p>
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

        <div class="border-top pt-4 mt-3">
            <button class="btn btn-success w-100">Save</button>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow' // or 'bubble'
        });

        // var form = document.querySelector('form');
        // form.onsubmit = function() {
        //     var contentInput = document.querySelector('#content');
        //     contentInput.value = quill.root.innerHTML;
        // };

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
                                        <select name="category_id" class="form-select category_id">
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
            } else {
                $("#inputimage").attr('type', 'text')
            }
        })
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
