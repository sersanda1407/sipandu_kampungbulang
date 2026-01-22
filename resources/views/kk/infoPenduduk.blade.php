<style>
    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }

    .info-label {
        font-weight: bold;
        color: #444;
        margin-bottom: 2px;
        display: block;
    }

    .info-value {
        color: #222;
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .card-custom {
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        background-color: #fff;
    }

    .img-ktp {
        width: 100%;
        max-width: 250px;
        height: auto;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 95%;
            margin: 1.75rem auto;
        }
    }

    /* Dark theme adjustments */
    body.theme-dark .modal-header {
        background-color: #1e1e2d;
        color: #c2c2d9;
        border-bottom: 1px solid #2d2d3d;
    }

    body.theme-dark .modal-title {
        color: #c2c2d9;
    }

    body.theme-dark .close {
        color: #c2c2d9;
    }

    body.theme-dark .close:hover {
        color: #fff;
    }

    body.theme-dark .card-custom {
        background-color: #1e1e2d;
        border: 1px solid #2d2d3d;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    }

    body.theme-dark .info-label {
        color: #c2c2d9;
    }

    body.theme-dark .info-value {
        color: #fff;
    }

    body.theme-dark .modal-content {
        background-color: #151521;
        color: #c2c2d9;
        border: 1px solid #2d2d3d;
    }

    body.theme-dark .modal-footer {
        border-top: 1px solid #2d2d3d;
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

    body.theme-dark .img-thumbnail {
        background-color: #252537;
        border-color: #2d2d3d;
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

    .img-ktp {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .img-ktp:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    body.theme-dark .img-ktp:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        border-color: #435ebe;
    }

    body.theme-dark .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    body.theme-dark .modal-body::-webkit-scrollbar-track {
        background: #252537;
        border-radius: 4px;
    }

    body.theme-dark .modal-body::-webkit-scrollbar-thumb {
        background: #435ebe;
        border-radius: 4px;
    }

    body.theme-dark .modal-body::-webkit-scrollbar-thumb:hover {
        background: #3a52a8;
    }
</style>

@foreach ($penduduk as $d)
    <div class="modal fade text-left" id="infoData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-dark">
                    <h5 class="modal-title" id="myModalLabel1">Detail Data Penduduk</h5>
                    <button type="button" class="close rounded-pill text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-custom p-3">
                        <div class="row">
                            @php
                                $imageKtpSrc = 'storage/foto_ktp/default.png';

                                if ($d->image_ktp) {
                                    if (Str::startsWith($d->image_ktp, ['data:image', 'http', 'https'])) {
                                        $imageKtpSrc = $d->image_ktp;
                                    } elseif (file_exists(public_path('storage/foto_ktp/' . $d->image_ktp))) {
                                        $imageKtpSrc = 'storage/foto_ktp/' . $d->image_ktp;
                                    }
                                }
                            @endphp

                            <div class="col-sm-12 col-md-4 text-center mb-3">
                                <a href="#" onclick="downloadImageAsPDF('{{ Str::startsWith($imageKtpSrc, ['data:image', 'http', 'https']) ? $imageKtpSrc : asset($imageKtpSrc) }}')"
                                   class="d-inline-block" title="Klik untuk mengunduh gambar">
                                    @if (Str::startsWith($imageKtpSrc, ['data:image', 'http', 'https']))
                                        <img src="{{ $imageKtpSrc }}" alt="Foto KTP"
                                            class="rounded img-thumbnail img-ktp shadow">
                                    @else
                                        <img src="{{ asset($imageKtpSrc) }}" alt="Foto KTP"
                                            class="rounded img-thumbnail img-ktp shadow">
                                    @endif
                                    <span class="download-indicator">
                                        <i class="fas fa-download me-1"></i>Klik untuk mengunduh
                                    </span>
                                </a>
                            </div>

                            <div class="col-sm-12 col-md-8">
                                <div class="mb-2">
                                    <span class="info-label">Nama Lengkap:</span>
                                    <p class="info-value">{{ $d->nama }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">NIK:</span>
                                    <p class="info-value">{{ $d->nik }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Jenis Kelamin:</span>
                                    <p class="info-value">{{ $d->gender }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">No Telepon / WhatsApp:</span>
                                    <p class="info-value">{{ $d->no_hp }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Pendidikan Terakhir:</span>
                                    <p class="info-value">
                                        @switch($d->pendidikan)
                                            @case('tk')
                                                TK / PAUD
                                                @break
                                            @case('sd')
                                                SD / MI / Paket A / Sederajat
                                                @break
                                            @case('smp')
                                                SMP / MTS / Paket B / Sederajat
                                                @break
                                            @case('sma')
                                                SMA / MA / Paket C / Sederajat
                                                @break
                                            @case('s1')
                                                S1
                                                @break
                                            @case('s2')
                                                S2
                                                @break
                                            @case('s3')
                                                S3
                                                @break
                                            @case('none')
                                                Tidak Sekolah
                                                @break
                                            @default
                                                {{ $d->pendidikan }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Nama Sekolah / Lembaga Pendidikan Terakhir:</span>
                                    <p class="info-value">{{ $d->nama_pendidikan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-custom p-3 mt-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="mb-2">
                                    <span class="info-label">Tempat, Tanggal Lahir:</span>
                                    <p class="info-value">{{ $d->tmp_lahir }}, {{ date('d-m-Y', strtotime($d->tgl_lahir)) }}
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Usia:</span>
                                    <p class="info-value">{{ $d->usia }} Tahun</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Agama:</span>
                                    <p class="info-value">{{ $d->agama }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Status Pernikahan:</span>
                                    <p class="info-value">{{ $d->status_pernikahan }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="mb-2">
                                    <span class="info-label">Pekerjaan:</span>
                                    <p class="info-value">{{ $d->pekerjaan }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Pendapatan:</span>
                                    <p class="info-value">Rp.{{ number_format($d->gaji, 0, '.', '.') }},-</p>
                                </div>

                                <div class="mb-2">
                                    <span class="info-label">Status Keluarga:</span>
                                    <p class="info-value">
                                        @php
                                            $statusMapping = [
                                                'Kepala Rumah Tangga' => 'Kepala Keluarga',
                                                'Isteri' => 'Istri',
                                                'Anak' => 'Anak',
                                                'Lainnya' => 'Saudara'
                                            ];
                                        @endphp
                                        {{ $statusMapping[$d->status_keluarga] ?? $d->status_keluarga }}
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Status Sosial:</span>
                                    <p class="info-value">{{ ucfirst($d->status_sosial) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-custom p-3 mt-3">
                        <span class="info-label">Alamat:</span>
                        <p class="info-value">{{ $d->alamat }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    function downloadImageAsPDF(imageUrl) {
        const { jsPDF } = window.jspdf;
        let pdf = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });
        let img = new Image();
        img.src = imageUrl;
        img.crossOrigin = "Anonymous";

        // Show loading indicator
        const downloadBtn = event.target;
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyiapkan...';

        img.onload = function () {
            let imgWidth = 130;
            let imgHeight = (img.height / img.width) * imgWidth;
            pdf.addImage(img, 'JPEG', 40, 40, imgWidth, imgHeight);
            pdf.save("Foto_KTP.pdf");

            // Reset button
            downloadBtn.innerHTML = originalText;

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Gambar KTP berhasil diunduh sebagai PDF',
                timer: 2000,
                showConfirmButton: false
            });
        };

        img.onerror = function () {
            // Reset button on error
            downloadBtn.innerHTML = originalText;

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal mengunduh gambar KTP',
                timer: 3000
            });
        };
    }
</script>
