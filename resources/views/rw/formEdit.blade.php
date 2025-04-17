@foreach ($data as $d)
<div class="modal fade" id="editData{{ $d->id }}" tabindex="-1" aria-labelledby="editRWLabel{{ $d->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light text-white">
                <h5 class="modal-title" id="editRWLabel{{ $d->id }}">Edit RW</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('rw/update/' . $d->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Ketua RW</label>
                        <input type="text" class="form-control text-capitalize" value="{{ $d->nama }}" name="nama" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Nomor Telepon / WhatsApp</label>
                        <input type="text" class="form-control" value="{{ $d->no_hp }}" name="no_hp"
                            id="no_hp{{ $d->id }}" maxlength="12" minlength="8" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">RW</label>
                        <input type="text" class="form-control" value="{{ $d->rw }}" name="rw" id="rw{{ $d->id }}" maxlength="3" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Periode</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="periode_awal" maxlength="4" value="{{ $d->periode_awal }}" required>
                            </div>
                            <div class="col-md-2 text-center">s/d</div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="periode_akhir" maxlength="4" value="{{ $d->periode_akhir }}" required>
                            </div>
                        </div>
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
        document.querySelectorAll("#no_hp{{ $d->id }}, #rw{{ $d->id }}").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    });
</script>
@endforeach
