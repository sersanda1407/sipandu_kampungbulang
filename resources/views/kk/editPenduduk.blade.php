@foreach ($penduduk as $d)
    <style>
        .modal-body {
            max-height: 80vh;
            /* Sesuaikan dengan kebutuhan */
            overflow-y: auto;
        }
    </style>
    <div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
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
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{$d->nama}}" name="nama" id="exampleInputEmail1"
                                aria-describedby="emailHelp" required @hasrole('rw|rt|warga') readonly @endhasrole>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Nomor NIK</label>
                            <input type="text" class="form-control" value="{{$d->nik}}" name="nik" id="nik{{ $d->id }}"
                                maxlength="16" minlength="16" required @hasrole('rw|rt|warga') readonly @endhasrole>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nomor Telepon / WhatsApp</label>
                            <input class="form-control" type="text" placeholder="No Telepon / WhatsApp"
                                value="{{$d->no_hp}}" name="no_hp" id="no_hp{{ $d->id }}" maxlength="12" minlength="8"
                                required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <label for="">Usia</label>
                                <input class="form-control" type="text" placeholder="Usia" value="{{$d->usia}}" name="usia"
                                    id="usia{{ $d->id }}" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Tempat Lahir</label>
                                <input class="form-control" type="text" placeholder="Tempat Lahir" value="{{$d->tmp_lahir}}"
                                    name="tmp_lahir" required @hasrole('rw|rt|warga') readonly @endhasrole>
                            </div>
                            <div class="col-sm-4">
                                <label for="">Tanggal Lahir</label>
                                <input class="form-control" type="date" placeholder="Tanggal Lahir" value={{ old('tgl_lahir', $d->tgl_lahir) }} name="tgl_lahir" id="tgl_lahir{{ $d->id }}" required
                                    oninput="hitungUsia({{ $d->id }})" @hasrole('rw|rt|warga') readonly @endhasrole>
                            </div>
                        </div>
                        <div class="mb-3">
    <label for="">Jenis Kelamin</label>
    @hasrole('warga')
        <select class="form-select" disabled>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" {{ $d->gender === 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ $d->gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <input type="hidden" name="gender" value="{{ $d->gender }}">
    @else
        <select class="form-select" name="gender" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" {{ $d->gender === 'Laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ $d->gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
    @endhasrole
