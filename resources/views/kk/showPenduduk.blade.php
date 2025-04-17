@extends('layouts.master')

@section('master')
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
    {{-- MODAL ADD --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Penduduk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ '/kk/' . $data->id . '/showPenduduk/store' }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap"
                                id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">Nomor NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nik" placeholder="Nomor NIK" id="nik"
                                maxlength="16" minlength="16" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp">No Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" placeholder="No Telepon / WhatsApp" name="no_hp"
                                id="no_hp" maxlength="12" minlength="8" required>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label for="">Usia</label>
                                <input class="form-control" type="text" placeholder="Usia" name="usia" id="usia" required
                                    readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Tempat Lahir <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" placeholder="Tempat Lahir" name="tmp_lahir"
                                    required>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" placeholder="Tanggal Lahir" name="tgl_lahir"
                                    id="tgl_lahir" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" type="text" placeholder="Nama Lengkap" name="gender" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Agama <span class="text-danger">*</span></label>
                            <select class="form-select" type="text" placeholder="Agama" name="agama" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Protestan">Protestan</option>
                                <option value="Konghucu">Konghucu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Hindu">Hindu</option>
                            </select>
                        </div>

                        <label for="">Alamat <span class="text-danger">*</span></label>
                        <div class="mb-3">
                            <textarea class="form-control" name="alamat" id="" cols="30" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="">Status Pernikahan <span class="text-danger">*</span></label>
                                    <select class="form-select" type="text" placeholder="Nama Lengkap"
                                        name="status_pernikahan" required>
                                        <option value="">-- Pilih Status Pernikahan --</option>
                                        <option value="Kawin">Kawin</option>
                                        <option value="Belum Kawin">Belum Kawin</option>
                                        <option value="Cerai">Cerai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="">Status Di Keluaraga <span class="text-danger">*</span></label>
                                    <select class="form-select" type="text" placeholder="Nama Lengkap"
                                        name="status_keluarga" required>
                                        <option value="">-- Pilih Status Dikeluarga --</option>
                                        <option value="Kepala Rumah Tangga">Kepala Keluarga</option>
                                        <option value="Isteri">Isteri</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="">Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pekerjaan" id="exampleInputPassword1" required>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="status_sosial" class="form-label small">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" name="status_sosial" id="status_sosial"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="hidup">Masih Hidup</option>
                                        <option value="mati">Sudah Meninggal</option>
                                        <option value="gajelas">Tanpa Keterangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Foto KTP <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="image_ktp" id="exampleInputPassword1"
                                    required>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>

                    <script>
                        document.getElementById("tgl_lahir").addEventListener("change", function () {
                            let tglLahir = new Date(this.value);
                            let today = new Date();
                            let usia = today.getFullYear() - tglLahir.getFullYear();
                            let monthDiff = today.getMonth() - tglLahir.getMonth();

                            // Jika bulan lahir belum lewat dalam tahun ini, kurangi usia
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < tglLahir.getDate())) {
                                usia--;
                            }

                            document.getElementById("usia").value = usia; // Isi usia otomatis
                        });
                    </script>
                    <script>
                        document.getElementById("nik").addEventListener("input", function (e) {
                            this.value = this.value.replace(/[^0-9]/g, ''); // Menghapus karakter non-angka
                        });

                        document.getElementById("no_hp").addEventListener("input", function (e) {
                            this.value = this.value.replace(/[^0-9]/g, ''); // Menghapus karakter non-angka
                        });
                    </script>

                </form>
            </div>
        </div>
    </div>

    {{-- MODAL ADD --}}

    {{-- MODAL DELETE --}}

    @foreach ($penduduk as $r)
        <div class="modal fade" id="modalDelete{{ $r->id }}" tabindex="-1" aria-labelledby="modalHapusBarang"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-circle mb-2"
                            style="color: #e74a3b; font-size:120px; justify-content:center; display:flex"></i>
                        <h5 class="text-center">Apakah anda yakin ingin menghapus Data {{ $r->nama }} Keluarga ini ?</h5>
                    </div>
                    <div class="modal-footer">
                        <form action={{ url('kk/' . $r->id) . '/showPenduduk/delete' }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Iya, Hapus Saja</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- END MODAL DELETE --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-content">
                        <div class="card-body">
                            <h5 class="card-title">Data Kartu Keluarga</h5>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="fw-bold me-2">Kepala Keluarga:</span>
                                        <span class="text-break">{{ $data->kepala_keluarga }}</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="fw-bold me-2">Nomor KK:</span>
                                        <span class="text-break">{{ $data->no_kk }}</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="fw-bold me-2">RW / RT:</span>
                                        <span>{{ $data->rw->rw }} / {{ $data->rt->rt }}</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="fw-bold me-2">Status Ekonomi:</span>
                                        <span>{{ $data->status_ekonomi }}</span>
                                    </div>
                                    <div class="col-12 col-md-6 d-flex">
                                        <span class="fw-bold me-2">Jumlah Individu:</span>
                                        <span>{{ \App\DataPenduduk::where('kk_id', $data->id)->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="py-3">
    <h3>Data Keluarga</h3>
</div>

<section class="section">
    <div class="card shadow mb-5">
        <div class="card-body">
            <div class="d-flex flex-column flex-sm-row gap-2">
                <button class="btn btn-primary rounded-pill mb-2 mb-sm-0" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>

                @if (count($penduduk) > 0)
                    <a href="{{ url('/penduduk/export/' . $data->id) }}" class="btn btn-danger rounded-pill">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                @endif
            </div>

            <!-- Tambahkan class 'table-responsive' untuk tampilan mobile -->
            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <thead class="text-center">
                        <tr>
                            <th>No.</th>
                            <th>Foto KTP</th>
                            <th>Nama</th>
                            <th class="d-none d-md-table-cell">NIK</th>
                            <th>Telepon</th>
                            <th class="d-none d-lg-table-cell">Alamat</th>
                            <th class="d-none d-md-table-cell">RT/RW</th>
                            <th> </th>
                        </tr>
                    </thead>

                    <tbody>
    @php
        // Pastikan setiap status ada, bahkan jika tidak ada datanya
        $sortedPenduduk = collect([
            'Kepala Rumah Tangga' => collect(),
            'Isteri' => collect(),
            'Anak' => collect(),
            'Lainnya' => collect(),
        ]);

        // Grouping berdasarkan status keluarga
        foreach ($penduduk as $p) {
            $status = trim($p->status_keluarga); // Hindari spasi berlebih
            if ($status === 'Kepala Rumah Tangga') {
                $sortedPenduduk['Kepala Rumah Tangga']->push($p);
            } elseif ($status === 'Isteri') {
                $sortedPenduduk['Isteri']->push($p);
            } elseif ($status === 'Anak') {
                $sortedPenduduk['Anak']->push($p);
            } else {
                $sortedPenduduk['Lainnya']->push($p);
            }
        }

        // Sorting Anak & Lainnya berdasarkan umur tertua
        $sortedPenduduk['Anak'] = $sortedPenduduk['Anak']->sortBy('tanggal_lahir');
        $sortedPenduduk['Lainnya'] = $sortedPenduduk['Lainnya']->sortBy('tanggal_lahir');

        // Gabungkan data secara berurutan
        $finalSorted = $sortedPenduduk['Kepala Rumah Tangga']
            ->merge($sortedPenduduk['Isteri'])
            ->merge($sortedPenduduk['Anak'])
            ->merge($sortedPenduduk['Lainnya']);
    @endphp

    @foreach ($finalSorted as $index => $pd)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <a href="#" onclick="showImageModal('{{ asset('storage/foto_ktp/' . ($pd->image_ktp ?: 'default.jpg')) }}')">
                    <img src="{{ asset('storage/foto_ktp/' . ($pd->image_ktp ?: 'default.jpg')) }}"
                        alt="Foto KTP" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                </a>
            </td>
            <td>{{ $pd->nama }}</td>
            <td class="d-none d-md-table-cell">{{ $pd->nik }}</td>
            <td>{{ $pd->no_hp }}</td>
            <td class="d-none d-lg-table-cell">{{ $pd->alamat }}</td>
            <td class="d-none d-md-table-cell">{{ $pd->rt->rt }} / {{ $pd->rw->rw }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0" type="button"
                        id="dropdownMenuButton{{ $pd->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3"
                        aria-labelledby="dropdownMenuButton{{ $pd->id }}">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"
                                data-bs-toggle="modal" data-bs-target="#infoData{{ $pd->id }}">
                                <i class="fas fa-eye text-info me-2"></i> Lihat
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#"
                                data-bs-toggle="modal" data-bs-target="#editData{{ $pd->id }}">
                                <i class="fas fa-edit text-primary me-2"></i> Edit
                            </a>
                        </li>
                        @hasrole('superadmin|rw|rt')
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="#"
                                data-bs-toggle="modal" data-bs-target="#modalDelete{{ $pd->id }}">
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

    @include('kk/editPenduduk')
    @include('kk/infoPenduduk')

</section>

<!-- MODAL UNTUK PREVIEW GAMBAR -->
<div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto KTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="imageContainer" class="d-inline-block" style="max-width: 100%; max-height: 80vh; overflow: hidden;">
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
        rotationAngle = 0; // Reset rotasi saat modal dibuka
        modalImage.style.transform = "rotate(0deg)";
        modalImage.src = imageUrl;
        currentImageUrl = imageUrl;

        // Atur tombol download gambar
        const downloadImageBtn = document.getElementById("downloadImageBtn");
        downloadImageBtn.href = imageUrl;
        downloadImageBtn.setAttribute("download", "foto_ktp.jpg");

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
            doc.save('foto_ktp.pdf');
        };
    }
</script>

<!-- IMPORT LIBRARY jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


        </div>
    </div>
@endsection