@extends('layouts.editcourseLayout')
@section('viewTitle')
    {{ $course->title }} | Quin
@endsection


@section('css')
    <style>
        .certificate-container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            border: 10px solid #4CAF50;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .certificate-border {
            border: 5px solid #000;
            padding: 20px;
        }

        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            color: red
        }

        .certificate-text {
            font-size: 18px;
            margin: 10px 0;
        }

        .certificate-recipient {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
        }

        .certificate-course {
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0;
        }

        .certificate-signature {
            margin-top: 50px;
            font-size: 18px;
        }

        .certificate-container {
            color: rgb(0, 0, 0);
            /* Override any color that uses oklch */
        }
    </style>
@endsection
@section('content')
    <div class="p-4 fw-bold fs-5 border-bottom">
        Intended learners
    </div>
    <div class="p-4">
        <div>
            <div class="certificate-container">
                <div class="certificate-border">
                    <div class="certificate-content">
                        <h1 class="certificate-title">Certificate of Achievement</h1>
                        <p class="certificate-text" style="color: red">This is to certify that</p>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Cat_August_2010-4.jpg/1200px-Cat_August_2010-4.jpg" class="w-100 h-100" alt="">
                        <h2 class="certificate-recipient" id="recipientName">[Recipient's Name]</h2>
                        <p class="certificate-text">has successfully completed</p>
                        <h3 class="certificate-course" id="courseName">[Course Name]</h3>
                        <p class="certificate-text">Issued on <span id="issueDate">[Date]</span></p>
                        <p class="certificate-signature">Signature: __________________</p>
                    </div>
                </div>
            </div>

            <button class="btn btn-outline-primary" onclick="exportToPDF()">Download PDF</button>
            <button class="btn btn-outline-primary" onclick="exportToPNG()">Download PNG</button>
        </div>

        <div class="border-top pt-4 mt-3">
            <button class="btn btn-success w-100">Save </button>
        </div>

    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.getElementById('recipientName').textContent = 'John Doe';
        document.getElementById('courseName').textContent = 'Web Development';
        document.getElementById('issueDate').textContent = 'August 18, 2024';

        function exportToPDF() {
            const element = document.querySelector('.certificate-container');
            html2pdf(element, {
                margin: 10,
                filename: 'certificate.pdf',
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
            domtoimage.toPng(element)
                .then(function(dataUrl) {
                    const link = document.createElement('a');
                    link.href = dataUrl;
                    link.download = 'certificate.png';
                    link.click();
                })
                .catch(function(error) {
                    console.error('Error capturing element:', error);
                });
        }
    </script>
@endsection
