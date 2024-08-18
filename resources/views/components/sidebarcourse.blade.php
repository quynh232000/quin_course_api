<div class="d-flex flex-column gap-4 pt-3">
    
    <div class="border-bottom pb-2">
        <div class="fw-bold  text-dark ">Plan your course</div>
        <a href="/course/{{$course->id}}/manage/goals" class=" d-flex flex-column {{Route::currentRouteName() == 'course.manage.goals' ? 'btn-active-primary':''}}">
            <div class="d-flex gap-2 align-items-center p-2 card-hover fw-bold">
                <div style="width: 18px;height:18px" class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    {{-- <i class="fa-solid fa-check text-xs"></i> --}}
                </div>
                <span class="fs-7 ">Intended learners</span>
            </div>
        </a>
    </div>
    <div class="border-bottom pb-2">
        <div class="fw-bold  text-dark">Create your content</div>
      
      
        <a href="/course/{{$course->id}}/manage/curriculum" class=" d-flex flex-column {{Route::currentRouteName() == 'course.manage.course_curriculum' ? 'btn-active-primary':''}}">
            <div class="d-flex gap-2 align-items-center p-2 card-hover fw-bold">
                <div style="width: 18px;height:18px" class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    <i class="fa-solid fa-check text-xs"></i>
                </div>
                <span class="fs-7 ">Curriculum</span>
            </div>
        </a>
    </div>
    <div>
        <div class="fw-bold  text-dark">Publish your course</div>
        <div class=" d-flex flex-column ">
            <a href="/course/{{$course->id}}/manage/basics" class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{Route::currentRouteName() == 'course.manage.course_basics' ? 'btn-active-primary':''}}">
                <div style="width: 18px;height:18px" class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    {{-- <i class="fa-solid fa-check text-xs"></i> --}}
                </div>
                <span class="fs-7 ">Course landing page</span>
            </a>
            <a href="/course/{{$course->id}}/manage/pricing" class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{Route::currentRouteName() == 'course.manage.course_pricing' ? 'btn-active-primary':''}}">
                <div style="width: 18px;height:18px" class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    {{-- <i class="fa-solid fa-check text-xs"></i> --}}
                </div>
                <span class="fs-7 ">Pricing</span>
            </a>
            <a href="/course/{{$course->id}}/manage/certificate" class="d-flex gap-2 align-items-center p-2 card-hover fw-bold {{Route::currentRouteName() == 'course.manage.course_certificate' ? 'btn-active-primary':''}}">
                <div style="width: 18px;height:18px" class="rounded-circle border d-flex justify-content-center align-items-center border-dark">
                    {{-- <i class="fa-solid fa-check text-xs"></i> --}}
                </div>
                <span class="fs-7 ">Certificate</span>
            </a>
        </div>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary w-100">Submit for Review</button>
    </div>


</div>