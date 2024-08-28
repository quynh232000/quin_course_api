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
        .tag-close:hover{
           color: red
        }
        .tag-body:hover .tag-close {
            display: inline-block;
        }
    </style>
@endpush
@section('main')
    <div class="container-fluid py-4">
        <div class="">
            <form method="POST" enctype="multipart/form-data" class="row "
                >
                @csrf
                <div class="col-md-8 m-auto">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="fw-bold mb-3 text-uppercase">
                                Create new Voucher
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
                              
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Title</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('title') ? old('title') : (isset($voucher) ? $voucher->title : '') }}"
                                            name="title" placeholder="Aa..">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date start</label>
                                        <input class="form-control" type="date"
                                        name="date_start"
                                        value="{{ old('date_start', isset($voucher) ? \Carbon\Carbon::parse($voucher->date_start)->format('Y-m-d') : '') }}"
                                        placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date end</label>
                                        <input class="form-control" type="date"
                                            value="{{ old('date_end') ? old('date_end') : (isset($voucher) ? \Carbon\Carbon::parse($voucher->date_end)->format('Y-m-d') : '') }}"
                                            name="date_end" placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Minimum total price</label>
                                        <input class="form-control" type="number"
                                            value="{{ old('min_price') ? old('min_price') : (isset($voucher) ? $voucher->min_price : '') }}"
                                            name="min_price" placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Discount Amount</label>
                                        <input class="form-control" type="number"
                                            value="{{ old('discount_amount') ? old('discount_amount') : (isset($voucher) ? $voucher->discount_amount : '') }}"
                                            name="discount_amount" placeholder="Aa..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Quantity</label>
                                        <input class="form-control" type="number"
                                            value="{{ old('quantity') ? old('quantity') : (isset($voucher) ? $voucher->quantity : '') }}"
                                            name="quantity" placeholder="Aa..">
                                    </div>
                                </div>



                            </div>
                            <div class="mt-2 d-flex justify-content-center">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>

                        </div>
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
