@extends('layouts.appLayout')
@section('viewTitle')
    Settings
@endsection
@section('main')
    <div class="container-fluid py-4">
        @if (in_array('Super Admin', auth('admin')->user()->roles()->toArray()))
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Add new setting</h6>
                        </div>
                        <div class="card-body  pt-0 pb-4 px-4">
                            <form method="POST" action="{{ isset($setting) ? '/settings/' . $setting->id : '' }}"
                                class="table-responsive p-0 d-flex row">
                                @csrf
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-label">Type</div>
                                            <input type="text" name="type"
                                                value="{{ isset($setting) ? $setting->type : '' }}" class="form-control"
                                                placeholder="Aa..">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-label">Key</div>
                                            <input type="text" name="key" class="form-control"
                                                value="{{ isset($setting) ? $setting->key : '' }}" placeholder="Aa..">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-label">Value</div>
                                    <input type="text" name="value"
                                        value="{{ isset($setting) ? $setting->value : '' }}" class="form-control"
                                        placeholder="Aa..">
                                </div>
                                <div class="col-md-12 d-flex align-items-end mt-4 gap-3">
                                    @if (isset($setting))
                                        <button type="submit" class="btn btn-primary mb-0">Save</button>

                                        <a href="/settings" type="submit" class="btn btn-warning mb-0">Add new</a>
                                    @else
                                        <button type="submit" class="btn btn-primary mb-0">Submit</button>
                                    @endif
                                </div>
                            </form>
                            @if (session('error'))
                                <div class="alert alert-danger mt-3 text-white">{{ session('error') }}</div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success mt-3 text-white">{{ session('success') }}</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>All Settings</h6>
                    </div>

                    @if (session('error_delete'))
                        <div class="alert alert-warning mt-3 text-white">{{ session('error_delete') }}</div>
                    @endif
                    @if (session('success_delete'))
                        <div class="alert alert-success mt-3 text-white">{{ session('success_delete') }}</div>
                    @endif
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">type
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Key</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Value</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 gap-2">
                                                    <div>
                                                        <span
                                                            class="badge text-bg-secondary bg-success text-white align-center"
                                                            style="min-width: 34px">{{ $item->id }}</span>
                                                    </div>
                                                    <div class="my-auto">
                                                        <h6 class="mb-0 text-sm"> {{ $item->type }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0"> {{ $item->key }}</p>
                                            </td>
                                            <td>
                                                <div class="text-xs font-weight-bold d-block"> {{ $item->value }}</div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2 ">

                                                    @if (in_array('Super Admin', auth('admin')->user()->roles()->toArray()))
                                                        <a href="/settings/{{ $item->id }}"
                                                            class="btn btn-sm btn-outline-primary mb-0">Edit</a>
                                                        <a href="{{ url('roles/delete/' . $item->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this')"
                                                            class="btn btn-sm btn-danger mb-0">Delete</a>
                                                    @else
                                                        --
                                                    @endif



                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
