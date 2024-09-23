@extends('layouts.appLayout')
@section('viewTitle')
    Teacher -
    {{ auth('admin')->user()->full_name }}
@endsection

@section('main')
    <div>
        <div class="card shadow-lg mx-4 ">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img width="74px" height="74px" src="{{ auth('admin')->user()->avatar_url }}" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm object-fit-cover">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ auth('admin')->user()->full_name }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                {{ auth('admin')->user()->email }}

                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                        <i class="ni ni-app"></i>
                                        <span class="ms-2">App</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                        <i class="ni ni-email-83"></i>
                                        <span class="ms-2">Messages</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span class="ms-2">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0">Update profile to become a teacher</h4>
                                <button class="btn btn-primary btn-sm ms-auto">Settings</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="row" method="POST">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Bank name</label>

                                        <select name="bank_id" class="form-select" id="">
                                            <option value="">--Select--</option>
                                            @foreach ($banks as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $teacherinfo && $teacherinfo->bank && $teacherinfo->bank->id == $item->id ? 'selected' : '' }}>

                                                    {{ $item->symbol }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Card number</label>
                                        <input class="form-control" type="number" name="card_number" placeholder="6351.."
                                            value="{{ old('card_number') ? old('card_number') : ($teacherinfo && $teacherinfo->card_number ? $teacherinfo->card_number : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Momo Number</label>
                                        <input class="form-control" type="number" name="momo_number"
                                            value="{{ old('momo_number') ? old('momo_number') : ($teacherinfo && $teacherinfo->momo_number ? $teacherinfo->momo_number : '') }}"
                                            placeholder="09..">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Major</label>
                                                <input class="form-control" type="text" name="major"
                                                    value="{{ old('major') ? old('major') : ($teacherinfo && $teacherinfo->major ? $teacherinfo->major : '') }}"
                                                    placeholder="Doctor, photographer,..">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Position</label>
                                                <input class="form-control" type="text" name="position"
                                                    value="{{ old('position') ? old('position') : ($teacherinfo && $teacherinfo->position ? $teacherinfo->position : '') }}"
                                                    placeholder="Manager, Senior">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Description</label>

                                        <textarea name="description" id="" class="form-control" placeholder="Your introduction.." rows="5">{{ old('description') ? old('description') : ($teacherinfo && $teacherinfo->description ? $teacherinfo->description : '') }}</textarea>
                                    </div>
                                </div>



                                <div>
                                    @if (session('error'))
                                        <div class="alert alert-danger py-2 text-white">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success py-2 text-white">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mt-2 ">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>
@endsection
