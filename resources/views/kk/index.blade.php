@extends('layouts.master')

@section('master')

    @php
        use Illuminate\Support\Facades\Crypt;
    @endphp

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kartu Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('kk/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Kepala Keluarga</label>
                            <input type="text" class="form-control" name="kepala_keluarga" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Nama Kepala Keluarga" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputNoKK" class="form-label">No.KK</label>
                            <input id="inputNoKK" type="text" class="form-control" name="no_kk" maxlength="16"
                                minlength="16" placeholder="Nomor Kartu Keluarga" required>
                            <span class="text-danger">
                                <p>*No Kartu Keluarga ini nantinya akan menjadi username / email untuk login ke akun SIPANDU
                                </p>
                            </span>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password Default</label>
                            <input type="text" class="form-control" placeholder="password" readonly>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">RW</label>
                                    <select class="form-select" name="rw_id" id="rw_id">
                                        <option value="">-- Pilih No Wilayah RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ $rw->id }}">{{ $rw->rw }} | {{ $rw->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">RT</label>
                                    <select class="form-select" name="rt_id" id="rt_id">
                                        <option value="">-- Pilih No Wilayah RT --</option>
                                        {{-- Akan diisi oleh JS --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Filter hanya nomor di input nomor KK
                            document.getElementById('inputNoKK').addEventListener('input', function (e) {
                                this.value = this.value.replace(/[^0-9]/g, '');
                            });

                            // Menghubungkan RW dan RT
                            const rtData = @json($selectRt);

                            document.getElementById('rw_id').addEventListener('change', function () {
                                const rwId = this.value;
                                const rtSelect = document.getElementById('rt_id');

                                // Kosongkan dahulu isi pilihan RT
                                rtSelect.innerHTML = '<option value="">-- Pilih No Wilayah RT --</option>';

                                // Filter sesuai rwId
                                const filteredRt = rtData.filter(rt => rt.rw_id == rwId);

                                // Tambahan option
                                filteredRt.forEach(rt => {
                                    const option = document.createElement('option');
                                    option.value = rt.id;
                                    option.textContent = `${rt.rt} | ${rt.nama}`;
                                    rtSelect.appendChild(option);
                                });
                            });
                        </script>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Alamat</label>
                            <textarea class="form-control" placeholder="alamat" name="alamat" id="exampleInputPassword1"
                                required></textarea>
                        </div>

                        <div class="mb-3">
    <label for="upload_kk" class="form-label">Foto KK</label>
    <input type="file" class="form-control" name="image" id="upload_kk" accept="image/*" required>
    <small class="text-muted">Format yang diperbolehkan: JPG, JPEG, PNG. Maksimal ukuran file: 3 MB</small><br>
</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('upload_kk').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png)'
                });
                this.value = '';
            }
        }

         const maxSize = 3 * 1024 * 1024; // 3 MB dalam bytes
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal adalah 3 MB. File Anda: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                });
                this.value = '';
                return;
            }
    });
</script>


    <style>
        #modalImage {
            max-width: 100%;
            max-height: 80vh;
            width: auto;
            height: auto;
        }

        #modalImage {
            object-fit: contain;
        }

        #imageContainer {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            max-width: 100%;
            max-height: 80vh;
        }
    </style>

