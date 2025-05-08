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

            .info-label,
            .info-value {
                display: block;
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
            }
        }
    </style>

    <script>
        function downloadImageAsPNG(imageUrl, namaRw) {
            const fileName = `Foto_RW_${namaRw.replace(/\s+/g, '_')}.png`;
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.src = imageUrl;

            img.onload = function () {
                const canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);

                const link = document.createElement("a");
                link.download = fileName;
                link.href = canvas.toDataURL("image/png");
                link.click();
            };
        }
    </script>



    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center text-md-start">Data Singkat Ketua RT</h4>
                <div class="row align-items-start g-4">
                    <div class="col-md-4 text-center">
                        @if ($data->image_rt)
                            <a href="#"
                                onclick="downloadImageAsPNG('{{ asset('storage/foto_rt/' . $data->image_rt) }}', '{{ $data->nama }}')">
                                <img src="{{ asset('storage/foto_rt/' . $data->image_rt) }}" alt="Foto RT"
                                    class="image-preview img-fluid">
                            </a>
                        @else
                            <img src="{{ asset('storage/foto_rt/default.png') }}" alt="Foto RT" class="image-preview img-fluid">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Nama Ketua RT:</div>
                            <div class="col-sm-7 info-value">{{ $data->nama }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">No Wilayah RT:</div>
                            <div class="col-sm-7 info-value">{{ $data->rt }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">No Wilayah RW:</div>
                            <div class="col-sm-7 info-value">{{ $data->Rw->rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Alamat:</div>
                            <div class="col-sm-7 info-value">{{ $data->alamat_rt }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">No HP / WhatsApp:</div>
                            <div class="col-sm-7 info-value">{{ $data->no_hp }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Email Akun SIPANDU:</div>
                            <div class="col-sm-7 info-value">{{ $data->user ? $data->user->email : '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Email Pribadi:</div>
                            <div class="col-sm-7 info-value">{{ $data->gmail_rt }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Periode Menjabat:</div>
                            <div class="col-sm-7 info-value">{{ $data->periode_awal }} - {{ $data->periode_akhir }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-center text-md-end mt-4">
                    <a href="{{ route('rt.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection