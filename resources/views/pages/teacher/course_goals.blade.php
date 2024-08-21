@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Intended learners
    </div>
    <div class="">

        @if (session('error'))
            <div class="alert alert-danger mt-2 py-2 text-white">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mt-2 py-2 text-white">{{ session('success') }}</div>
        @endif
    </div>
    <form method="POST" class="p-4" action="{{ route('course.manage._goals', ['id' => $course->id]) }}">
        @csrf
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
                    @forelse ($data['whatlearns'] as $item)
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="{{ $item->id }}" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]" value="{{ $item->content }}"
                                    placeholder="Example: Identify and manage project risks">
                                <a onclick="return confirm('Do you want to delete this?')"
                                    href="{{ route('course.manage._goals.delete', ['id' => $course->id, 'goal_id' => $item->id]) }}"
                                    class="position-absolute cursor-pointer " style="top:-10px;right:-5px">
                                    <i class="fa-regular fa-circle-xmark fs-5"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Identify and manage project risks">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">

                                </span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Identify and manage project risks">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">

                                </span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Identify and manage project risks">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">

                                </span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Identify and manage project risks">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">

                                </span>
                            </div>
                        </div>
                    @endforelse


                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore" data='whatlearn'>
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="form-label fs-6 mt-3">What are the requireds or prerequisites for taking your course?</label>
            <p class="fs-7">List the required skills, experience, tools or equipment learners should have prior to taking
                your course.</p>
            <div class="content-body">
                <div class="list-content">
                    @forelse ($data['requires'] as $item)
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="{{ $item->id }}" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]" value="{{ $item->content }}"
                                    placeholder="Example: Identify and manage project risks">
                                <a onclick="return confirm('Do you want to delete this?')"
                                    href="{{ route('course.manage._goals.delete', ['id' => $course->id, 'goal_id' => $item->id]) }}"
                                    class="position-absolute cursor-pointer " style="top:-10px;right:-5px">
                                    <i class="fa-regular fa-circle-xmark fs-5"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="require" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: No programming experience needed. You will learn">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                </span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="require" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: No programming experience needed. You will learn">

                            </div>
                        </div>
                    @endforelse

                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore" data='require'>
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <label class="form-label fs-6 mt-3">Who this this course for?</label>
            <p class="fs-7">Write a clear description of the intended learners for your course who will find your course
                content valuable.</p>
            <div class="content-body">
                <div class="list-content">
                    @forelse ($data['whofors'] as $item)
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="{{ $item->id }}" hidden>
                                <input type="text" name="type[]" value="whatlearn" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    value="{{ $item->content }}"
                                    placeholder="Example: Identify and manage project risks">
                                <a onclick="return confirm('Do you want to delete this?')"
                                    href="{{ route('course.manage._goals.delete', ['id' => $course->id, 'goal_id' => $item->id]) }}"
                                    class="position-absolute cursor-pointer " style="top:-10px;right:-5px">
                                    <i class="fa-regular fa-circle-xmark fs-5"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whofor" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Beginner Python developers curious about data...">
                                <span class="position-absolute cursor-pointer" style="top:-10px;right:-5px">
                                </span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <div class="position-relative">
                                <input type="text" name="id[]" value="" hidden>
                                <input type="text" name="type[]" value="whofor" hidden>
                                <input type="text" class="form-control" name="content[]"
                                    placeholder="Example: Beginner Python developers curious about data...">

                            </div>
                        </div>
                    @endforelse


                </div>
                <div>
                    <div class="btn btn-outline-primary border-0 px-0 btn-addmore" data="whofor">
                        <i class="fa-solid fa-plus"></i>
                        <span class="ms-2">Add more to your response</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top pt-4 mt-3">
            <button type="submit" class="btn btn-success w-100">Save </button>
        </div>

    </form>
@endsection
@section('js')
    <script>
        $(".btn-addmore").click(function() {
            const parent = $(this).closest('.content-body');
            const listContent = parent.find(".list-content")
            const type = $(this).attr('data')
            $(listContent).append(`
                    <div class="form-group my-3">
                        <div class="position-relative">
                            <input type="text" name="id[]" value="" hidden>
                            <input type="text" name="type[]" value="${type}" hidden>
                            <input type="text" name='content[]' class="form-control"
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
        $('.btn-delete-add').on('click', function() {
            $(this).closest('.form-group').remove();
        })
    </script>
@endsection