<div class="container-fluid">
    <div class="row">
        <div class="py-3">
            <h3>Data Kartu Keluarga</h3>
        </div>

        <section class="section">
            <div class="card shadow mb-5">
                <div class="card-body">

                    @hasrole('superadmin|rw|rt')
                        <button class="btn btn-primary rounded-pill mb-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                    @endhasrole

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    @hasrole('superadmin|rw|rt')
                                        <th>No.</th>
                                    @endhasrole
                                    <th>Foto KK</th>
                                    <th>Kepala Keluarga</th>
                                    <th>No. KK</th>
                                    <th class="d-none d-md-table-cell">RT/RW</th>
                                    <th class="d-none d-md-table-cell">Status Ekonomi</th>
                                    <th class="d-none d-md-table-cell">Jumlah Individu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        @hasrole('superadmin|rw|rt')
                                            <td>{{ $loop->iteration }}</td>
                                        @endhasrole

                                        {{-- Foto KK --}}
                                        <td>
                                            @php
                                                $imageSrc = 'storage/foto_kk/default.jpg';
                                                if ($d->image) {
                                                    if (Str::startsWith($d->image, ['data:image', 'http', 'https'])) {
                                                        $imageSrc = $d->image;
                                                    } elseif (file_exists(public_path('storage/foto_kk/' . $d->image))) {
                                                        $imageSrc = 'storage/foto_kk/' . $d->image;
                                                    }
                                                }
                                            @endphp

                                            <a href="#"
                                                onclick="showImageModal('{{ asset($imageSrc) }}', '{{ $d->kepala_keluarga }}')">
                                                <img src="{{ asset($imageSrc) }}" alt="Foto KK" class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </a>
                                        </td>

                                        <td>{{ $d->kepala_keluarga }}</td>
                                        <td>{{ $d->no_kk }}</td>
                                        <td class="d-none d-md-table-cell">{{ $d->Rt->rt }} / {{ $d->Rw->rw }}</td>

                                        {{-- Status Ekonomi --}}
                                        <td class="d-none d-md-table-cell">
                                            @php
                                                $pendudukKK = \App\DataPenduduk::where('kk_id', $d->id)->get();
                                                $totalGaji = $pendudukKK->sum('gaji');
                                                $jumlahOrang = $pendudukKK->count();
                                                $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

                                                if ($rataRata < 500000) {
                                                    $statusEkonomi = 'Sangat Tidak Mampu';
                                                } elseif ($rataRata <= 1500000) {
                                                    $statusEkonomi = 'Tidak Mampu';
                                                } elseif ($rataRata <= 3000000) {
                                                    $statusEkonomi = 'Menengah ke Bawah';
                                                } elseif ($rataRata <= 5000000) {
                                                    $statusEkonomi = 'Menengah';
                                                } elseif ($rataRata <= 10000000) {
                                                    $statusEkonomi = 'Menengah ke Atas';
                                                } else {
                                                    $statusEkonomi = 'Mampu';
                                                }
                                            @endphp
                                            {{ $statusEkonomi }}
                                        </td>

                                        {{-- Jumlah Individu --}}
                                        <td class="d-none d-md-table-cell">
                                            {{ \App\DataPenduduk::where('kk_id', $d->id)->count() }}
                                        </td>

                                        {{-- Aksi --}}
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle btn-sm" type="button"
                                                    id="dropdownMenuButton{{ $d->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Aksi
                                                </button>

                                                <ul class="dropdown-menu shadow-lg border-0 rounded-3"
                                                    aria-labelledby="dropdownMenuButton{{ $d->id }}">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ url('/kk/' . Crypt::encryptString($d->id) . '/showPenduduk') }}">
                                                            <i class="fas fa-eye text-info me-2"></i> Lihat
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#editData{{ $d->id }}">
                                                            <i class="fas fa-edit text-primary me-2"></i> Edit
                                                        </a>
                                                    </li>

                                                    @hasrole('superadmin|rw|rt')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form id="delete-form-kk-{{ $d->id }}"
                                                                action="{{ route('kk.delete', $d->id) }}" method="POST"
                                                                style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>

                                                            <a class="dropdown-item d-flex align-items-center text-danger"
                                                                href="#" onclick="confirmDeleteKK('{{ $d->id }}', '{{ $d->no_kk }}', '{{ $d->kepala_keluarga }}')">
                                                                <i class="fas fa-trash-alt me-2"></i> Hapus
                                                            </a>
                                                        </li>
                                                    @endhasrole
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>


    <!-- MODAL UNTUK PREVIEW GAMBAR -->
    <div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Foto KK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="imageContainer" class="d-inline-block"
                        style="max-width: 100%; max-height: 80vh; overflow: hidden;">
                        <img id="modalImage" src="" class="img-fluid" style="max-width: 100%; height: auto;">
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

    <!-- JAVASCRIPT UNTUK MODAL GAMBAR -->
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDeleteKK(id, no_kk, kepala_keluarga) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            html: `Data KK <strong>${no_kk}</strong> atas nama <strong>${kepala_keluarga}</strong> akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-kk-${id}`).submit();
            }
        });
    }
</script>


    @include('kk/formEdit')


@endsection
