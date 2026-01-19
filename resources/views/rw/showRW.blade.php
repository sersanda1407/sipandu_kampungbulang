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
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        body.theme-dark .info-label {
            color: #c2c2d9;
            opacity: 0.9;
        }

        body.theme-dark .info-value {
            color: #fff;
        }

        body.theme-dark .image-preview {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            border: 1px solid #2d2d3d;
        }

        body.theme-dark .image-preview:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-color: #435ebe;
        }

        .card {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        body.theme-dark .card {
            background-color: #1e1e2d;
            border-color: #2d2d3d;
        }

        body.theme-dark .card-title {
            color: #c2c2d9;
        }

        body.theme-dark .btn-secondary {
            background-color: #2d2d3d;
            border-color: #2d2d3d;
            color: #c2c2d9;
        }

        body.theme-dark .btn-secondary:hover {
            background-color: #3a3a4d;
            border-color: #435ebe;
            color: #fff;
        }

        @media (max-width: 576px) {
            .info-label,
            .info-value {
                display: block;
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
            }
            
            .image-preview {
                margin-bottom: 20px;
            }
        }

        .row.mb-3 {
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        body.theme-dark .row.mb-3 {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .download-indicator {
            display: block;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }

        body.theme-dark .download-indicator {
            color: #a1a1c2;
        }

        body.theme-dark .container.py-4 {
            background-color: #151521;
        }

        .badge-status {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 20px;
        }
    </style>

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center text-md-start">Data Singkat Ketua RW</h4>
                <div class="row align-items-start g-4">
                    @php
                        $imageSrc = 'storage/foto_rw/default.png'; // default path

                        if ($data->image_rw) {
                            if (Str::startsWith($data->image_rw, ['data:image', 'http', 'https'])) {
                                $imageSrc = $data->image_rw;
                            } elseif (file_exists(public_path('storage/foto_rw/' . $data->image_rw))) {
                                $imageSrc = 'storage/foto_rw/' . $data->image_rw;
                            }
                        }
                    @endphp

                    <div class="col-md-4 text-center">
                        <a href="#" onclick="downloadImageAsPNG('{{ asset($imageSrc) }}', '{{ $data->nama }}')" 
                           class="d-inline-block" title="Klik untuk mengunduh gambar">
                            <img src="{{ asset($imageSrc) }}" alt="Foto RW" class="image-preview img-fluid">
                            <span class="download-indicator">
                                <i class="fas fa-download me-1"></i>Klik untuk mengunduh
                            </span>
                        </a>
                    </div>

                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Nama Ketua RW:</div>
                            <div class="col-sm-7 info-value">{{ $data->nama }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">No Wilayah RW:</div>
                            <div class="col-sm-7 info-value">{{ $data->rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Alamat:</div>
                            <div class="col-sm-7 info-value">{{ $data->alamat_rw }}</div>
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
                            <div class="col-sm-7 info-value">{{ $data->gmail_rw }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-5 info-label">Periode Menjabat:</div>
                            <div class="col-sm-7 info-value">{{ $data->periode_awal }} - {{ $data->periode_akhir }}</div>
                        </div>

                        @if($data->status)
                            <div class="row mb-3">
                                <div class="col-sm-5 info-label">Status:</div>
                                <div class="col-sm-7">
                                    <span class="badge badge-status {{ $data->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $data->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($data->keterangan)
                            <div class="row mb-3">
                                <div class="col-sm-5 info-label">Keterangan:</div>
                                <div class="col-sm-7 info-value">{{ $data->keterangan }}</div>
                            </div>
                        @endif
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

        <script>
        function downloadImageAsPNG(imageUrl, namaRw) {
            const fileName = `Foto_RW_${namaRw.replace(/\s+/g, '_')}.png`;
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.src = imageUrl;

            // Show loading indicator
            const downloadBtn = event.target;
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyiapkan...';
            downloadBtn.disabled = true;

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

                // Reset button
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Gambar berhasil diunduh',
                    timer: 2000,
                    showConfirmButton: false
                });
            };

            img.onerror = function () {
                // Reset button on error
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengunduh gambar',
                    timer: 3000
                });
            };
        }
    </script>
@endsection