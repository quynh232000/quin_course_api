@extends('layouts.editcourseLayout')
@section('viewTitle')
{{$course->title}} | Quin
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Intended learners
    </div>
    <div class="p-4">
        <p class="fs-7">
            The following descriptions will be publicly visible on your
            <a href="/course/1/manage/basics" class="text-primary text-decoration-underline">Course Landing
                Page</a>
            and will have a direct impact on your course performance. These descriptions will help learners
            decide if your course is right for them.
        </p>
        <div>
            <label class="form-label fs-6 mt-3">What will students learn in your course?</label>
            <p class="fs-7">You must enter at least 4 learning objectives or outcomes that learners can expect to achieve
                after completing your course.</p>
            <div class="content-body">
                <div class="list-content">
                    <div class="form-group my-1">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Identify and manage project risks">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                {{-- <i class="fa-regular fa-circle-xmark fs-5"></i> --}}
                            </span>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Identify and manage project risks">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                {{-- <i class="fa-regular fa-circle-xmark fs-5"></i> --}}
                            </span>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Identify and manage project risks">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                {{-- <i class="fa-regular fa-circle-xmark fs-5"></i> --}}
                            </span>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Identify and manage project risks">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                {{-- <i class="fa-regular fa-circle-xmark fs-5"></i> --}}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore">
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="form-label fs-6 mt-3">What are the requireds or prerequisites for taking your course?</label>
            <p class="fs-7">List the required skills, experience, tools or equipment learners should have prior to taking your course.</p>
            <div class="content-body">
                <div class="list-content">
                    <div class="form-group my-1">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: No programming experience needed. You will learn">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                            </span>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: No programming experience needed. You will learn">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                            </span>
                        </div>
                    </div>
                    
                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore">
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <label class="form-label fs-6 mt-3">What this this course for?</label>
            <p class="fs-7">Write a clear description of the intended learners for your course who will find your course content valuable.</p>
            <div class="content-body">
                <div class="list-content">
                    <div class="form-group my-1">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Beginner Python developers curious about data...">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                            </span>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Beginner Python developers curious about data...">
                            <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                            </span>
                        </div>
                    </div>
                    
                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore">
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top pt-4 mt-3">
            <button class="btn btn-success w-100">Save </button>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $(".btn-addmore").click(function() {
            const parent = $(this).closest('.content-body');
            const listContent = parent.find(".list-content")
            $(listContent).append(`
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" class="form-control"
                                placeholder="Example: Identify and manage project risks">
                            <span class="position-absolute cursor-pointer btn-delete-add" style="top:-10px;right:-5px">
                                 <i class="fa-regular fa-circle-xmark fs-5"></i> 
                            </span>
                        </div>
                    </div>
            `)
            $('.btn-delete-add').on('click', function() {
                $(this).closest('.form-group').remove();
            })
        })
    </script>
@endsection
