@extends('layouts.master')

@section('master')
    <style>
        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #222;
        }

        .image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 576px) {
            .info-label, .info-value {
                display: block;
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function downloadImageAsPDF(imageUrl) {
            const { jsPDF } = window.jspdf;
            let pdf = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

            let img = new Image();
            img.src = imageUrl;
            img.crossOrigin = "Anonymous";

            img.onload = function () {
                let pageWidth = 210;
                let pageHeight = 297;
                let imgWidth = pageWidth - 40;
                let imgHeight = (img.height / img.width) * imgWidth;

                if (imgHeight > pageHeight - 40) {
                    imgHeight = pageHeight - 40;
                    imgWidth = (img.width / img.height) * imgHeight;
                }

                pdf.addImage(img, 'JPEG', 20, 20, imgWidth, imgHeight);
                pdf.save("Foto_RW.pdf");
            };
        }
    </script>

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center text-md-start">Detail Ketua RW</h4>
                <div class="row align-items-start g-4">
                    <div class="col-md-4 text-center">
                        @if ($data->image_rw)
                            <a href="#" onclick="downloadImageAsPDF('{{ asset('storage/foto_rw/' . $data->image_rw) }}')">
                                <img src="{{ asset('storage/foto_rw/' . $data->image_rw) }}"
                                    alt="Foto RW" class="image-preview img-fluid">
                            </a>
                        @else
                            <img src="{{ asset('storage/foto_rw/default.png') }}"
                                alt="Foto RW" class="image-preview img-fluid">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Nama Ketua RW:</div>
                            <div class="col-sm-7 info-value">{{ $data->nama }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">No HP:</div>
                            <div class="col-sm-7 info-value">{{ $data->no_hp }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Email Akun:</div>
                            <div class="col-sm-7 info-value">{{ $data->user ? $data->user->email : '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Email Aktif:</div>
                            <div class="col-sm-7 info-value">{{ $data->gmail_rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">RW:</div>
                            <div class="col-sm-7 info-value">{{ $data->rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Periode Menjabat:</div>
                            <div class="col-sm-7 info-value">{{ $data->periode_awal }} - {{ $data->periode_akhir }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-center text-md-end mt-4">
                    <a href="{{ route('rw.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
