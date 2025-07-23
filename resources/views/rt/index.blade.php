@extends('layouts.master')

@section('master')

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data RT Baru</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ url('rt/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body overflow-auto" style="max-height: 70vh;">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Ketua RT</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Nama Lengkap" name="nama"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RT</label>
                                    <input type="text" class="form-control" placeholder="No Wilayah RT" name="rt"
                                        id="rt_input" maxlength="3" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RW</label>
                                    <select class="form-select" name="rw_id" required>
                                        <option value="">-- Pilih No Wilayah RW --</option>
                                        @foreach ($select as $d)
                                            <option value="{{ $d->id }}">{{ $d->rw }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat_rt" placeholder="Alamat tinggal ketua RT"
                                minlength="8" required></textarea>
                        </div>


                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" name="no_hp" id="no_hp_rt"
                                placeholder="Masukkan No Telepon / WhatsApp" maxlength="12" minlength="8" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Email Pribadi</label>
                            <input type="email" class="form-control" name="gmail_rt" placeholder="Email aktif" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_awal" id="periode_awal"
                                        placeholder="Tahun Awal Menjabat" maxlength="4" required>
                                </div>
                                <div class="col-md-2 text-center">s/d</div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_akhir" id="periode_akhir"
                                        placeholder="Tahun Akhir Menjabat" maxlength="4" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Ketua RT</label>
                            <input type="file" class="form-control" name="image_rt" required accept="image/*">
                            <small class="text-muted">Upload foto Ketua RT</small>
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

    {{-- Script Validasi Nomor HP dan RT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hpInput = document.getElementById('no_hp_rt');
            const rtInput = document.getElementById('rt_input');
            if (!hpInput || !rtInput) return;

            // Buat elemen feedback
            const feedHp = document.createElement('div');
            feedHp.className = 'invalid-feedback';
            hpInput.after(feedHp);

            const feedRt = document.createElement('div');
            feedRt.className = 'invalid-feedback';
            rtInput.after(feedRt);

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
        });
    </script>

    {{-- Script untuk angka saja --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("#no_hp_rt, #rt_input, #periode_awal, #periode_akhir").forEach(function (input) {
                input.addEventListener("input", function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });
        });
    </script>

    {{-- END MODAL ADD --}}

    <div class="container-fluid">
        <div class="row">
            <div class="py-3">
                <h3>Data RT</h3>
            </div>
            <section class="section">
                <div class="card shadow mb-5">
                    <div class="card-body">
                        @hasrole('superadmin')
                        <button class="btn btn-primary rounded-pill mb-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        @endhasrole
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Foto RT</th>
                                    <th>Nama Ketua RT</th>
                                    <th>No Telepon</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @php

                                                $imageSrc = 'storage/foto_rt/default.jpg';

                                                if ($d->image_rt) {
                                                    if (Str::startsWith($d->image_rt, ['data:image', 'http', 'https'])) {
                                                        $imageSrc = $d->image_rt;
                                                    } elseif (file_exists(public_path('storage/foto_rt/' . $d->image_rt))) {
                                                        $imageSrc = 'storage/foto_rt/' . $d->image_rt;
                                                    }
                                                }
                                            @endphp

                                            <a href="#" onclick="showImageModal('{{ asset($imageSrc) }}', '{{ $d->nama }}')">
                                                <img src="{{ asset($imageSrc) }}" alt="Foto RT" class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </a>

                                        </td>
                                        <td style="min-width: 200px;">{{ $d->nama }}</td>
                                        <td>{{ $d->no_hp }}</td>
                                        <td>{{ $d->rt }}</td>
                                        <td>{{ $d->Rw->rw }}</td>
                                        <td style="min-width: 150px;">{{ $d->periode_awal }} / {{ $d->periode_akhir }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button"
                                                    id="dropdownMenuRT{{ $d->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuRT{{ $d->id }}">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ url('/rt/' . Crypt::encryptString($d->id) . '/showRT') }}">
                                                            <i class="fas fa-eye text-info me-2"></i> Lihat
                                                        </a>
                                                    </li>
                                                    @hasrole('superadmin')
                                                    <li>
                                                        <button class="dropdown-item text-success" data-bs-toggle="modal"
                                                            data-bs-target="#editData{{ $d->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDeleteRT('{{ $d->id }}', '{{ $d->rt }}', '{{ $d->Rw->rw }}', '{{ $d->nama }}')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>

                                                        <form id="delete-form-rt-{{ $d->id }}"
                                                            action="{{ url('/rt/delete/' . Crypt::encryptString($d->id)) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </li>



                                                    <hr>
                                                    <li>
                                                        <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                            data-bs-target="#modalResetPasswordRT{{ $d->id }}">
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

                <!-- MODAL UNTUK PREVIEW GAMBAR -->
                <div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Preview Foto RT</h5>
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

                <script>
                    let rotationAngle = 0;
                    let currentImageUrl = '';
                    let currentNamaRT = '';

                    function showImageModal(imageUrl, namaRT) {
                        const modalImage = document.getElementById("modalImage");
                        rotationAngle = 0;
                        modalImage.style.transform = "rotate(0deg)";
                        modalImage.src = imageUrl;
                        currentImageUrl = imageUrl;
                        currentNamaRT = namaRT;

                        const sanitizedName = namaRT.replace(/\s+/g, "_");
                        document.getElementById("downloadImageBtn").href = imageUrl;
                        document.getElementById("downloadImageBtn").setAttribute("download", `FOTO_RT_${sanitizedName}.jpg`);

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
                            const sanitizedName = currentNamaRT.replace(/\s+/g, "_");
                            doc.save(`FOTO_RT_${sanitizedName}.pdf`);
                        };
                    }
                </script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    function confirmDeleteRT(id, rt, rw, nama) {
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            html: `Data Ketua RT <strong>RT ${rt} / RW ${rw}</strong> atas nama <strong>${nama}</strong> akan dihapus permanen!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Hapus',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#e74c3c',
                            cancelButtonColor: '#6c757d',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById(`delete-form-rt-${id}`).submit();
                            }
                        });
                    }
                </script>


                {{-- MODAL RESET PASSWORD RT --}}
                @foreach ($data as $d)
                    <div class="modal fade" id="modalResetPasswordRT{{ $d->id }}" tabindex="-1"
                        aria-labelledby="modalResetLabelRT" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <i class="fas fa-exclamation-circle mb-2"
                                        style="color: #f39c12; font-size:120px; justify-content:center; display:flex"></i>
                                    <h5 class="text-center">
                                        Apakah Anda yakin ingin mereset password akun Ketua RT <strong>{{ $d->nama }}</strong>?
                                    </h5>
                                    <p class="text-center mt-2 text-muted">Password akan direset ke: <strong>password</strong>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('rt.resetPassword', $d->id) }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @include('rt/formEdit')
            </section>
        </div>
    </div>
@endsection