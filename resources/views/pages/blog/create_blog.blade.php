@extends('layouts.appLayout')
@push('js1')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Add new Bog
    @endif
@endsection
@push('css')
    <style>
        .tag-close {
            /* display: none; */
            top: -15px;
            right: 0px;
            border-radius: 50%;
            transition: .3s ease
        }

        .tag-close:hover {
            color: red
        }

        .tag-body:hover .tag-close {
            display: inline-block;
        }
    </style>
@endpush
@section('main')
    <div class="container-fluid py-4">
        <div class="card">
            <form method="POST" enctype="multipart/form-data" class="row "
                action="{{ isset($blog) ? route('admin.blog._update', ['id' => $blog->id]) : '' }}">
                @csrf
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="fw-bold mb-3 text-uppercase">
                                Create new Blog
                            </div>
                        </div>
                        <div>
                            @if (session('error'))
                                <div class="alert alert-danger text-white py-2">{{ session('error') }}</div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success text-white py-2">{{ session('success') }}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_published" class="form-control-label">Publish</label>
                                                <div class="form-check form-switch">
                                                    @if (isset($blog))
                                                        <input class="form-check-input" name="is_published" type="checkbox"
                                                            {{ $blog->is_published ? 'checked' : '' }}>
                                                    @else
                                                        <input class="form-check-input" name="is_published" type="checkbox">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rememberMe" class="form-control-label">Show</label>
                                                <div class="form-check form-switch">
                                                    @if (isset($blog))
                                                        @if ($blog->is_show)
                                                            <input class="form-check-input" type="checkbox" name="is_show"
                                                                checked id="rememberMe">
                                                        @else
                                                            <input class="form-check-input" type="checkbox" name="is_show"
                                                                id="rememberMe">
                                                        @endif
                                                    @else
                                                        <input class="form-check-input" type="checkbox" name="is_show"
                                                            checked id="rememberMe">
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Title</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('title') ? old('title') : (isset($blog) ? $blog->title : '') }}"
                                            name="title" placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Subtitle</label>
                                        @if (isset($blog))
                                            <textarea name="subtitle" class="form-control" id="" rows="3" value="">{{ $blog->subtitle }}</textarea>
                                        @else
                                            <textarea name="subtitle" class="form-control" id="" rows="3" value="">{{ old('subtitle') }}</textarea>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="" class="form-label">Tags</label>
                                                <select name="tag" id="" class="form-select">
                                                    <option value="">--select--</option>
                                                    @foreach ($tags as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-9 d-flex align-items-end gap-3 pb-4">
                                            @if (isset($blog))
                                                @foreach ($blog->tags as $item)
                                                    <div class="position-relative tag-body">
                                                        <span class="text-xs border p-2 rounded-2 text-primary">
                                                            #{{ $item->tag->slug }}</span>
                                                        <a href="{{ route('admin.blog.deletetag', ['id' => $blog->id, 'tag_id' => $item->id]) }}"
                                                            class="position-absolute tag-close"><i
                                                                class="fa-regular fa-circle-xmark"></i></a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Content</label>
                                        <div id="editor-container" style="min-height: 220px">{!! isset($blog) ? $blog->content : '' !!}</div>
                                        <input type="hidden" value="{{ isset($blog) ? $blog->content : '' }}"
                                            name="content" id="description">
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <label for="thumbnail_url">
                            <div id="img_thumb">
                                @if (isset($blog))
                                    <img src="{{ $blog->thumbnail_url }}" alt="Image placeholder"
                                        class="img-custom object-fit-cover">
                                @else
                                    <img src="https://img.pikbest.com/origin/09/30/65/27hpIkbEsTzdI.jpg!sw800"
                                        alt="Image placeholder" class="img-custom object-fit-cover">
                                @endif
                            </div>
                        </label>
                        <input type="file" name="thumbnail_url" accept="image/*" id="thumbnail_url" hidden>
                        <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                            <div class="d-flex justify-content-center">
                                <label for="thumbnail_url" class="btn btn-dark btn-sm"><i
                                        class="fa-solid fa-upload me-2"></i>Upload Banner</label>
                            </div>
                        </div>


                        <script>
                            $("#thumbnail_url").change(function(e) {

                                const [file] = e.target.files
                                if (file) {
                                    const url = URL.createObjectURL(file)
                                    $(".img-custom").attr('src', url)

                                }
                            })
                        </script>


                    </div>
                    <div class="mt-5">
                        <button class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow' // or 'bubble'
        });

        var form = document.querySelector('form');
        form.onsubmit = function() {
            var contentInput = document.querySelector('input[name="content"]');
            contentInput.value = quill.root.innerHTML;
        };
    </script>
@endsection