</div>

                        <div class="mb-3">
    <label for="">Agama</label>
    @hasrole('rw|rt|warga')
        <select class="form-select" name="agama_disabled" disabled>
            <option value="">-- Pilih Agama --</option>
            <option value="Islam" {{ $d->agama === 'Islam' ? 'selected' : '' }}>Islam</option>
            <option value="Katolik" {{ $d->agama === 'Katolik' ? 'selected' : '' }}>Katolik</option>
            <option value="Protestan" {{ $d->agama === 'Protestan' ? 'selected' : '' }}>Protestan</option>
            <option value="Konghucu" {{ $d->agama === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
            <option value="Buddha" {{ $d->agama === 'Buddha' ? 'selected' : '' }}>Buddha</option>
            <option value="Hindu" {{ $d->agama === 'Hindu' ? 'selected' : '' }}>Hindu</option>
        </select>
        <input type="hidden" name="agama" value="{{ $d->agama }}">
    @else
        <select class="form-select" name="agama" required>
            <option value="">-- Pilih Agama --</option>
            <option value="Islam" {{ $d->agama === 'Islam' ? 'selected' : '' }}>Islam</option>
            <option value="Katolik" {{ $d->agama === 'Katolik' ? 'selected' : '' }}>Katolik</option>
            <option value="Protestan" {{ $d->agama === 'Protestan' ? 'selected' : '' }}>Protestan</option>
            <option value="Konghucu" {{ $d->agama === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
            <option value="Buddha" {{ $d->agama === 'Buddha' ? 'selected' : '' }}>Buddha</option>
            <option value="Hindu" {{ $d->agama === 'Hindu' ? 'selected' : '' }}>Hindu</option>
        </select>
    @endhasrole
</div>


<div class="mb-3">
    <label for="">Alamat</label>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="alamatSesuaiKK{{ $d->id }}"
            onchange="autofillAlamat({{ $d->id }}, '{{ addslashes($d->kk->alamat ?? '') }}')">
        <label class="form-check-label" for="alamatSesuaiKK{{ $d->id }}">
            Isi Alamat sesuai Kartu Keluarga
        </label>
    </div>
    
    <textarea class="form-control" name="alamat" id="alamat{{ $d->id }}" cols="30" rows="3"
        @hasrole('rw|rt|warga') readonly @endhasrole>{{ $d->alamat }}</textarea>
</div>



                        <div class="row">
                            <div class="col">
    <div class="mb-3">
        <label for="">Status Pernikahan</label>
        @hasrole('rw|rt|warga')
            <select class="form-select" disabled>
                <option value="">-- Pilih Status Pernikahan --</option>
                <option value="Kawin" {{ $d->status_pernikahan === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                <option value="Belum Kawin" {{ $d->status_pernikahan === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                <option value="Cerai" {{ $d->status_pernikahan === 'Cerai' ? 'selected' : '' }}>Cerai</option>
            </select>
            <input type="hidden" name="status_pernikahan" value="{{ $d->status_pernikahan }}">
        @else
            <select class="form-select" name="status_pernikahan" required>
                <option value="">-- Pilih Status Pernikahan --</option>
                <option value="Kawin" {{ $d->status_pernikahan === 'Kawin' ? 'selected' : '' }}>Kawin</option>
                <option value="Belum Kawin" {{ $d->status_pernikahan === 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                <option value="Cerai" {{ $d->status_pernikahan === 'Cerai' ? 'selected' : '' }}>Cerai</option>
            </select>
        @endhasrole
    </div>
</div>

                            <div class="col">
    <div class="mb-3">
        <label for="">Status Di Keluarga</label>
        @hasrole('rw|rt|warga')
            <select class="form-select" disabled>
                <option value="">-- Pilih Status Dikeluarga --</option>
                <option value="Kepala Rumah Tangga" {{ $d->status_keluarga === 'Kepala Rumah Tangga' ? 'selected' : '' }}>Kepala Keluarga</option>
                <option value="Isteri" {{ $d->status_keluarga === 'Isteri' ? 'selected' : '' }}>Isteri</option>
                <option value="Anak" {{ $d->status_keluarga === 'Anak' ? 'selected' : '' }}>Anak</option>
                <option value="Lainnya" {{ $d->status_keluarga === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="hidden" name="status_keluarga" value="{{ $d->status_keluarga }}">
        @else
            <select class="form-select" name="status_keluarga" required>
                <option value="">-- Pilih Status Dikeluarga --</option>
                <option value="Kepala Rumah Tangga" {{ $d->status_keluarga === 'Kepala Rumah Tangga' ? 'selected' : '' }}>Kepala Keluarga</option>
                <option value="Isteri" {{ $d->status_keluarga === 'Isteri' ? 'selected' : '' }}>Isteri</option>
                <option value="Anak" {{ $d->status_keluarga === 'Anak' ? 'selected' : '' }}>Anak</option>
                <option value="Lainnya" {{ $d->status_keluarga === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        @endhasrole
    </div>
</div>

                        </div>

                        <div class="mb-3">
                            <label for="">Pekerjaan <span class="text-danger">*</span></label>
                            <select class="form-select" name="pekerjaan" required>
                                <option value="">-- Pilih Pekerjaan --</option>
                                <option value="PNS" {{ $d->pekerjaan == 'PNS' ? 'selected' : '' }}>Pegawai Negeri Sipil
                                </option>
                                <option value="TNI" {{ $d->pekerjaan == 'TNI' ? 'selected' : '' }}>Tentara Nasional Indonesia
                                </option>
                                <option value="POLRI" {{ $d->pekerjaan == 'POLRI' ? 'selected' : '' }}>Polisi</option>
                                <option value="P3K" {{ $d->pekerjaan == 'P3K' ? 'selected' : '' }}>Pegawai P3K</option>
                                <option value="Honorer" {{ $d->pekerjaan == 'Honorer' ? 'selected' : '' }}>Pegawai Honorer
                                </option>
                                <option value="Wiraswasta" {{ $d->pekerjaan == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta
                                </option>
                                <option value="Buruh" {{ $d->pekerjaan == 'Buruh' ? 'selected' : '' }}>Buruh Harian Lepas
                                </option>
                                <option value="Wirausaha" {{ $d->pekerjaan == 'Wirausaha' ? 'selected' : '' }}>
                                    Wirausaha/Pengusaha</option>
                                <option value="Guru" {{ $d->pekerjaan == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Pensiunan/Purnawirawan" {{ $d->pekerjaan == 'Pensiunan/Purnawirawan' ? 'selected' : '' }}>Pensiunan TNI/POLRI/PNS</option>
                                <option value="Ibu Rumah Tangga" {{ $d->pekerjaan == 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                    Ibu Rumah Tangga</option>
                                <option value="Pelajar/Mahasiswa" {{ $d->pekerjaan == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Mahasiswa/Pelajar</option>
                                <option value="Tidak Bekerja" {{ $d->pekerjaan == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak
                                    Bekerja</option>
                                <option value="Lainnya" {{ $d->pekerjaan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

<div class="mb-3">
  <label for="gaji" class="form-label">Pendapatan</label>
  <div class="input-group">
    <input type="text" name="gaji" class="form-control gaji" value="{{ number_format($d->gaji, 0, ',', '.') }}" required>
  </div>
</div>

                        <label>Status</label>
                        <div class="mb-3">
                            <select class="form-select" name="status_sosial" required>
                                <option value="">-- Pilih Sosial --</option>
                                <option value="hidup" {{ $d->status_sosial === 'hidup' ? 'selected' : '' }}>Masih Hidup
                                </option>
                                <option value="mati" {{ $d->status_sosial === 'mati' ? 'selected' : '' }}>Sudah Meninggal
                                </option>
                                <option value="gajelas" {{ $d->status_sosial === 'gajelas' ? 'selected' : '' }}>Tanpa
                                    Keterangan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto KTP</label>
                            <input type="file" class="form-control" name="image_ktp">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                            <br>
                            @if ($d->image_ktp)
                                <img src="{{ asset('storage/foto_ktp/' . $d->image_ktp) }}" alt="Foto KTP" width="100"
                                    height="70">
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
        <script>
let previousAlamatValues = {};

function autofillAlamat(id, alamatKK) {
    const checkbox = document.getElementById('alamatSesuaiKK' + id);
    const textarea = document.getElementById('alamat' + id);

    if (checkbox.checked) {
        // Simpan nilai sebelumnya sebelum diubah
        previousAlamatValues[id] = textarea.value;
        textarea.value = alamatKK;
        textarea.setAttribute('readonly', true);
    } else {
        // Kembalikan nilai sebelumnya
        textarea.value = previousAlamatValues[id] || '';
        textarea.removeAttribute('readonly');
    }
}
</script>
        <script>
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
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Fungsi untuk membatasi hanya angka
                function onlyNumbers(input) {
                    input.value = input.value.replace(/[^0-9]/g, ''); // Menghapus karakter selain angka
                }

                // Menyeleksi semua input dengan nama 'nik' dan 'no_hp'
                document.querySelectorAll("input[name='nik'], input[name='no_hp']").forEach(function (input) {
                    input.addEventListener("input", function () {
                        onlyNumbers(this);
                    });
                });
            });
        </script>
        <script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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




    </div>
@endforeach