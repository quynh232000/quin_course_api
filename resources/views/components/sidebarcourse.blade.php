<div class="d-flex flex-column gap-4 pt-3">

    <div class="border-bottom pb-2">
        <div class="fw-bold  text-dark ">Plan your course</div>
        <a href="/course/{{ $course->id }}/manage/goals"
            class=" d-flex flex-column {{ Route::currentRouteName() == 'course.manage.goals' ? 'btn-active-primary' : '' }}">
            <div class="d-flex gap-2 align-items-center p-2 card-hover fw-bold">
                <div style="width: 18px;height:18px"
                    class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    @if ($course->progress()['progress']['intend'])
                        <i class="fa-solid fa-check text-xs"></i>
                    @endif
                </div>
                <span class="fs-7 ">Intended learners</span>
            </div>
        </a>
    </div>
    <div class="border-bottom pb-2">
        <div class="fw-bold  text-dark">Create your content</div>


        <a href="/course/{{ $course->id }}/manage/curriculum"
            class=" d-flex flex-column {{ Route::currentRouteName() == 'course.manage.course_curriculum' ? 'btn-active-primary' : '' }}">
            <div class="d-flex gap-2 align-items-center p-2 card-hover fw-bold">
                <div style="width: 18px;height:18px"
                    class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    @if ($course->progress()['progress']['curriculum'])
                        <i class="fa-solid fa-check text-xs"></i>
                    @endif
                </div>
                <span class="fs-7 ">Curriculum</span>
            </div>
        </a>
    </div>
    <div>
        <div class="fw-bold  text-dark">Publish your course</div>
        <div class=" d-flex flex-column ">
            <a href="/course/{{ $course->id }}/manage/basics"
                class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{ Route::currentRouteName() == 'course.manage.course_basics' ? 'btn-active-primary' : '' }}">
                <div style="width: 18px;height:18px"
                    class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    @if ($course->progress()['progress']['landing'])
                        <i class="fa-solid fa-check text-xs"></i>
                    @endif
                </div>
                <span class="fs-7 ">Course landing page</span>
            </a>
            <a href="/course/{{ $course->id }}/manage/pricing"
                class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{ Route::currentRouteName() == 'course.manage.course_pricing' ? 'btn-active-primary' : '' }}">
                <div style="width: 18px;height:18px"
                    class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    @if ($course->progress()['progress']['pricing'])
                        <i class="fa-solid fa-check text-xs"></i>
                    @endif
                </div>
                <span class="fs-7 ">Pricing</span>
            </a>
            <a href="/course/{{ $course->id }}/manage/certificate"
                class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{ Route::currentRouteName() == 'course.manage.course_certificate' ? 'btn-active-primary' : '' }}">
                <div style="width: 18px;height:18px"
                    class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    @if ($course->progress()['progress']['certificate'])
                        <i class="fa-solid fa-check text-xs"></i>
                    @endif
                </div>
                <span class="fs-7 ">Certificate</span>
            </a>
        </div>
    </div>
    <div class="mt-3">
        @if ($course->progress()['total_percent'] == 100)
            <a href="{{ route('preview', ['id' => $course->id]) }}" class="btn btn-primary w-100" type="submit">Submit for
                Review
            </a>
        @else
            <button class="btn btn-primary w-100" data-bs-toggle="modal"
                data-bs-target="#exampleModal">Submit for
                Review
            </button>
        @endif
    </div>


    {{-- modal --}}

    {{-- <button type="button" class="btn btn-primary" >
        Launch demo modal
    </button> --}}

    <!-- Modal -->
    <div class="modal fade  " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Why can't submit for review?</h1>
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Here are few more items you need to complete.</span>

                    <div class="my-2">
                        <ul>
                            @php
                                $url = [
                                    'intend' => 'goals',
                                    'curriculum' => 'curriculum',
                                    'landing' => 'basics',
                                    'pricing' => 'pricing',
                                    'certificate' => 'certificate',
                                ];
                            @endphp
                            @foreach ($course->progress()['data'] as $key => $item)
                                @if (count($item) > 0)
                                    <li class="mb-2">
                                        <div class="">
                                            <div class="fw-bold fs-7 mb-1">On the <a
                                                    href="/course/{{ $course->id }}/manage/{{ $url[$key] }}"
                                                    class="text-primary">{{ $key }}</a> page ,you must</div>
                                            @foreach ($item as $mess)
                                                <div class=" fs-7">+ {{ $mess }}</div>
                                            @endforeach
                                        </div>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- modal --}}

</div>
