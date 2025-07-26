@foreach ($penduduk as $d)
<style>
    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }
</style>

<div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Edit Data Penduduk</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="{{ url('kk/' . $d->id . '/showPenduduk/edit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Seluruh isi form tetap di sini, tidak menggunakan include -->
<div class="mb-3">
    <label for="nama" class="form-label">Nama Lengkap</label>
    <input type="text" class="form-control" name="nama" value="{{ $d->nama }}" required @hasrole('rw|rt|warga') readonly @endhasrole>
</div>

<div class="mb-3">
    <label for="nik" class="form-label">NIK</label>
    <input type="text" class="form-control" name="nik" id="nik{{ $d->id }}" value="{{ $d->nik }}" maxlength="16" minlength="16" required @hasrole('rw|rt|warga') readonly @endhasrole>
</div>

<div class="mb-3">
    <label for="no_hp" class="form-label">No Telepon / WhatsApp</label>
    <input type="text" class="form-control" name="no_hp" id="no_hp{{ $d->id }}" value="{{ $d->no_hp }}" maxlength="13" minlength="8" required>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="usia" class="form-label">Usia</label>
        <input type="text" class="form-control" name="usia" id="usia{{ $d->id }}" value="{{ $d->usia }}" readonly>
    </div>
    <div class="col-md-4">
        <label for="tmp_lahir" class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" name="tmp_lahir" value="{{ $d->tmp_lahir }}" required @hasrole('rw|rt|warga') readonly @endhasrole>
    </div>
    <div class="col-md-4">
        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir{{ $d->id }}" value="{{ $d->tgl_lahir }}" required oninput="hitungUsia({{ $d->id }})" @hasrole('rw|rt|warga') readonly @endhasrole>
    </div>
</div>

