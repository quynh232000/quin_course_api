@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($category))
         {{ $category->name }}
    @else
        Manage Categories
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">


        @isset($category)
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" style="min-width: 26px"><a href="/categories" style="min-width: 46px">All
                                    categories</a></li>
                            @foreach ($category->parents as $item)
                                <li class="breadcrumb-item"><a href="/categories/{{ $item->id }}">{{ $item->name }}</a>
                                </li>
                            @endforeach
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Category infonation</h6>
                        </div>
                        <div class="card-body  pt-0 pb-4 px-4">
                            <form method="POST" action="/categories/update/{{ $category->id }}"
                                class="table-responsive p-0 d-flex row" enctype="multipart/form-data">
                                @csrf

                                <div class="col-md-3">
                                    <div class="form-label">Name</div>
                                    <input type="text" name="name" value="{{ $category->name }}" class="form-control"
                                        placeholder="Aa..">
                                </div>

                                <div class="col-md-3">
                                    <div class="form-label">Icon</div>
                                    <input type="file" name="icon" class="form-control" id="inputGroupFile01" accept="image/*">
                                </div>
                                <div class=" col-md-2 d-flex">
                                    <img src="{{ $category->icon_url }}" width="86px" height="74px" class="img-thumbnail"
                                        alt="">
                                </div>
                                <div class="col-md-2 d-flex align-items-end ">
                                    <button type="submit" class="btn btn-primary mb-0">Update</button>
                                </div>
                            </form>
                            @if (session('error_update'))
                                <div class="alert alert-warning mt-3 text-white">{{ session('error_update') }}</div>
                            @endif
                            @if (session('success_update'))
                                <div class="alert alert-success mt-3 text-white">{{ session('success_update') }}</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endisset


        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Add {{ isset($category) ? "category child of '$category->name'" : 'new category' }} </h6>
                    </div>
                    <div class="card-body  pt-0 pb-4 px-4">
                        <form method="POST" class="table-responsive p-0 d-flex row" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-3">
                                <div class="form-label">Name</div>
                                <input type="text" name="name" class="form-control" placeholder="Aa..">
                            </div>
                            <div class="col-md-3">
                                <div class="form-label">Icon</div>
                                <input type="file" name="icon" class="form-control" accept="image/*" id="inputGroupFile01">
                            </div>
                            <div class="col-md-2 d-flex align-items-end ">
                                <button type="submit" class="btn btn-primary mb-0">Submit</button>
                            </div>
                        </form>
                        @if (session('error'))
                            <div class="alert alert-warning mt-3 text-white">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success mt-3 text-white">{{ session('success') }}</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>{{ isset($category) ? 'Categories of "' . $category->name . '"' : 'All Categories' }}</h6>
                    </div>

                    @if (session('error_delete'))
                        <div class="alert alert-warning mt-3 text-white">{{ session('error_delete') }}</div>
                    @endif
                    @if (session('success_delete'))
                        <div class="alert alert-success mt-3 text-white">{{ session('success_delete') }}</div>
                    @endif
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table table-hover align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr style="background-color: rgba(22,22,24,.12)" class="text-center">
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-start">
                                            <span>Id</span>
                                            <span class="ms-3">Name</span>
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Slug</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Icon url</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Parent Id</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Created At</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
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
                                                        <h6 class="mb-0 text-sm"> {{ $item->name }}</h6>
                                                        @if ($item->haschild)
                                                            <i class="fa-solid fa-chevron-down"></i>
                                                        @endif
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <p class="text-sm font-weight-bold mb-0"> {{ $item->slug }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        <img width="64px" height="52px" src="{{ $item->icon_url && $item->icon_url !='' ?$item->icon_url : 'https://img.freepik.com/free-vector/gradient-no-photo-sign-design_23-2149288316.jpg' }}"
                                                            alt="" class="img-thumbnail">
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="d-flex justify-content-center"><span
                                                        class="text-xs font-weight-bold "> {{ $item->parent_id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-xs font-weight-bold"> {{ $item->created_at }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2 ">
                                                    <a class="btn btn-sm btn-outline-primary mb-0">Edit</a>
                                                    <a href="{{ url('categories/delete/' . $item->id) }}"
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
