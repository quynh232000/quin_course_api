@extends('layouts.appLayout')
@section('viewTitle')
    @if (isset($level))
        {{ $level->name }}
    @else
        Order detail
    @endif
@endsection

@section('main')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
                            <p class="mb-0 ms-5">Order : <strong>{{ $data->order_code }}</strong></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header pb-0 border-bottom mb-2">
                                        <h6>Order information</h6>
                                    </div>
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="p-2 px-4 d-flex flex-column gap-2">
                                            <div class="d-flex">
                                                <div style="width: 100px">Order code:</div>
                                                <strong>{{ $data->order_code }}</strong>
                                            </div>
                                            <div class="d-flex">
                                                <div style="width: 100px">Order date:</div>
                                                <strong>{{ $data->created_at }}</strong>
                                            </div>
                                            <div class="d-flex">
                                                <div style="width: 100px">Total Items:</div>
                                                <strong>{{ count($data->orderDetails) }}</strong>
                                            </div>
                                            <div class="d-flex">
                                                <div style="width: 100px">Subtotal:</div>
                                                <strong
                                                    class="">{{ number_format($data->subtotal, 0, '.', ',') }}%</strong>
                                            </div>
                                            @if ($data->voucher_id)
                                                <div class="d-flex">
                                                    <div style="width: 100px">Voucher:</div>
                                                    <span class="bg-danger rounded-2 text-white fw-sm text-sm px-2">
                                                        -{{ $data->voucher->discount_amount }} %</span>
                                                </div>
                                            @endif
                                            <div class="border-top d-flex border-bottom py-2 mt-2 align-items-center">
                                                <div class="fw-bold" style="width: 100px">Total:</div>
                                                <strong class="text-primary fs-4">{{ number_format($data->total, 0, '.', ',') }}
                                                    đ</strong>
                                            </div>
                                            <div>
                                                @php
                                                    $status = 'success';
                                                    switch ($data->tatus) {
                                                        case 'new':
                                                            $status = 'info';
                                                            break;
                                                        case 'pending':
                                                            $status = 'warning';

                                                            break;
                                                        case 'completed':
                                                            $status = 'success';

                                                            break;
                                                        case 'failed':
                                                            $status = 'danger';

                                                            break;
                                                    }
                                                @endphp
                                                <div
                                                    class="alert alert-{{ $status }} text-white text-center text-uppercase">
                                                    {{ $data->status }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header pb-0">
                                        <h6>Order detail</h6>
                                    </div>
                                    <div class="card-body px-0 pt-0 pb-2">
                                        <div class="table-responsive p-0">
                                            <table class="table align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Name</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                            Price</th>
                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Status</th>
                                                        <th
                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Teacher</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data->orderDetails as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div>
                                                                        <img width="36px" height="36px"
                                                                            src="{{ $item->course->image_url }}"
                                                                            class="avatar object-fit-cover avatar-sm me-3"
                                                                            alt="user1">
                                                                    </div>
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">{{ $item->course->title }}
                                                                        </h6>
                                                                        {{-- <p class="text-xs text-secondary mb-0">john@creative-tim.com</p> --}}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($item->price, 0, '.', ',') }} đ</p>
                                                                {{-- <p class="text-xs text-secondary mb-0">Organization</p> --}}
                                                            </td>
                                                            <td class="align-middle text-center text-sm">
                                                                @if ($data->status == 'pending')
                                                                    <span
                                                                        class="badge badge-sm bg-gradient-warning">{{ $data->status }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge badge-sm bg-gradient-success">{{ $data->status }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                <span
                                                                    class="text-secondary text-xs font-weight-bold">{{ $item->course->user->first_name . ' ' . $item->course->user->last_name }}</span>
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
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile">
                    <img src="{{ $data->user->thumbnail_url ? $item->user->thumbnail_url : asset('assets/img/bg-profile.jpg') }}"
                        alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    <img src="{{ $data->user->avatar_url }}" width="128px" height="128px"
                                        class="rounded-circle   border-2 border-white object-fit-cover">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <div class="d-flex justify-content-between">
                            <a href="/users/{{$data->user->uuid}}" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Info</a>
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                    class="ni ni-collection"></i></a>
                            <a href="javascript:;"
                                class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                            <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                    class="ni ni-email-83"></i></a>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <div class="text-center mt-4">
                            <h5>
                                {{ $data->user->first_name . ' ' . $data->user->last_name }}
                            </h5>
                            <div class="h6 font-weight-300">
                                {{ $data->email }}
                            </div>
                            {{-- <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