<div class="mb-3">
    <label for="gender" class="form-label">Jenis Kelamin</label>
    @hasrole('warga')
        <select class="form-select" disabled>
            <option value="Laki-laki" {{ $d->gender === 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ $d->gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <input type="hidden" name="gender" value="{{ $d->gender }}">
    @else
        <select class="form-select" name="gender" required>
            <option value="Laki-laki" {{ $d->gender === 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ $d->gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
    @endhasrole
</div>

<div class="mb-3">
    <label for="agama" class="form-label">Agama</label>
    @hasrole('rw|rt|warga')
        <select class="form-select" disabled>
            @foreach(['Islam', 'Katolik', 'Protestan', 'Konghucu', 'Buddha', 'Hindu'] as $agama)
                <option value="{{ $agama }}" {{ $d->agama === $agama ? 'selected' : '' }}>{{ $agama }}</option>
            @endforeach
        </select>
        <input type="hidden" name="agama" value="{{ $d->agama }}">
    @else
        <select class="form-select" name="agama" required>
            <option value="">-- Pilih Agama --</option>
            @foreach(['Islam', 'Katolik', 'Protestan', 'Konghucu', 'Buddha', 'Hindu'] as $agama)
                <option value="{{ $agama }}" {{ $d->agama === $agama ? 'selected' : '' }}>{{ $agama }}</option>
            @endforeach
        </select>
    @endhasrole
</div>

<div class="mb-3">
    <label for="alamat" class="form-label">Alamat</label>
    <div class="form-check mb-2">
        <input type="checkbox" class="form-check-input" id="alamatSesuaiKK{{ $d->id }}" onchange="autofillAlamat({{ $d->id }}, '{{ addslashes($d->kk->alamat ?? '') }}')">
        <label class="form-check-label" for="alamatSesuaiKK{{ $d->id }}">Isi Alamat sesuai Kartu Keluarga</label>
    </div>
    <textarea class="form-control" name="alamat" id="alamat{{ $d->id }}" rows="3" @hasrole('rw|rt|warga') readonly @endhasrole>{{ $d->alamat }}</textarea>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
        @hasrole('rw|rt|warga')
            <select class="form-select" disabled>
                @foreach(['Kawin', 'Belum Kawin', 'Cerai'] as $status)
                    <option value="{{ $status }}" {{ $d->status_pernikahan === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            <input type="hidden" name="status_pernikahan" value="{{ $d->status_pernikahan }}">
        @else
            <select class="form-select" name="status_pernikahan" required>
                @foreach(['Kawin', 'Belum Kawin', 'Cerai'] as $status)
                    <option value="{{ $status }}" {{ $d->status_pernikahan === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        @endhasrole
    </div>
    <div class="col-md-6">
        <label for="status_keluarga" class="form-label">Status Di Keluarga</label>
        @hasrole('rw|rt|warga')
            <select class="form-select" disabled>
                @foreach(['Kepala Rumah Tangga', 'Isteri', 'Anak', 'Lainnya'] as $status)
                    <option value="{{ $status }}" {{ $d->status_keluarga === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            <input type="hidden" name="status_keluarga" value="{{ $d->status_keluarga }}">
        @else
            <select class="form-select" name="status_keluarga" required>
                @foreach(['Kepala Rumah Tangga', 'Isteri', 'Anak', 'Lainnya'] as $status)
                    <option value="{{ $status }}" {{ $d->status_keluarga === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        @endhasrole
    </div>
</div>

@php
    $daftarPekerjaan = ['PNS/P3K', 'TNI', 'POLRI', 'Pegawai Honorer', 'Wiraswasta', 'Buruh Harian Lepas / Freelance', 'Wirausaha', 'Guru', 'Pensiunan/Purnawirawan', 'Ibu Rumah Tangga', 'Pelajar/Mahasiswa', 'Tidak Bekerja'];
    $pekerjaanUtama = in_array($d->pekerjaan, $daftarPekerjaan) ? $d->pekerjaan : 'Lainnya';
    $pekerjaanLainnya = !in_array($d->pekerjaan, $daftarPekerjaan) ? $d->pekerjaan : '';
@endphp

<div class="mb-3">
    <label for="pekerjaan" class="form-label">Pekerjaan</label>
    <select class="form-select" name="pekerjaan" id="pekerjaan{{ $d->id }}" onchange="togglePekerjaanLainnya({{ $d->id }})" required>
        <option value="">-- Pilih Pekerjaan --</option>
        @foreach($daftarPekerjaan as $job)
            <option value="{{ $job }}" {{ $pekerjaanUtama === $job ? 'selected' : '' }}>{{ $job }}</option>
        @endforeach
        <option value="Lainnya" {{ $pekerjaanUtama === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select>
</div>

<div class="mb-3" id="pekerjaanLainnyaContainer{{ $d->id }}" style="display: none;">
    <label for="pekerjaan_lainnya" class="form-label">Pekerjaan Lainnya</label>
    <input type="text" class="form-control" name="pekerjaan_lainnya" id="pekerjaan_lainnya{{ $d->id }}"
        value="{{ old('pekerjaan_lainnya', $pekerjaanLainnya) }}">
</div>



<div class="mb-3">
    <label for="gaji" class="form-label">Pendapatan</label>
    <input type="text" class="form-control gaji" name="gaji" value="{{ number_format($d->gaji, 0, ',', '.') }}" required>
</div>

<div class="mb-3">
    <label for="status_sosial" class="form-label">Status Sosial</label>
    <select class="form-select" name="status_sosial" required>
        @foreach(['hidup' => 'Masih Hidup', 'mati' => 'Sudah Meninggal', 'gajelas' => 'Tanpa Keterangan'] as $val => $label)
            <option value="{{ $val }}" {{ $d->status_sosial === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="image_ktp" class="form-label">Foto KTP</label>
    <input type="file" class="form-control" name="image_ktp" id="upload_ktp_edit" accept="image/*">
    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small><br>
    @if ($d->image_ktp)
        <img src="{{ asset('storage/foto_ktp/' . $d->image_ktp) }}" alt="Foto KTP" width="100" height="70">
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('upload_ktp_edit').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png, gif, webp)'
                    });
                    this.value = '';
                }
            }
        });

        let previousAlamatValues = {};

        function autofillAlamat(id, alamatKK) {
            const checkbox = document.getElementById('alamatSesuaiKK' + id);
            const textarea = document.getElementById('alamat' + id);

            if (checkbox.checked) {
                previousAlamatValues[id] = textarea.value;
                textarea.value = alamatKK;
                textarea.setAttribute('readonly', true);
            } else {
                textarea.value = previousAlamatValues[id] || '';
                textarea.removeAttribute('readonly');
            }
        }

        function hitungUsia(id) {
            let tglLahir = document.getElementById('tgl_lahir' + id).value;
            if (tglLahir) {
                let birthDate = new Date(tglLahir);
                let today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                let monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                document.getElementById('usia' + id).value = age;
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            function onlyNumbers(input) {
                input.value = input.value.replace(/[^0-9]/g, '');
            }
            document.querySelectorAll("input[name='nik'], input[name='no_hp']").forEach(function (input) {
                input.addEventListener("input", function () {
                    onlyNumbers(this);
                });
            });
        });

        $(document).ready(function () {
            $('.gaji').each(function () {
                formatGajiField(this);
            });

            $(document).on('input', '.gaji', function () {
                let angka = $(this).val().replace(/[^0-9]/g, '');
                if (angka) {
                    $(this).val('Rp.' + formatRupiah(angka));
                } else {
                    $(this).val('');
                }
            });

            function formatRupiah(angka) {
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatGajiField(input) {
                let angka = $(input).val().replace(/[^0-9]/g, '');
                if (angka) {
                    $(input).val('Rp.' + formatRupiah(angka));
                }
            }
        });
    </script>
<script>
function togglePekerjaanLainnya(id) {
    const pekerjaanSelect = document.getElementById('pekerjaan' + id);
    const lainnyaContainer = document.getElementById('pekerjaanLainnyaContainer' + id);
    if (pekerjaanSelect.value === 'Lainnya') {
        lainnyaContainer.style.display = 'block';
    } else {
        lainnyaContainer.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    @foreach ($penduduk as $d)
        togglePekerjaanLainnya({{ $d->id }});
    @endforeach
});
</script>


</div>
@endforeach
