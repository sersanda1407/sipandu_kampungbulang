@extends('layouts.master')

@section('master')


    <!-- Modal Tambah Data RW -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data RW Baru</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('rw.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body overflow-auto" style="max-height: 70vh;">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Ketua RW</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Nama Lengkap" name="nama"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" name="no_hp" id="no_hp"
                                placeholder="No Telepon / WhatsApp" maxlength="12" minlength="8" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">RW</label>
                            <input type="text" class="form-control" name="rw" id="rw" placeholder="No Wilayah RW"
                                maxlength="3" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_awal"
                                        placeholder="Tahun Awal Menjabat" maxlength="4" required>
                                </div>
                                <div class="col-md-2 text-center">s/d</div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_akhir"
                                        placeholder="Tahun Akhir Menjabat" maxlength="4" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Ketua RW</label>
                            <input type="file" class="form-control" name="image_rw" required>
                            <small class="text-muted">Upload foto Ketua RW</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("#no_hp, #rw, input[name='periode_awal'], input[name='periode_akhir']")
                .forEach(input => input.addEventListener("input", () => {
                    input.value = input.value.replace(/\D/g, ''); // Hanya angka
                }));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hpInput = document.getElementById('no_hp');
            const rwInput = document.getElementById('rw');
            if (!hpInput || !rwInput) return;

            // Buat elemen feedback
            const feedHp = document.createElement('div');
            feedHp.className = 'invalid-feedback';
            hpInput.after(feedHp);

            const feedRw = document.createElement('div');
            feedRw.className = 'invalid-feedback';
            rwInput.after(feedRw);

            // Helper set validity
            const setValidity = (el, feed, ok, msg = '') => {
                el.classList.toggle('is-invalid', !ok);
                el.classList.toggle('is-valid', ok);
                feed.textContent = msg;
            };

            // Cek API
            async function validateField(input, feed, route, paramName, validateFn) {
                const val = input.value.replace(/\D/g, '');
                let ok = await validateFn(val);
                setValidity(input, feed, ok, ok ? '' : 'Tidak valid/duplikat');
            }

            const debounce = (fn, ms = 300) => {
                let t;
                return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms) };
            };

            hpInput.addEventListener('input', debounce(() => {
                validateField(hpInput, feedHp, "{{ route('api.check-nohp') }}", 'no_hp', async val => {
                    return val.length >= 8 && !(await (await fetch(`{{ route('api.check-nohp') }}?no_hp=${val}`)).json()).exists;
                });
            }, 300));

            rwInput.addEventListener('input', debounce(() => {
                validateField(rwInput, feedRw, "{{ route('api.check-rw') }}", 'rw', async val => {
                    const num = +val; if (!val || num < 1 || num > 999) return false;
                    return !(await (await fetch(`{{ route('api.check-rw') }}?rw=${val}`)).json()).exists;
                });
            }, 300));
        });
    </script>
    {{-- END MODAL Tambah data RW --}}

    {{-- MODAL DELETE --}}
    @foreach ($data as $r)
        <div class="modal fade" id="modalDelete{{ $r->id }}" tabindex="-1" aria-labelledby="modalHapusBarang"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-circle mb-2"
                            style="color: #e74a3b; font-size:120px; justify-content:center; display:flex"></i>
                        <h5 class="text-center">Apakah anda yakin ingin menghapus Data Ketua RW {{ $r->rw }} atas nama
                            {{ $r->nama }} ?
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <form action={{ url('/rw/delete/' . $r->id) }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- END MODAL DELETE --}}
    <div class="container-fluid">
        <div class="row">
            <div class="py-3">
                <h3>Data RW</h3>
            </div>
            <section class="section">
                <div class="card shadow mb-5">
                    <div class="card-body">
                        <button class="btn btn-primary rounded-pill mb-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Foto RW</th>
                                    <th>Ketua RW</th>
                                    <th>No Telepon</th>
                                    <th>RW</th>
                                    <th>Periode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="#"
                                                onclick="showImageModal('{{ asset('storage/foto_rw/' . ($d->image_rw ?: 'default.jpg')) }}', '{{ $d->nama }}')">
                                                <img src="{{ asset('storage/foto_rw/' . ($d->image_rw ?: 'default.jpg')) }}"
                                                    alt="Foto RW" class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </a>
                                        </td>
                                        <td style="min-width: 200px;">{{ $d->nama }}</td>
                                        <td>{{ $d->no_hp }}</td>
                                        <td>{{ $d->rw }}</td>
                                        <td style="min-width: 180px;">{{ $d->periode_awal }} - {{ $d->periode_akhir }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button"
                                                    id="dropdownMenuButton{{ $d->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $d->id }}">
                                                    <li>
                                                        <button class="dropdown-item text-success" data-bs-toggle="modal"
                                                            data-bs-target="#editData{{ $d->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalDelete{{ $d->id }}">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </li>
                                                    @hasrole('superadmin')
                                                    <li>
                                                        <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                            data-bs-target="#modalResetPasswordRW{{ $d->id }}">
                                                            <i class="fas fa-key"></i> Reset Password
                                                        </button>
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
        <!-- MODAL UNTUK PREVIEW GAMBAR -->
        <div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Foto RW</h5>
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
            let currentNamaRW = '';

            function showImageModal(imageUrl, namaRW) {
                const modalImage = document.getElementById("modalImage");
                rotationAngle = 0;
                modalImage.style.transform = "rotate(0deg)";
                modalImage.src = imageUrl;
                currentImageUrl = imageUrl;
                currentNamaRW = namaRW;

                const sanitizedName = namaRW.replace(/\s+/g, "_"); // Hilangkan spasi
                document.getElementById("downloadImageBtn").href = imageUrl;
                document.getElementById("downloadImageBtn").setAttribute("download", `FOTO_RW_${sanitizedName}.jpg`);

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
                img.crossOrigin = "Anonymous";
                img.src = currentImageUrl;

                img.onload = function () {
                    const imgWidth = 180;
                    const imgHeight = (img.height / img.width) * imgWidth;
                    doc.addImage(img, 'JPEG', 15, 40, imgWidth, imgHeight);
                    const sanitizedName = currentNamaRW.replace(/\s+/g, "_");
                    doc.save(`FOTO_RW_${sanitizedName}.pdf`);
                };
            }
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        {{-- MODAL RESET PASSWORD RW --}}
        @foreach ($data as $d)
            <div class="modal fade" id="modalResetPasswordRW{{ $d->id }}" tabindex="-1" aria-labelledby="modalResetLabelRW"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <i class="fas fa-exclamation-circle mb-2"
                                style="color: #f39c12; font-size:120px; justify-content:center; display:flex"></i>
                            <h5 class="text-center">
                                Apakah Anda yakin ingin mereset password akun Ketua RW <strong>{{ $d->nama }}</strong>?
                            </h5>
                            <p class="text-center mt-2 text-muted">Password akan direset ke: <strong>password</strong></p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('rw.resetPassword', $d->id) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @include('rw/formEdit')
        </section>
    </div>
@endsection