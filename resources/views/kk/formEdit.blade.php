@foreach ($data as $d)
    <div class="modal fade text-left" id="editData{{ $d->id }}" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Edit Data Keluarga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ url('kk/update/' . $d->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="mb-3">
                            <label class="form-label">Kepala Keluarga</label>
                            <input type="text" class="form-control" value="{{ $d->kepala_keluarga }}"
                                name="kepala_keluarga" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No.KK</label>
                            <input type="text" class="form-control" value="{{ $d->no_kk }}" name="no_kk"
                                maxlength="16" minlength="16" required>
                        </div>
                        <div class="row">
                            {{-- =====================  RW  ===================== --}}
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">RW</label>
                                    <select id="rwSelect" class="form-select" name="rw_id">
                                        <option value="">-- Pilih No Wilayah RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ $rw->id }}"
                                                {{ $d->rw_id == $rw->id ? 'selected' : '' }}>
                                                {{ $rw->rw }} &nbsp;|&nbsp; {{ $rw->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- =====================  RT  ===================== --}}
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">RT</label>
                                    <select id="rtSelect" class="form-select" name="rt_id">
                                        <option value="">-- Pilih No Wilayah RT --</option>
                                        @foreach ($selectRt as $rt)
                                            <option value="{{ $rt->id }}"
                                                    data-rw="{{ $rt->rw_id }}"      {{-- kunci penting --}}
                                                    {{ $d->rt_id == $rt->id ? 'selected' : '' }}>
                                                {{ $rt->rt }} &nbsp;|&nbsp; {{ $rt->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const rwSelect = document.getElementById('rwSelect');
                            const rtSelect = document.getElementById('rtSelect');
                            const rtOptions = Array.from(rtSelect.options);   // cache semua option RT

                            function filterRT() {
                                const rwId = rwSelect.value;

                                // 1. kosongkan dulu RT yang tampil
                                rtSelect.innerHTML = '';

                                // 2. selalu sisipkan option kosong di atas
                                rtSelect.appendChild(rtOptions[0].cloneNode(true));

                                // 3. sisipkan option yang rw‑nya cocok
                                rtOptions.forEach(opt => {
                                    if (opt.dataset.rw === rwId) {
                                        rtSelect.appendChild(opt.cloneNode(true));
                                    }
                                });

                                // 4. reset pilihan jika pilihan lama tidak ada di list baru
                                if (![...rtSelect.options].some(o => o.value === rtSelect.value)) {
                                    rtSelect.value = '';
                                }
                            }

                            // pertama kali jalan (untuk halaman edit)
                            filterRT();

                            // tiap kali RW berubah
                            rwSelect.addEventListener('change', filterRT);
                        });
                        </script>


                        <div class="mb-3">
                            <label class="form-label">Status Ekonomi</label>
                            <select class="form-select" name="status_ekonomi">
                                <option value="">-- Pilih Status Ekonomi --</option>
                                <option value="Mampu" {{ $d->status_ekonomi == 'Mampu' ? 'selected' : '' }}>Mampu</option>
                                <option value="Tidak Mampu" {{ $d->status_ekonomi == 'Tidak Mampu' ? 'selected' : '' }}>Tidak Mampu</option>
                            </select>
                        </div>
                       

                        <div class="mb-3">
                            <label class="form-label">Foto KK</label>
                            <input type="file" class="form-control" name="image">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                            <br>
                            @if ($d->image)
                            <img src="{{ asset('storage/foto_kk/' . $d->image) }}" alt="Foto KK" width="100"
                            height="70">
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
@endforeach
