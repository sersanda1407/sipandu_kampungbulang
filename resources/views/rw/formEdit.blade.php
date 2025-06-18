@foreach ($data as $d)
<div class="modal fade" id="editData{{ $d->id }}" tabindex="-1" aria-labelledby="editRWLabel{{ $d->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light text-white">
                <h5 class="modal-title" id="editRWLabel{{ $d->id }}">Edit RW</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('rw.update', $d->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body overflow-auto" style="max-height: 70vh;">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Ketua RW</label>
                        <input type="text" class="form-control text-capitalize" name="nama"
                            value="{{ old('nama', $d->nama) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">RW</label>
                        <input type="text" class="form-control" name="rw" id="rw_{{ $d->id }}" maxlength="3"
                            value="{{ old('rw', $d->rw) }}" required>
                    </div>
                    <div class="form-group mb-3">
    <label class="form-label">Alamat</label>
    <textarea class="form-control" name="alamat_rw" required>{{ old('alamat_rw', $d->alamat_rw) }}</textarea>
</div>


                    <div class="form-group mb-3">
                        <label class="form-label">Nomor Telepon / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp_{{ $d->id }}"
                            maxlength="12" minlength="8" value="{{ old('no_hp', $d->no_hp) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="gmail_rw" placeholder="Email aktif"
                             value="{{ old('gmail_rw', $d->gmail_rw) }}" required>
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
                        <label class="form-label">Foto RW</label>
                        <input type="file" class="form-control" name="image_rw" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small><br>
                        @if ($d->image_rw)
                            <img src="{{ asset('storage/foto_rw/' . $d->image_rw) }}" alt="Foto RW" width="100" height="70">
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputNoHp = document.getElementById("no_hp_{{ $d->id }}");
        const inputRw = document.getElementById("rw_{{ $d->id }}");

        [inputNoHp, inputRw].forEach(function (input) {
            if (input) {
                input.addEventListener("input", function () {
                    this.value = this.value.replace(/\D/g, '');
                });
            }
        });
    });
</script>
@endforeach
