@foreach ($data as $d)
    <div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title" id="myModalLabel1">Edit Data RT</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                       
                    </button>
                </div>
                <form action="{{ url('rt/update/' . $d->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Ketua RT</label>
                            <input type="text" class="form-control text-capitalize" value="{{ $d->nama }}" 
                                name="nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" value="{{ $d->no_hp }}" 
                                name="no_hp" id="no_hp{{ $d->id }}" maxlength="12" minlength="8" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RT</label>
                                    <input type="text" class="form-control" value="{{ $d->rt }}"
                                        name="rt" maxlength="3" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RW</label>
                                    <select class="form-select" name="rw_id" required>
                                        <option value="">-- Pilih RW --</option>
                                        @foreach ($select as $da)
                                            <option value="{{ $da->id }}" 
                                                {{ $d->rw_id == $da->id ? 'selected' : '' }}>
                                                {{ $da->rw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Periode</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_awal"
                                        value="{{ $d->periode_awal }}" maxlength="4" required>
                                </div>
                                <div class="col-md-2 text-center">s/d</div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_akhir"
                                        value="{{ $d->periode_akhir }}" maxlength="4" required>
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
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("input[name='periode_akhir'], input[name='periode_awal'], input[name='no_hp'], input[name='rt']")
            .forEach(function (input) {
                input.addEventListener("input", function () {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Hanya memperbolehkan angka
                });
            });
    });
</script>

