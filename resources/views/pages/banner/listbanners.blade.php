@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Manage Banners
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div>Filter Banner:</div>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex gap-3">
                                <div class="form-group">
                                    <label for="" class="form-label">Placement:</label>
                                    <input style="max-width: 220px" class="form-control" type="text" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Type:</label>
                                    <input style="max-width: 220px" class="form-control" type="text" placeholder="">
                                </div>
                                <div class="form-group d-flex align-items-end">
                                    <button type="button" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="/banners/create" class="btn btn-info"><i class="fa-solid fa-plus me-2"></i>Add
                                    banner</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">


                    @if (session('error'))
                        <div class="alert alert-warning mt-3 text-white">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success mt-3 text-white">{{ session('success') }}</div>
                    @endif
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-hover align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr style="background-color: rgba(22,22,24,.12)" class="text-center">
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-start">
                                            <span>Id</span>
                                            <span class="ms-3">Title</span>
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Banner url</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Placement(page)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Type (position)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Is Show</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($banners as $item)
                                        <tr class="bg-hover-danger" style=":hover{background-color:red}">
                                            <td>
                                                <a href="/categories/{{ $item->id }}" class="d-flex px-2 gap-2">
                                                    <div>
                                                        <span
                                                            class="badge text-bg-secondary bg-info text-white align-center"
                                                            style="min-width: 34px">{{ $item->id }}</span>
                                                    </div>
                                                    <div class="my-auto d-flex justify-content-between flex-1"
                                                        style="flex: 1">
                                                        <h6 class="mb-0 text-sm"> {{ $item->title }}</h6>

                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <p style="width:64px;height:52px" class="text-sm font-weight-bold mb-0">
                                                        <img width="100%" height="100%"
                                                            src="{{ $item->banner_url && $item->banner_url != '' ? $item->banner_url : 'https://img.freepik.com/free-vector/gradient-no-photo-sign-design_23-2149288316.jpg' }}"
                                                            alt="" class="rounded-2 border object-fit-cover">
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <p class="text-sm font-weight-bold mb-0"> {{ $item->placement }}</p>
                                                </div>
                                            </td>

                                            <td class="">
                                                <div class="d-flex justify-content-center"><span
                                                        class="text-xs font-weight-bold "> {{ $item->type }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-xxs badge badge-sm bg-gradient-success">
                                                        {{ $item->is_show ? 'Show' : 'Hidden' }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2 ">
                                                    <a href="{{ route('admin.banner.update', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-outline-primary mb-0">Edit</a>
                                                    <a href="{{ route('admin.banner.delete', ['id' => $item->id]) }}"
                                                        onclick="return confirm('Are you sure you want to delete this')"
                                                        class="btn btn-sm btn-warning mb-0">Delete</a>

                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-danger fw-bold">No data found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
