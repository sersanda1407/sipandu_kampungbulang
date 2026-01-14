@foreach ($data as $d)
    <div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title" id="myModalLabel1">Edit Data RT</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ url('rt/update/' . $d->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body overflow-auto" style="max-height: 70vh;">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Ketua RT</label>
                            <input type="text" class="form-control text-capitalize" name="nama"
                                value="{{ old('nama', $d->nama) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RT</label>
                                    <input type="text" class="form-control" name="rt" id="rt_input_{{ $d->id }}"
                                        maxlength="3" value="{{ old('rt', $d->rt) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RW</label>
                                    <select class="form-select" name="rw_id" required>
                                        <option value="">-- Pilih RW --</option>
                                        @foreach ($select as $da)
                                            <option value="{{ $da->id }}" {{ $d->rw_id == $da->id ? 'selected' : '' }}>
                                                {{ $da->rw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat_rt"
                                required>{{ old('alamat_rt', $d->alamat_rt) }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" name="no_hp" id="no_hp_rt_{{ $d->id }}" maxlength="12"
                                minlength="8" value="{{ old('no_hp', $d->no_hp) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Email Pribadi</label>
                            <input type="email" class="form-control" name="gmail_rt"
                                value="{{ old('gmail_rt', $d->gmail_rt) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_awal" maxlength="4"
                                        value="{{ old('periode_awal', $d->periode_awal) }}" required>
                                </div>
                                <div class="col-md-2 text-center">s/d</div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_akhir" maxlength="4"
                                        value="{{ old('periode_akhir', $d->periode_akhir) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto RT</label>
                            <input type="file" class="form-control upload-gambar" name="image_rt" accept="image/*">
                             <small class="text-muted">Format yang diperbolehkan: JPG, JPEG, PNG. Maksimal ukuran file: 3 MB</small><br>
                            <small class="text-muted">*Kosongkan jika tidak ingin mengganti foto</small><br>
                            @if ($d->image_rt)
                                <img src="{{ asset('storage/foto_rt/' . $d->image_rt) }}" alt="Foto RT" width="100" height="70">
                            @endif
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.upload-gambar').forEach(function (input) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (file && !allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png)'
                    });
                    this.value = '';
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
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputNoHp = document.getElementById("no_hp_rt_{{ $d->id }}");
            const inputRt = document.getElementById("rt_input_{{ $d->id }}");

            [inputNoHp, inputRt].forEach(function (input) {
                if (input) {
                    input.addEventListener("input", function () {
                        this.value = this.value.replace(/\D/g, '');
                    });
                }
            });
        });
    </script>
@endforeach