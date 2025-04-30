@extends('layouts.master')

@section('master')
    {{-- MODAL DELETE --}}
    @foreach ($data as $r)
        <div class="modal fade" id="modalDelete{{ $r->id }}" tabindex="-1" aria-labelledby="modalHapusBarang"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-circle mb-2"
                            style="color: #e74a3b; font-size:120px; justify-content:center; display:flex"></i>
                        <h5 class="text-center">Apakah anda yakin ingin menghapus Data Ketua RT {{ $r->rt }} / RW
                            {{ $r->Rw->rw }} atas nama {{ $r->nama }} ?</h5>
                    </div>
                    <div class="modal-footer">
                        <form action={{ url('/rt/delete/' . $r->id) }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- END MODAL DELETE --}}

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data RT Baru</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('rt/store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Ketua RT</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Nama Lengkap" name="nama"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" name="no_hp" id="no_hp"
                                placeholder="Masukkan No Telepon / WhatsApp" maxlength="12" minlength="8" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">RT</label>
                                    <input type="text" class="form-control" placeholder="No Wilayah RT" name="rt" id="rt"
                                        maxlength="3" required>
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
                            <label class="form-label">Periode</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_awal"
                                        placeholder="Tahun Awal Menjabat" maxlength="4" required>
                                </div>
                                <div class="col-md-2 text-center">s/d</div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="periode_akhir"
                                        placeholder="Tahun Akhir Menjabat" maxlength="4" required>
                                </div>
                            </div>
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
            const hpInput = document.getElementById('no_hp');
            const rtInput = document.getElementById('rt');
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
            document.querySelectorAll("#no_hp, #rt, #periode_awal, #periode_akhir").forEach(function (input) {
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
                        <button class="btn btn-primary rounded-pill mb-3" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Ketua RT</th>
                                    <th>No Telepon</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>Periode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->no_hp }}</td>
                                        <td>{{ $d->rt }}</td>
                                        <td>{{ $d->Rw->rw }}</td>
                                        <td>{{ $d->periode_awal }} / {{ $d->periode_akhir }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button"
                                                    id="dropdownMenuRT{{ $d->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuRT{{ $d->id }}">
                                                    <li>
                                                        <button class="dropdown-item text-success" data-bs-toggle="modal"
                                                            data-bs-target="#editData{{ $d->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalDelete{{ $d->id }}">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </li>
                                                    @hasrole('superadmin')
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