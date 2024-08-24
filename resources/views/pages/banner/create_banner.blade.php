@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Add new Banner
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">
        <div class="card">
            <form method="POST" enctype="multipart/form-data" class="row "
                action="{{ isset($banner) ? route('admin.banner._update', ['id' => $banner->id]) : '' }}">
                @csrf
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="fw-bold mb-3 text-uppercase">
                                Create new banner
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Title</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('title') ? old('title') : (isset($banner)? $banner->title : '') }}"
                                            name="title" placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alt (For SEO)</label>
                                        <input class="form-control" type="text" placeholder="Aa.."
                                            value="{{ old('alt') ? old('alt') : (isset($banner)? $banner->alt : '') }}"
                                            name="alt">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Description</label>
                                        <textarea class="form-control" placeholder="Aa.." name="description" id="" rows="5">{{ old('description') ? old('description') : (isset($banner)? $banner->description : '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Where apperance</label>
                                        <input class="form-control" type="text" name="placement"
                                            value="{{ old('placement') ? old('placement') : (isset($banner)? $banner->placement : '') }}"
                                            placeholder="home, collection">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Link to</label>
                                        <input class="form-control" type="text" name="link_to"
                                            value="{{ old('link_to') ? old('link_to') : (isset($banner)? $banner->link_to : '') }}"
                                            placeholder="Href link banner to...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Type banner</label>
                                        <input class="form-control" type="text" name="type"
                                            value="{{ old('type') ? old('type') : (isset($banner)? $banner->type : '') }}"
                                            placeholder="Slider, ads,...">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Priority</label>
                                        <input class="form-control" type="number"
                                            value="{{ old('priority') ? old('priority') : (isset($banner)? $banner->priority : '') }}"
                                            name="priority" placeholder="0,1,2,..">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date expired</label>
                                        <input class="form-control" type="date"
                                            value="{{ old('expired_at') ? old('expired_at') : (isset($banner)? $banner->expired_at : '') }}"
                                            name="expired_at" placeholder="0,1,2,..">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_blank" class="form-control-label">Is blank</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="is_blank" type="checkbox"
                                                        {{ old('is_blank') ? 'checked' : (isset($banner)? ($banner->is_blank ? 'checked' : '') : '') }}
                                                        checked id="is_blank">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rememberMe" class="form-control-label">Show banner</label>
                                                <div class="form-check form-switch">
                                                    @if (isset($banner))
                                                        @if ($banner->is_show)
                                                            <input class="form-check-input" type="checkbox" name="is_show" checked
                                                                id="rememberMe">
                                                        @else
                                                            <input class="form-check-input" type="checkbox" name="is_show"
                                                                id="rememberMe">
                                                        @endif
                                                    @else
                                                        <input class="form-check-input" type="checkbox" name="is_show" checked
                                                            id="rememberMe">
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <label for="image_banner">
                            @if (isset($banner))
                                <img src="{{ $banner->banner_url }}" alt="Image placeholder"
                                    class="card-img-top object-fit-cover">
                            @else
                                <img src="https://img.pikbest.com/origin/09/30/65/27hpIkbEsTzdI.jpg!sw800"
                                    alt="Image placeholder" class="card-img-top object-fit-cover">
                            @endif
                        </label>
                        <input type="file" name="image" accept="image/*" id="image_banner" hidden>
                        <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                            <div class="d-flex justify-content-center">
                                <label for="image_banner" class="btn btn-dark btn-sm"><i
                                        class="fa-solid fa-upload me-2"></i>Upload Banner</label>
                            </div>
                        </div>


                        <script>
                            $("#image_banner").change(function(e) {

                                const [file] = e.target.files
                                if (file) {
                                    const url = URL.createObjectURL(file)
                                    $(".card-img-top").attr('src', url)

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
