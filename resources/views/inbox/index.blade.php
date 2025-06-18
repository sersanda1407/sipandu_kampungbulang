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
        }
    </style>

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center text-md-start">Kotak Verifikasi Pendaftaran Warga Baru</h4>

                {{-- Search dan Entries --}}
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <form method="GET" action="{{ route('inbox.index') }}"
                        class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                        <label for="entries" class="me-2 mb-0">Tampilkan</label>
                        <select name="entries" id="entries" class="form-select form-select-sm w-auto"
                            onchange="this.form.submit()">
                            @foreach ([5, 10, 25, 50] as $entry)
                                <option value="{{ $entry }}" {{ request('entries') == $entry ? 'selected' : '' }}>
                                    {{ $entry }}
                                </option>
                            @endforeach
                        </select>
                        <span class="ms-2">entri</span>
                    </form>

                    <form method="GET" action="{{ route('inbox.index') }}" class="d-flex">
                        <input type="hidden" name="entries" value="{{ request('entries', 5) }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..."
                            class="form-control form-control-sm me-2" />
                        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                    </form>
                </div>

                {{-- Isi Inbox --}}
                @if ($data->isEmpty())
                    <div class="alert alert-info">Tidak ada pendaftaran warga yang menunggu verifikasi.</div>
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

                                {{-- Tombol ACC --}}
                                {{-- Tombol ACC dan Tolak --}}
                                <div
                                    class="col-md-3 d-flex flex-column gap-2 justify-content-md-end justify-content-center align-items-center mt-3 mt-md-0">
                                    <form action="{{ route('inbox.verifikasi', $kk->id) }}" method="POST" class="w-100 text-center">
                                        @csrf
                                        <button type="submit" name="acc" value="1" class="btn btn-success btn-sm btn-acc w-100">
                                            ✔ Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('inbox.verifikasi', $kk->id) }}" method="POST" class="w-100 text-center">
                                        @csrf
                                        <button type="submit" name="acc" value="0" class="btn btn-danger btn-sm btn-acc w-100">
                                            ✘ Tolak
                                        </button>
                                    </form>
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
                                Menampilkan {{ $from }} sampai {{ $to }} dari total {{ $data->total() }} entri
                            </small>
                        </div>
                        <div>
                            {{ $data->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
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
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                timer: 10000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Ditolak!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                timer: 10000
            });
        </script>
    @endif

@endsection