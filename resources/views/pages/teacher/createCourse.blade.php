@extends('layouts.appLayout')
@section('viewTitle')
    Create couse
@endsection

@section('main')
    <div class="container-fluid py-4">
        <div class="card shadow-lg mx-4 " style="border-bottom: 2px solid var(--primary)">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>Step {{ $step }} of 3</div>
                <a href="/" class="btn btn-sm btn-outline-warning  " disabled>Exist</a>
            </div>
        </div>
        <div class="container-fluid py-4">
            {{-- <iframe width="560" height="315" src="https://www.youtube.com/embed/AYaJX1fcMEg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
            <div class="row">
                <div class="col-md-12">
                    <form class="card" method="POST" action="/course/create/{{ $step }}">
                        @csrf
                        <div class="card-body">
    
                            @switch($step)
                                @case(1)
                                    <h4 class="text-center mb-5">
                                        First, let's find out what type of course your're making. </h4>
                                @break
    
                                @case(2)
                                    <div class="text-center mb-5">
                                        <h4 class="text-center mb-2">
                                            How about a working title? </h4>
                                        <small class="">It's ok if you can't think of a good title now. You can change it
                                            later.</small>
                                    </div>
                                @break
    
                                @case(3)
                                    <div class="text-center mb-5">
                                        <h4 class="text-center mb-2">
                                            What category best fits the knowledge you'll share? </h4>
                                        <small class="">If you're not sure about the right category, you can change it
                                            later.</small>
                                    </div>
                                @break
    
                                <h4 class="text-center mb-5">
                                    First, let's find out what type of course your're making. </h4>
    
                                @default
                            @endswitch
    
                            <div class="row d-flex justify-content-center gap-4">
                                @switch($step)
                                    @case(1)
                                        <label for="type1"
                                            class="card col-5 col-sm-4 col-lg-2 text-center border-2 rounded-2 p-3 card-hover typecard">
                                            <div class="card-body">
                                                <div class="fs-3 mb-2">
                                                    <i class="fa-solid fa-clapperboard"></i>
                                                </div>
                                                <strong class="py-2 fw-bold ">Course</strong>
                                                <p class="fw-bold text-xs mt-2">
                                                    Create rich learning experiences
                                                    with the help of video
                                                    lectures,quizzes, coding
                                                    exercises, etc.
                                                </p>
                                            </div>
                                            <input type="radio" name="typecourse" hidden id="type1" value="course">
                                        </label>
                                        <label for="type2"
                                            class=" card col-5 col-sm-4 col-lg-2 text-center border-2 rounded-2 p-3 card-hover typecard ">
                                            <div class="card-body">
                                                <div class="fs-3 mb-2">
                                                    <i class="fa-regular fa-rectangle-list"></i>
                                                </div>
                                                <strong class="py-2 fw-bold ">Practice Test</strong>
                                                <p class="fw-bold text-xs mt-2">
                                                    Help students prepare for cetification exams by providing practice questions.
                                                </p>
                                            </div>
                                            <input type="radio" name="typecourse" hidden id="type2" value="practice">
                                        </label>
                                    @break
    
                                    @case(2)
                                        <div class="col-md-6">
                                            <div class="form-group position-relative">
                                                <input type="text" name="title" class="form-control " id="inputtitle"
                                                    placeholder="e.g.Learning Javascript Basic">
                                                <span class="position-absolute text-sm " id="count-title"
                                                    style="top: 50%;right:10px;transform:translateY(-50%)">60</span>
                                            </div>
                                        </div>
                                    @break
    
                                    @case(3)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="category_id" class="form-select" id="inputselect">
                                                    <option value="">Choose a category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                        </div>
                                    @break
                                @endswitch
                            </div>
                            <div>
                                @if (session('error'))
                                    <div class="alert alert-danger py-2 mt-4 text-white">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
    
                            <div class="d-flex justify-content-between pt-5">
    
                                @if ($step > 1)
                                    <div class="mt-4">
                                        <a href="/course/create/{{ $step - 1 }}" class="btn btn-warning">Previous</a>
                                    </div>
                                @endif
                                <div class="mt-4" id="btn-bottom">
                                    {{-- <button type="submit" class="btn btn-success">Continue</button> --}}
                                    <button disabled class="btn btn-disabled">Continue</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(".typecard").click(function() {
            $(".typecard").each(function() {
                $(this).removeClass(' border-primary')
            })
            $(this).addClass(' border-primary')
            $("#btn-bottom").html(`<button type="submit" class="btn btn-success">Continue</button>`)
        })
        $('#inputtitle').on('input', function(e) {
            const value = $(this).val()
            const len = value.length
            $('#count-title').text(60 - len)
            if (len > 60) {
                $('#inputtitle').addClass(' border-danger')
                $("#btn-bottom").html(` <button disabled class="btn btn-disabled">Continue</button>`)
            } else if (len > 5) {
                $("#btn-bottom").html(`<button type="submit" class="btn btn-success">Continue</button>`)
                $('#inputtitle').removeClass(' border-danger')
            } else {
                $("#btn-bottom").html(` <button disabled class="btn btn-disabled">Continue</button>`)
            }


        })

        $('#inputselect').change(function() {
            console.log('====================================');
            console.log(123);
            console.log('====================================');
            $("#btn-bottom").html(`<button type="submit" class="btn btn-success">Continue</button>`)
        })
    </script>
@endsection
