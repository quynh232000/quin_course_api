@extends('layouts.previewLayout')
@push('css')
    <style>
        .btn-video {
            background-color: rgba(238, 235, 235, .3);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: none;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: .3s ease;
            /* color: var(--primary); */
            color: white;
            position: absolute;
            top: 50%;
            transform: translateY(-50%)
        }

        .btn-video:hover {
            background-color: var(--primary);
            color: white
        }

        .btn-video:hover .btn-video>i {
            transform: scale(1.2)
        }

        .btn-video.prev {
            left: 10px
        }

        .btn-video.next {
            right: 10px
        }

        .video-body:hover .btn-video {
            display: flex;
        }
    </style>
@endpush
@section('content')
    <div class="d-flex h-100 flex-column flex-1">
        <div class="" style="height:70vh">
            @if ($step->type == 'lecture')
                @if ($step->lecture && $step->lecture->video_type == 'youtube')
                    <div class=" h-100 position-relative video-body" style="">
                        <iframe width="100%" height="100%" src="{{ $step->lecture->video_url }}" title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        <div class="btn-video prev"><i class="fa-solid fa-arrow-left"></i></div>
                        <div class="btn-video next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center pt-8">
                        <div>
                            <div>Attention: This part of the course has nothing to show right now</div>
                            <div class="mt-3 d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm"
                                    href="/course/{{ $course->id }}/manage/curriculum/section/{{ $step->section->id }}/{{ $step->type }}/{{ $step->id }}">Update
                                    content</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            @if ($step->type == 'quiz')
                @if ($step->question)
                    <div class="row d-flex h-100 border-bottom position-relative video-body">
                        <div class="col-md-5 border-end p-4">
                            <strong>Question:</strong>
                            <p>{!! $step->question->content !!}</p>
                        </div>
                        <div class="col-md-7 p-4">
                            <strong>Answer:</strong>
                            <div class="mt-4">
                                @foreach ($step->answers as $key => $item)
                                    <label for="answer{{ $key }}"
                                        class="d-flex gap-3 border rounded-2 p-2 px-3 align-items-center mb-3">
                                        <input id="answer{{ $key }}" type="radio" name="anwser">
                                        <div class="fs-6 mb-0">{{ $item->content }}</div>
                                    </label>
                                @endforeach

                            </div>
                            <div class="mt-4 d-flex ">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <div class="btn-video prev"><i class="fa-solid fa-arrow-left"></i></div>
                        <div class="btn-video next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center pt-8">
                        <div>
                            <div>Attention: This part of the course has nothing to show right now</div>
                            <div class="mt-3 d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm"
                                    href="/course/{{ $course->id }}/manage/curriculum/section/{{ $step->section->id }}/{{ $step->type }}/{{ $step->id }}">Update
                                    content</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            @if ($step->type == 'article')
                <div class="row d-flex h-100 border-bottom position-relative video-body overflow-scroll">
                    <h3 class="px-5 mt-3">{{$step->title}}</h3>
                    <div class="p-5 ">
                        {!! $step->article->content !!}
                    </div>
                </div>
            @endif



        </div>
        <div class="border-bottom border-top">
            <div class="p-4 d-flex align-items-center ">
                <div class="flex-1">
                    <h4>{{ $step->title }}
                    </h4>
                    <span class="fs-7">Published:
                        {{ $course->published_at ? \Carbon\Carbon::parse($course->published_at)->format('d/m/Y') : 'Editing' }}</span>
                </div>
                <div class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-plus me-2"></i><span>Add note at
                        02:05</span></div>
            </div>
            <div class="">
                <div class="border-top border-bottom px-4 d-flex  ">
                    <div class="p-2 px-4 btn-hover-primary">
                        <a href="#" class="fw-bold ">Overview</a>
                    </div>
                    <div class="p-2 px-4 btn-hover-primary">
                        <a href="#" class="fw-bold ">Notes</a>
                    </div>
                    <div class="p-2 px-4 btn-hover-primary">
                        <a href="#" class="fw-bold ">Comments</a>
                    </div>
                </div>
                <div>
                    @if ($step->type == 'lecture' && $step->lecture)
                        <div class="row p-4">
                            <div class="col-md-2">Description</div>
                            <div class="col-md-10">
                                {!! $step->lecture->description !!}
                            </div>
                        </div>
                    @endif

                    <div class="row p-4 border-top">
                        <div class="col-md-2">Intended learners</div>
                        <div class="col-md-10">
                            @foreach ($course->intends as $item)
                                <p>{{ $item->content }}</p>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
