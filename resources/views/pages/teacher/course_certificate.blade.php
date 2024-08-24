@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection



@push('css')
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Grey+Qo&family=Outfit:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet"> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.min.js"></script> --}}
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Grey+Qo&family=Outfit:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet"> --}}
    <style>
        @font-face {
            font-family: 'Great Vibes';
            src: url("{{ asset('assets/fonts/GreatVibes-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            /* or bold, etc. */
            font-style: normal;
            /* or italic, etc. */
        }

        .certificate-container {
            color: rgb(0, 0, 0) !important;
        }


        #signature {
            /* font-family: "Playfair Display", serif; */
            font-family: "Great Vibes", cursive;
            font-size: 18px !important;
        }

        #name_certificate {}

        #user_name {
            /* font-family: "Playfair Display", serif; */
            font-family: "Great Vibes", cursive;
            font-optical-sizing: auto;
            font-size: 64px !important;
        }
    </style>
@endpush
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Certificate
    </div>
    <div class="p-4">
        @if (session('error'))
            <div>
                <div class="alert alert-danger py-2 text-white">{{ session('error') }}</div>
            </div>
        @endif
        @if (session('success'))
            <div>
                <div class="alert alert-success py-2 text-white">{{ session('success') }}</div>
            </div>
        @endif
        <form method="POST" class="mb-3 border-bottom">
            @csrf
            <div class="form-group">
                <label for="" class="form-label">Certificate name of this course:</label>
                <input id="input-certificate" type="text" class="form-control" name="certificate_name"
                    value="{{ old('certificate_name') ? old('certificate_name') : $course->certificate_name }}"
                    placeholder="{{ $course->title }}">
                <div class="d-flex justify-content-end mt-3"><button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
        <div class="text-center fw-bold fs-4 mb-4 text-success">Preview Certificate</div>
        <div>
            <div class=" border-2 border-primary p-4" style="border: 3px dashed green;background-color:rgb(231, 234, 249)">



                <div class="certificate-container position-relative">

                    <div>
                        <img class="w-100 h-100" src="{{ asset('images/logo/certificate_quincourse.png') }}" alt="">
                    </div>
                    <i id="user_name" class="text-center position-absolute start-0 end-0 text-primary  fs-2 ps-3"
                        style="top: 44%; font-family: 'Great Vibes', cursive; color: #000;">
                        {{ auth('admin')->user()->first_name . ' ' . auth('admin')->user()->last_name }}
                    </i>
                    <div id="certificate_name" class="text-center position-absolute start-0 end-0 text-primary  fs-5"
                        style="top: 66%; color: #000;">
                        {{ $course->certificate_name ?? $course->title }}
                    </div>
                    <div class="text-center position-absolute fs-7" style="top: 79%; color: gray; left: 28%;">
                        HCM, {{ now()->format('d M Y') }}
                    </div>
                    <i id="signature" class="text-center position-absolute fs-5" style="top: 79%; left: 60%; color: #000;">
                        Quin Course
                    </i>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center gap-2">
                <button class="btn btn-outline-primary" onclick="exportToPDF()">Download PDF</button>
                <button class="btn btn-outline-primary" onclick="exportToPNG()">Download PNG</button>
            </div>
        </div>



    </div>
@endsection
@section('js')
    {{-- cahnge input --}}
    <script>
        $("#input-certificate").on('input', function(e) {
            const value = $(this).val();
            $('#certificate_name').text(value);
        })
    </script>



    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.min.js"></script> --}}
    <script>
        function exportToPDF() {
            const element = document.querySelector('.certificate-container');
            const username = 'Certificate_' + `{{ auth('admin')->user()->username }}`;
            html2pdf(element, {
                margin: 10,
                filename: username.toUpperCase() + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            });
        }

        function exportToPNG() {
            const element = document.querySelector('.certificate-container');
            const username = `{{ auth('admin')->user()->username }}`
            domtoimage.toPng(element)
                .then(function(dataUrl) {
                    const link = document.createElement('a');
                    link.href = dataUrl;
                    link.download = username + '_certificate.png';
                    link.click();
                })
                .catch(function(error) {
                    console.error('Error capturing element:', error);
                });
        }

        function exportToPNG() {
            const element = document.querySelector('.certificate-container');
            const username = 'Certificate_' + `{{ auth('admin')->user()->username }}`;

            domtoimage.toPng(element)
                .then(function(dataUrl) {
                    const link = document.createElement('a');
                    link.href = dataUrl;
                    link.download = username.toUpperCase() + '.png';
                    link.click();
                })
                .catch(function(error) {
                    console.error('Error capturing element:', error);
                });
        }
    </script>
@endsection
