@extends('layouts.master')

@section('master')
    <style>
        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 15px;
        }

        .info-value {
            color: #222;
            font-size: 15px;
        }

        .image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .inbox-item:hover {
            background-color: #f1f5f9;
            transition: 0.3s;
        }

        .btn-acc {
            font-weight: 500;
            padding: 8px 14px;
            font-size: 14px;
        }

        .sorting-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .info-label {
                display: block;
                font-weight: 600;
                color: #555;
                font-size: 14px;
                margin-bottom: 2px;
            }

            .info-value {
                display: block;
                color: #222;
                font-size: 15px;
                margin-bottom: 10px;
            }

            .btn-acc {
                width: 100%;
                max-width: 180px;
                margin: 10px auto 0;
            }

            .col-md-3,
            .col-md-6 {
                text-align: center;
            }

            .sorting-container {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="container py-4">
        <div class="py-3">
            <h3>Kotak Verifikasi Pendaftaran Warga Baru</h3>
        </div>
        <div class="card shadow">
            <div class="card-body">
                {{-- Search, Entries, dan Sorting --}}
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3">
                        {{-- Form Pilihan Jumlah Data --}}
                        <form method="GET" action="{{ route('inbox.index') }}"
                            class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                            <label for="entries" class="me-2 mb-0"></label>
                            <select name="entries" id="entries" class="form-select form-select-md w-auto"
                                onchange="this.form.submit()">
                                @foreach ([5, 10, 25, 50] as $entry)
                                    <option value="{{ $entry }}" {{ request('entries') == $entry ? 'selected' : '' }}>
                                        {{ $entry }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="ms-2">entries per page</span>
                        </form>

                        {{-- Form Sorting --}}
                        <form method="GET" action="{{ route('inbox.index') }}" class="sorting-container">
                            <input type="hidden" name="entries" value="{{ request('entries', 5) }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="sort" id="sort" class="form-select form-select-md w-auto"
                                onchange="this.form.submit()">
                                <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </form>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('inbox.index') }}" class="d-flex" id="searchForm">
                        <input type="hidden" name="entries" value="{{ request('entries', 5) }}">
                        <input type="hidden" name="sort" value="{{ request('sort', 'desc') }}">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search..." class="form-control form-control-md" autocomplete="off" />
                    </form>
                </div>

                {{-- Tampilkan Pesan Berdasarkan Kondisi --}}
                @if ($data->isEmpty())
                    @if (request('search'))
                        <div class="alert alert-warning text-center">
                            Data dengan kata kunci "<strong>{{ request('search') }}</strong>" tidak ditemukan.
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            Tidak ada pendaftaran warga yang menunggu verifikasi.
                        </div>
                    @endif
                @else
                    @foreach ($data as $kk)
                        <div class="inbox-item p-3 mb-3 border rounded">
                            <div class="row align-items-start">
                                {{-- Gambar --}}
                                <div class="col-md-3 text-center">
                                    @php
                                        $imgPath = 'storage/foto_kk/default.png';
                                        if ($kk->image && file_exists(public_path('storage/foto_kk/' . $kk->image))) {
                                            $imgPath = 'storage/foto_kk/' . $kk->image;
                                        }
                                    @endphp
                                    <a href="javascript:void(0);"
                                        onclick="showImageModal('{{ asset($imgPath) }}', '{{ $kk->kepala_keluarga }}')">
                                        <img src="{{ asset($imgPath) }}" alt="Foto KK" class="image-preview">
                                    </a>

                                    <!-- MODAL UNTUK PREVIEW GAMBAR -->
                                    <div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Preview Foto KK</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <div id="imageContainer" class="d-inline-block"
                                                        style="max-width: 100%; max-height: 80vh; overflow: hidden;">
                                                        <img id="modalImage" src="" class="img-fluid"
                                                            style="max-width: 100%; height: auto;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button onclick="rotateImage()" class="btn btn-primary">
                                                        <i class="fas fa-sync"></i> Rotasi 90°
                                                    </button>
                                                    <a id="downloadImageBtn" class="btn btn-success" download>
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <button onclick="downloadAsPDF()" class="btn btn-danger">
                                                        <i class="fas fa-file-pdf"></i> Download as PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Informasi --}}
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="mb-2">
                                        <span class="info-label">Kepala Keluarga:</span>
                                        <span class="info-value">{{ $kk->kepala_keluarga }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="info-label">No KK:</span>
                                        <span class="info-value">{{ $kk->no_kk }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="info-label">No Telepon:</span>
                                        <span class="info-value">{{ $kk->no_telp }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="info-label">Alamat:</span>
                                        <span class="info-value">{{ $kk->alamat }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="info-label">Wilayah:</span>
                                        <span class="info-value">
                                            RT {{ $kk->rt->rt ?? '-' }} / RW {{ $kk->rw->rw ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="info-label">Tanggal Daftar:</span>
                                        <span class="info-value">{{ $kk->created_at->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>

{{-- Tombol ACC dan Tolak --}}
<div class="col-md-3 d-flex flex-column gap-2 justify-content-md-end justify-content-center align-items-center mt-3 mt-md-0">
    {{-- Tombol Terima dengan VerificationController --}}
    <form id="form-acc-{{ $kk->id }}" action="{{ route('kk.verify', $kk->id) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
    <button type="button" class="btn btn-success btn-sm btn-acc w-100"
            onclick="confirmAccept('{{ $kk->id }}', '{{ $kk->kepala_keluarga }}','{{ $kk->no_kk }}')">
        ✔ Terima
    </button>

    {{-- Tombol Tolak dengan VerificationController --}}
    <form id="form-reject-{{ $kk->id }}" action="{{ route('kk.reject', $kk->id) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
    <button type="button" class="btn btn-danger btn-sm btn-acc w-100"
            onclick="confirmReject('{{ $kk->id }}', '{{ $kk->kepala_keluarga }}','{{ $kk->no_kk }}')">
        ✘ Tolak
    </button>

    {{-- Tombol Batalkan Verifikasi (jika sudah diverifikasi) --}}
    @if($kk->verifikasi == 'diterima')
    <form id="form-unverify-{{ $kk->id }}" action="{{ route('kk.unverify', $kk->id) }}" method="POST" style="display: none;">
        @csrf
    </form>
    <button type="button" class="btn btn-warning btn-sm btn-acc w-100"
            onclick="confirmUnverify('{{ $kk->id }}', '{{ $kk->kepala_keluarga }}','{{ $kk->no_kk }}')">
        🔄 Batalkan Verifikasi
    </button>
    @endif
</div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Info Menampilkan dan Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-column flex-md-row">
                        <div class="mb-2 mb-md-0">
                            @php
                                $from = ($data->currentPage() - 1) * $data->perPage() + 1;
                                $to = min($from + $data->count() - 1, $data->total());
                            @endphp
                            <small>
                                Shows {{ $from }} to {{ $to }} of {{ $data->total() }} entries
                            </small>
                        </div>
                        <div>
                            {{ $data->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
function confirmAccept(id, nama, nokk) {
    Swal.fire({
        title: 'Terima Pendaftaran?',
        html: `Data atas nama <strong>${nama}</strong> dengan No. KK <strong>${nokk}</strong> akan diterima dan notifikasi WhatsApp akan dikirim ke warga.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Terima & Kirim Notifikasi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-acc-${id}`).submit();
        }
    });
}

function confirmReject(id, nama, nokk) {
    Swal.fire({
        title: 'Tolak Pendaftaran?',
        html: `Data atas nama <strong>${nama}</strong> dengan No. KK <strong>${nokk}</strong> akan <b>DITOLAK</b> dan notifikasi akan dikirim ke warga dan RT/RW.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Tolak & Kirim Notifikasi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-reject-${id}`).submit();
        }
    });
}

function confirmUnverify(id, nama, nokk) {
    Swal.fire({
        title: 'Batalkan Verifikasi?',
        html: `Verifikasi untuk <strong>${nama}</strong> dengan No. KK <strong>${nokk}</strong> akan dibatalkan. Notifikasi akan dikirim ke warga dan RT/RW.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-unverify-${id}`).submit();
        }
    });
}
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const sortSelect = document.getElementById('sort');

        let typingTimer;
        const delay = 500; // 500 ms

        searchInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                // Pertahankan nilai sort saat search
                document.querySelector('input[name="sort"]').value = sortSelect.value;
                searchForm.submit();
            }, delay);
        });

        let rotationAngle = 0;
        let currentImageUrl = '';
        let currentNamaKepalaKeluarga = '';

        function showImageModal(imageUrl, namaKepalaKeluarga) {
            const modalImage = document.getElementById("modalImage");
            rotationAngle = 0;
            modalImage.style.transform = "rotate(0deg)";
            modalImage.src = imageUrl;
            currentImageUrl = imageUrl;
            currentNamaKepalaKeluarga = namaKepalaKeluarga;

            document.getElementById("downloadImageBtn").href = imageUrl;
            document.getElementById("downloadImageBtn").setAttribute("download", `KK_Keluarga_${namaKepalaKeluarga}.png`);

            var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }

        function rotateImage() {
            rotationAngle = (rotationAngle + 90) % 360;
            document.getElementById("modalImage").style.transform = `rotate(${rotationAngle}deg)`;
        }

        function downloadAsPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const img = new Image();
            img.src = currentImageUrl;

            img.onload = function () {
                const imgWidth = 180;
                const imgHeight = (img.height / img.width) * imgWidth;
                doc.addImage(img, 'JPEG', 15, 40, imgWidth, imgHeight);
                doc.save(`KK_Keluarga_${currentNamaKepalaKeluarga}.pdf`);
            };
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Kartu Keluarga Berhasil diverifikasi!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                timer: 10000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Kartu Keluarga Telah ditolak!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                timer: 10000
            });
        </script>
    @endif
@endsection