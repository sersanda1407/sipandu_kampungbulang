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
            let imgWidth = 130;
            let imgHeight = (img.height / img.width) * imgWidth;
            pdf.addImage(img, 'JPEG', 40, 40, imgWidth, imgHeight);
            pdf.save("Foto_KTP.pdf");
        };
    }
</script>

@foreach ($penduduk as $d)
<div class="modal fade text-left" id="infoData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h5 class="modal-title" id="myModalLabel1">Detail Data Penduduk</h5>
                <button type="button" class="close rounded-pill text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-custom p-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 text-center mb-3">
                            @if ($d->image_ktp)
                                <a href="#" onclick="downloadImageAsPDF('{{ asset('storage/foto_ktp/' . $d->image_ktp) }}')">
                                    <img src="{{ asset('storage/foto_ktp/' . $d->image_ktp) }}" alt="Foto KTP" class="rounded img-thumbnail img-ktp shadow">
                                </a>
                            @else
                                <img src="{{ asset('storage/foto_ktp/default.png') }}" alt="Foto KTP" class="rounded img-thumbnail img-ktp shadow">
                            @endif
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
                        </div>
                    </div>
                </div>

                <div class="card card-custom p-3 mt-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="mb-2">
                                <span class="info-label">Tempat, Tanggal Lahir:</span>
                                <p class="info-value">{{ $d->tmp_lahir }}, {{ date('d-m-Y', strtotime($d->tgl_lahir)) }}</p>
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
