@foreach ($data as $d)
<div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Edit Data Kartu Keluarga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('kk/update/' . $d->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto">
                    <div class="mb-3">
                        <label class="form-label">Kepala Keluarga</label>
                        <input type="text" class="form-control" name="kepala_keluarga" value="{{ $d->kepala_keluarga }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No.KK</label>
                        <input id="inputEditNoKK{{ $d->id }}" type="text" class="form-control" name="no_kk" value="{{ $d->no_kk }}" maxlength="16" minlength="16" required @hasrole('rw|rt|warga') readonly @endhasrole>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            document.getElementById('inputEditNoKK{{ $d->id }}').addEventListener('input', (e) => {
                                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                            });
                        });
                    </script>

                    <div class="row">
                        {{-- RW --}}
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">RW</label>
                                @hasrole('warga')
                                    <select id="rwSelect{{ $d->id }}" class="form-select" disabled>
                                        <option value="">-- Pilih No Wilayah RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ $rw->id }}" {{ $d->rw_id == $rw->id ? 'selected' : '' }}>
                                                {{ $rw->rw }} | {{ $rw->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="rw_id" value="{{ $d->rw_id }}">
                                @else
                                    <select id="rwSelect{{ $d->id }}" class="form-select" name="rw_id">
                                        <option value="">-- Pilih No Wilayah RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ $rw->id }}" {{ $d->rw_id == $rw->id ? 'selected' : '' }}>
                                                {{ $rw->rw }} | {{ $rw->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endhasrole
                            </div>
                        </div>

                        {{-- RT --}}
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">RT</label>
                                @hasrole('warga')
                                    <select id="rtSelect{{ $d->id }}" class="form-select" disabled>
                                        <option value="">-- Pilih No Wilayah RT --</option>
                                        @foreach ($selectRt as $rt)
                                            <option value="{{ $rt->id }}" data-rw="{{ $rt->rw_id }}" {{ $d->rt_id == $rt->id ? 'selected' : '' }}>
                                                {{ $rt->rt }} | {{ $rt->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="rt_id" value="{{ $d->rt_id }}">
                                @else
                                    <select id="rtSelect{{ $d->id }}" class="form-select" name="rt_id">
                                        <option value="">-- Pilih No Wilayah RT --</option>
                                        @foreach ($selectRt as $rt)
                                            <option value="{{ $rt->id }}" data-rw="{{ $rt->rw_id }}" {{ $d->rt_id == $rt->id ? 'selected' : '' }}>
                                                {{ $rt->rt }} | {{ $rt->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endhasrole
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const rwSelect = document.getElementById('rwSelect{{ $d->id }}');
                            const rtSelect = document.getElementById('rtSelect{{ $d->id }}');
                            const rtOptions = Array.from(rtSelect.options);

                            function filterRT() {
                                const rwId = rwSelect.value;
                                rtSelect.innerHTML = '';
                                rtSelect.appendChild(rtOptions[0].cloneNode(true));
                                rtOptions.forEach(opt => {
                                    if (opt.dataset.rw === rwId) {
                                        rtSelect.appendChild(opt.cloneNode(true));
                                    }
                                });
                                if (![...rtSelect.options].some(o => o.value == rtSelect.value)) {
                                    rtSelect.value = '';
                                }
                            }

                            filterRT();
                            rwSelect?.addEventListener('change', filterRT);
                        });
                    </script>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" required>{{ $d->alamat }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto KK</label>
                            <input type="file" class="form-control" name="image" id="upload_kk_edit" accept="image/*">
                            <small class="text-muted">Format yang diperbolehkan: JPG, JPEG, PNG. Maksimal ukuran file: 3 MB</small><br>
                             <small class="text-muted">*Kosongkan jika tidak ingin mengganti foto</small><br>
                                 @if ($d->image)
                                    <img src="{{ asset('storage/foto_kk/' . $d->image) }}" alt="Foto KK" width="100" height="70">
                                 @endif
                    </div>

                    <input type="hidden" name="oldImage" value="{{ $d->image }}">
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('upload_kk_edit').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png)'
                });
                this.value = '';
            }
        }

                const maxSize = 3 * 1024 * 1024;
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
</script>

@endforeach
