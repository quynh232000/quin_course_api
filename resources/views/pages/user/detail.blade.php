@extends('layouts.appLayout')
@section('viewTitle')
    Account: {{ $user->email }}
@endsection
@section('main')
    <div class="card shadow-lg mx-4 ">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative" style="width: 74px;height:74px">
                        <img width="74px" height="74px" src="{{ $user->avatar_url }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm object-fit-cover">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <div class="d-flex">
                            <h5 class="mb-1">
                                {{ $user->full_name }}
                            </h5>

                        </div>
                        <div class="mb-0 font-weight-bold text-sm d-flex">
                            {{ $user->email }}

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">Courses</span>
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
        <div>
            @if (session('success'))
                <div class="alert alert-success text-white py-2">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-white py-2">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        {{-- <div class="d-flex align-items-center">
                            <p class="mb-0">Edit User</p>
                            <button class="btn btn-primary btn-sm ms-auto">Settings</button>
                        </div> --}}

                        <div>
                            <div class="d-flex gap-2 border-bottom py-2">
                                <span style="min-width: 160px">Log status:</span>

                                <form method="POST" action="/users/{{ $user->uuid }}/status" id="form_status"
                                    class="form-check form-switch">
                                    @csrf
                                    <input @checked($user->blocked_until == '') class="form-check-input" type="checkbox"
                                        id="status_user">
                                    <label class="form-check-label" for="status_user">
                                        <div class="ms-2">
                                            @if ($user->blocked_until == '')
                                                <span class="badge badge-sm bg-gradient-success">Actived</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger">Blocked</span>
                                            @endif
                                        </div>
                                    </label>

                                </form>

                            </div>

                            <div class="d-flex gap-2 border-bottom py-2 justify-content-between">
                                <div class="d-flex gap-2">
                                    <span style="min-width: 160px">Comment status:</span>

                                    <form method="POST" action="/users/{{ $user->uuid }}/iscomment" id="form_comment"
                                        class="form-check form-switch">
                                        @csrf
                                        <input @checked($user->is_comment_blocked == 0) class="form-check-input" type="checkbox"
                                            id="comment_user">
                                        <label class="form-check-label" for="comment_user">
                                            <div class="ms-2">
                                                @if ($user->is_comment_blocked)
                                                    <span class="badge badge-sm bg-gradient-danger">Blocked</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">Actived</span>
                                                @endif
                                            </div>
                                        </label>

                                    </form>
                                </div>
                                @if ($user->comment_blocked_at)
                                    <small class="text-secondary">
                                        <span>Blocked At: </span>
                                        <i>{{ $user->comment_blocked_at }}</i>
                                        {{-- <i>{{ explode(' ', $user->comment_blocked_at)[0] }}</i> --}}
                                    </small>
                                @endif
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-control-label">User Roles</label>

                                @if (in_array('Super Admin', auth('admin')->user()->roles()->toArray()))
                                    <form method="POST" action="/users/{{ $user->uuid }}/addrole" class="row d-flex">
                                        @csrf
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <select name="role" class="form-select form-select-sm"
                                                    aria-label="Default select example">
                                                    <option selected value="">--Choose role--</option>
                                                    @foreach ($allRoles as $item)
                                                        @if (!in_array($item->id, $roles->pluck('role_id')->toArray()))
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7 d-flex align-items-end">
                                            <button type="submit" class="btn btn-sm btn-primary">Add Role</button>
                                        </div>
                                    </form>
                                @endif
                                <div>
                                    <table id="table-role-list"
                                        class="table align-items-center justify-content-center mb-0 table-border border table-striped ">
                                        <thead class="" style="background-color: rgba(0, 0, 114, 0.12)">
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-primary font-weight-bolder ">

                                                    <span>Role Name</span>
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-primary font-weight-bolder  ps-2">
                                                    Description</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xs text-primary font-weight-bolder  ps-2 text-end">
                                                    Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2">
                                                            <div class="me-4">
                                                                {{ $loop->index + 1 }}
                                                            </div>
                                                            <div class="my-auto">
                                                                <h6 class="mb-0 text-sm">{{ $item->role->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm  mb-0">{{ $item->role->description }}</p>
                                                    </td>
                                                    <td class="d-flex justify-content-end align-items-center">
                                                        @if ($item->role->name == 'User')
                                                            <button disabled
                                                                class="text-xs font-weight-bold btn btn-sm btn-default px-2 text-gray">Default</button>
                                                        @else
                                                            @if (in_array('Super Admin', auth('admin')->user()->roles()->toArray()))
                                                                <a href="/users/{{ $user->uuid }}/deleterole/{{ $item->role->id }}"
                                                                    type="submit"
                                                                    onclick="return confirm('Are you sure you want to remove this role?')"
                                                                    class="text-xs font-weight-bold btn btn-sm btn-warning px-2 text-white">Remove</a>
                                                            @else
                                                                <button disabled
                                                                    class="text-xs font-weight-bold btn btn-sm btn-warning px-2 text-white">Remove</button>
                                                            @endif
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">


                    <form method="POST" action="/users/{{ $user->uuid }}/updateinfo" class="card-body mt-0 pt-0">
                        <p class="text-uppercase text-sm">User Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Username</label>
                                    <input class="form-control" name="username" type="text"
                                        value="{{ $user->username }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email address</label>
                                    <input readonly disabled class="form-control" type="email"
                                        value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">First name</label>
                                    <input class="form-control" name="first_name" type="text"
                                        value="{{ $user->first_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Last name</label>
                                    <input class="form-control" name="last_name" type="text"
                                        value="{{ $user->last_name }}">
                                </div>
                            </div>

                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Contact Information</p>
                        <div>
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" name="address" type="text"
                                            value="{{ $user->address }}" placeholder="--">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Birthday</label>
                                        <input class="form-control" name="birthday" type="date"
                                            value="{{ $user->birthday }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Phone number</label>
                                        <input class="form-control" type="text" name="phone_number"
                                            value="{{ $user->phone_number }}" placeholder="+84">
                                    </div>
                                </div>

                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Introduction</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Bio (150)</label>
                                        <input class="form-control" name="bio" type="text"
                                            value="{{ $user->bio }}" placeholder="Bio..">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    @if (in_array('Super Admin', auth('admin')->user()->roles()->toArray()))
                                        
                                    <button type="submit" class="btn btn-primary ">Update </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile position-relative">
                    <form method="POST" action="/users/{{ $user->uuid }}/changethumbnail"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="btn-thumbnail">
                            <label for="thumbnail_url"
                                class="position-absolute btn btn-sm btn-wraning z-10 bg-secondary text-white top-1 start-2">
                                <i class="fa-regular fa-image"></i>
                            </label>
                            {{-- <button type="submit" for="thumbnail_url"
                                class="position-absolute btn btn-sm btn-primary z-10  text-white top-1 start-2">
                                Save
                            </button> --}}
                        </div>
                        <input hidden type="file" name="thumbnail_url" accept="image/*" id="thumbnail_url">
                        <label for="thumbnail_url" class="w-100">
                            <img id="thumbnail_preview" height="180px"
                                src="{{ $user->thumbnail_url ?? asset('assets/img/bg-profile.jpg') }}"
                                alt="Image placeholder" class="card-img-top object-fit-cover w-100">
                        </label>
                    </form>

                    <script>
                        $("#thumbnail_url").change(function(e) {

                            const [file] = e.target.files
                            if (file) {
                                const url = URL.createObjectURL(file)
                                $("#thumbnail_preview").attr('src', url)
                                $("#btn-thumbnail").html(`
                                   <button type="submit" for="thumbnail_url"
                                class="position-absolute btn btn-sm btn-primary z-10  text-white top-1 start-2">
                                Save
                            </button>
                              `)
                            }
                        })
                    </script>
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <form method="POST" enctype="multipart/form-data"
                                action="/users/{{ $user->uuid }}/changeavatar"
                                class="mt-n4 mt-lg-n6 mb-4 mb-lg-0 position-relative d-flex flex-column align-items-center">
                                @csrf
                                <label style="width:96px;height:96px" for="avatar_url">
                                    <img width="96px" height="96px" src="{{ $user->avatar_url }}"
                                        class="rounded-circle object-fit-cover  border-2 border-white"
                                        id="avatar_preview">
                                </label>
                                <input type="file" name="avatar_url" accept="image/*" id="avatar_url" hidden>
                                <div class="d-flex justify-content-center" id="btn-avatar">
                                    <label for="avatar_url"
                                        class="d-flex py-1  text-xs  text-center border rounded btn btn-outline-primary">
                                        <span><i class="fa-solid fa-upload "></i> Upload</span>
                                    </label>
                                    {{-- <button for="avatar_url"
                                        class="d-flex py-1 justify-content-center text-xs  text-center border rounded btn btn-primary ">
                                        Save
                                    </button> --}}
                                </div>


                                <script>
                                    $("#avatar_url").change(function(e) {

                                        const [file] = e.target.files
                                        if (file) {
                                            const url = URL.createObjectURL(file)
                                            $("#avatar_preview").attr('src', url)
                                            $("#btn-avatar").html(`
                                                  <button type="submit" for="avatar_url"
                                                      class="d-flex py-1 justify-content-center text-xs  text-center border rounded btn btn-primary ">
                                                      Save
                                                  </button>
                                            `)
                                        }
                                    })
                                </script>
                            </form>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <div class="d-flex justify-content-between">
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                    class="ni ni-collection"></i></a>
                            <a href="javascript:;"
                                class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                            <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                    class="ni ni-email-83"></i></a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">22</span>
                                        <span class="text-sm opacity-8">Course</span>
                                    </div>
                                    <div class="d-grid text-center mx-4">
                                        <span class="text-lg font-weight-bolder">10</span>
                                        <span class="text-sm opacity-8">Learning</span>
                                    </div>
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">89</span>
                                        <span class="text-sm opacity-8">Star</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <h5>
                                {{ $user->full_name }}<span class="font-weight-light"></span>
                            </h5>
                            <div class="h6 font-weight-300">
                                {{ $user->username }}
                            </div>
                            {{-- <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div> --}}
                            <div>
                                {{ $user->bio }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        // const 
        // #status_user
        $("#status_user").change(function() {
            $("#form_status").submit()
        })
        $("#comment_user").change(function() {
            $("#form_comment").submit()
        })
    </script>
@endsection
