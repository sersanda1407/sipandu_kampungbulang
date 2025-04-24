@extends('layouts.master')

@section('master')

    @php
        use Illuminate\Support\Facades\Crypt;
    @endphp

    {{-- MODAL ADD --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Keluarga</h5>
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
                            <label for="exampleInputPassword1" class="form-label">No.KK</label>
                            <input type="text" class="form-control" placeholder="Nomor Kartu Keluarga" name="no_kk"
                                id="exampleInputPassword1" maxlength="16" minlength="16" required>
                            <span class="text-danger">
                                <p>*No Kartu Keluarga ini nantinya akan menjadi username / email untuk login ke akun SIPEKA
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
                            // Data RT dalam bentuk array JS
                            const rtData = @json($selectRt);

                            document.getElementById('rw_id').addEventListener('change', function () {
                                const rwId = this.value;
                                const rtSelect = document.getElementById('rt_id');

                                // Kosongkan dulu isi dropdown RT
                                rtSelect.innerHTML = '<option value="">-- Pilih No Wilayah RT --</option>';

                                // Filter RT berdasarkan rw_id yang sesuai
                                const filteredRt = rtData.filter(rt => rt.rw_id == rwId);

                                // Tambahkan option ke dropdown RT
                                filteredRt.forEach(rt => {
                                    const option = document.createElement('option');
                                    option.value = rt.id;
                                    option.textContent = `${rt.rt} | ${rt.nama}`;
                                    rtSelect.appendChild(option);
                                });
                            });
                        </script>


                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Status Ekonomi</label>
                            <select class="form-select" name="status_ekonomi" id="rw_id">
                                <option value="">-- Pilih Status Ekonomi --</option>
                                <option value="Mampu">Mampu</option>
                                <option value="Tidak Mampu">Tidak Mampu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Foto KK</label>
                            <input type="file" class="form-control" name="image" id="exampleInputPassword1" required>
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

    {{-- MODAL DELETE --}}
    @foreach ($data as $r)
        <div class="modal fade" id="modalDelete{{ $r->id }}" tabindex="-1" aria-labelledby="modalHapusBarang"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-circle mb-2"
                            style="color: #e74a3b; font-size:120px; justify-content:center; display:flex"></i>
                        <h5 class="text-center">Apakah anda yakin ingin menghapus Data Keluarga {{ $r->kepala_keluarga }} (No.KK
                            {{ $r->no_kk }} ) ?
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <form action={{ url('kk/delete/' . $r->id) }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Yakin, Hapus Saja</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- END MODAL DELETE --}}

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
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            @hasrole('superadmin|rw|rt')
                                            <td>{{ $loop->iteration }}</td>
                                            @endhasrole
                                            <td>
                                                <a href="#"
                                                    onclick="showImageModal('{{ asset('storage/foto_kk/' . ($d->image ?: 'default.jpg')) }}')">
                                                    <img src="{{ asset('storage/foto_kk/' . ($d->image ?: 'default.jpg')) }}"
                                                        alt="Foto KK" class="img-thumbnail"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </a>
                                            </td>
                                            <td>{{ $d->kepala_keluarga }}</td>
                                            <td>{{ $d->no_kk }}</td>
                                            <td class="d-none d-md-table-cell">{{ $d->Rt->rt }} / {{ $d->Rw->rw }}</td>
                                            <td class="d-none d-md-table-cell">{{ $d->status_ekonomi }}</td>
                                            <td class="d-none d-md-table-cell">
                                                {{ \App\DataPenduduk::where('kk_id', $d->id)->count() }}
                                            </td>
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
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center text-danger"
                                                                href="#" data-bs-toggle="modal"
                                                                data-bs-target="#modalDelete{{ $d->id }}">
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
                        <i class="fas fa-download"></i> Download Gambar
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

        function showImageModal(imageUrl) {
            const modalImage = document.getElementById("modalImage");
            rotationAngle = 0;
            modalImage.style.transform = "rotate(0deg)";
            modalImage.src = imageUrl;
            currentImageUrl = imageUrl;

            document.getElementById("downloadImageBtn").href = imageUrl;
            document.getElementById("downloadImageBtn").setAttribute("download", "foto_kk.jpg");

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
                doc.save('foto_kk.pdf');
            };
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    @include('kk/formEdit')


@endsection