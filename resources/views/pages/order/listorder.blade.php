@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Manage orders
    @endif
@endsection
@section('main')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div>Filter Blog:</div>
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
                                            <span>No.</span>
                                            <span class="ms-3">Code</span>
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">
                                            Total</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Created At</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Payment</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr class="bg-hover-danger" style=":hover{background-color:red}">
                                            <td>
                                                <a href="/orders/{{ $item->order_code }}" class="d-flex px-2 gap-2">
                                                    <div>
                                                        <span
                                                            class="badge text-bg-secondary bg-info text-white align-center"
                                                            style="min-width: 34px">{{ $item->id }}</span>
                                                    </div>
                                                    <div class=" d-flex justify-content-between ">
                                                        <strong class="mb-0 text-sm" style="line-height: 28px">
                                                            {{ $item->order_code }}</strong>

                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <div class="text-sm font-weight-bold mb-0">
                                                        {{ $item->email }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if ($item->status == 'pending')
                                                        <p
                                                            class="text-white font-weight-bold mb-0 text-xs badge badge-sm bg-gradient-warning text-danger">
                                                            {{ $item->status }}</p>
                                                    @endif
                                                    @if ($item->status == 'failed')
                                                        <p
                                                            class=" font-weight-bold mb-0 text-xs badge badge-sm bg-gradient-danger text-white">
                                                            {{ $item->status }}</p>
                                                    @endif
                                                    @if ($item->status == 'completed')
                                                        <p
                                                            class=" font-weight-bold mb-0 text-xs badge badge-sm bg-gradient-success text-white">
                                                            {{ $item->status }}</p>
                                                    @endif
                                                    @if ($item->status == 'new')
                                                        <p
                                                            class=" font-weight-bold mb-0 text-xs badge badge-sm bg-gradient-primary text-white">
                                                            {{ $item->status }}</p>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="">
                                                <div class="d-flex justify-content-center text-primary fw-bold">
                                                    {{ number_format($item->total, 0, ',', '.') }} đ
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-xs ">
                                                        {{ $item->created_at }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($item->status == 'pending')
                                                    <div class="d-flex align-items-center justify-content-center gap-2 ">
                                                        <a href="{{ route('admin.order.cancel', ['order_id' => $item->id]) }}"
                                                            onclick="return confirm('Are you sure you want to cancel this order?')"
                                                            class="btn btn-sm btn-outline-warning mb-0">Cancel</a>
                                                        <a href="{{ route('admin.order.confirm', ['order_id' => $item->id]) }}"
                                                            class="btn btn-sm btn-primary mb-0">Confirm</a>

                                                    </div>
                                                @else
                                                    --
                                                @endif
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
