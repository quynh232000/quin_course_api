@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Manage Vouchers
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div>Filter Voucher:</div>
                        <div class="d-flex justify-content-between">
                            <form class="d-flex gap-3">
                                @php
                                    $type = [
                                        ['id' => 'happening', 'name' => 'Happening'],
                                        ['id' => 'happened', 'name' => 'Happened'],
                                        ['id' => 'comming', 'name' => 'Comming Soon'],
                                    ];
                                @endphp
                                <div class="form-group">
                                    <label for="" class="form-label">Activitive:</label>
                                    <select name="type" class="form-select" id="" style="min-width: 220px">
                                        <option value="">--All--</option>
                                        @foreach ($type as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ isset(request()->type) ? (request()->type == $item['id'] ? 'selected' : '') : '' }}>
                                                {{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                            <div class="d-flex align-items-center">
                                <a href="/vouchers/create" class="btn btn-info"><i class="fa-solid fa-plus me-2"></i>Add
                                    Voucher</a>
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
                                            Code</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Minimun Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Discount Amount</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Quantity</th>

                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Used</th>
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
                                                        <h6 class="mb-0 text-sm"> {{ $item->title }}</h6>

                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    {{ $item->code }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ number_format($item->min_price, 0, ',', '.') }} vnd</p>
                                                </div>
                                            </td>

                                            <td class="">
                                                <div class="d-flex justify-content-center">
                                                    <p class="text-sm font-weight-bold mb-0">

                                                        {{$item->discount_amount }} 
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if ($item->status() == 'active')
                                                        <span class="text-xxs badge badge-sm bg-gradient-success">
                                                            Active</span>
                                                    @endif
                                                    @if ($item->status() == 'expired')
                                                        <span class="text-xxs badge badge-sm bg-gradient-warning">
                                                            Expired</span>
                                                    @endif
                                                    @if ($item->status() == 'comming')
                                                        <span class="text-xxs badge badge-sm bg-gradient-info">
                                                            ComeSoon</span>
                                                    @endif

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    {{ $item->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    {{ $item->used }}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2 ">
                                                    <a href="{{ route('admin.voucher.update', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-outline-primary mb-0">Edit</a>
                                                    <a href="{{ route('admin.voucher.delete', ['id' => $item->id]) }}"
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
                <div class="d-flex justify-content-start mt-4 pt-4 pagination">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
