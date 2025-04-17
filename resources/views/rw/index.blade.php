@extends('layouts.master')

@section('master')
   
<!-- Modal Tambah Data RW -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data RW Baru</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('rw/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Ketua RW</label>
                        <input type="text" class="form-control text-capitalize" placeholder="Nama Lengkap" name="nama" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Nomor Telepon / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp" 
                            placeholder="No Telepon / WhatsApp" maxlength="12" minlength="8" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">RW</label>
                        <input type="text" class="form-control" name="rw" id="rw" placeholder="No Wilayah RW" maxlength="3" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Periode</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="periode_awal" placeholder="Tahun Awal Menjabat" maxlength="4" required>
                            </div>
                            <div class="col-md-2 text-center">s/d</div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="periode_akhir" placeholder="Tahun Akhir Menjabat" maxlength="4" required>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("#no_hp, #rw, input[name='periode_awal'], input[name='periode_akhir']")
            .forEach(input => input.addEventListener("input", () => {
                input.value = input.value.replace(/\D/g, ''); // Hanya angka
            }));
    });
</script>


    {{-- END MODAL ADD --}}

    {{-- MODAL DELETE --}}
    @foreach ($data as $r)
        <div class="modal fade" id="modalDelete{{ $r->id }}" tabindex="-1" aria-labelledby="modalHapusBarang"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <i class="fas fa-exclamation-circle mb-2"
                            style="color: #e74a3b; font-size:120px; justify-content:center; display:flex"></i>
                        <h5 class="text-center">Apakah anda yakin ingin menghapus Data Ketua RW {{ $r->rw }} atas nama {{ $r->nama }} ?</h5>
                    </div>
                    <div class="modal-footer">
                        <form action={{ url('/rw/delete/' . $r->id) }} method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Yakin, Hapus Saja</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- END MODAL DELETE --}}
    <div class="container-fluid">
        <div class="row">
            <div class="py-3">
                <h3>Data RW</h3>
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
                                    <th>Ketua RW</th>
                                    <th>No Telepon</th>
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
                                        <td>{{ $d->rw }}</td>
                                        <td>{{ $d->periode_awal }} - {{ $d->periode_akhir }}</td>
                                        <td>
                                            <a class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#editData{{ $d->id }}"><i
                                                    class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete{{ $d->id }}"><i
                                                    class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        @include('rw/formEdit')
        </section>
    </div>
@endsection
