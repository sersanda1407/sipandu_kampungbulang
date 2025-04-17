@extends('layouts.master')

@section('master')
    {{-- MODAL ADD --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="py-3">
                <h3>Data Penduduk</h3>
            </div>
            <section class="section">
                <div class="card shadow mb-5">
                    <div class="card-body">
                        @hasrole('superadmin')
                            <form id="filter-tanggal" method="GET" action="{{ url('/penduduk/filter') }}">
                                <div class="row justify-content-start mb-4">
                                    <div class="col-1">
                                        RW
                                        <select name="rw_id" id="" class="form-select">
                                            <option value="">-- Pilih RW --</option>
                                            @foreach ($selectRw as $rw)
                                                <option value="{{ $rw->id }}">{{ $rw->rw }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-1">
                                        RT
                                        <select name="rt_id" id="" class="form-select">
                                            <option value="">-- Pilih RT --</option>
                                            @foreach ($selectRt as $rt)
                                                <option value="{{ $rt->id }}">{{ $rt->rt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4 mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        @endhasrole
                        {{-- @dd(App\User::role('superadmin') == true) --}}
                        @if (Auth::user()->hasrole('rt') == true)
                            <a href={{ url('/penduduk/exportRt/' . Auth::user()->Rt[0]->id) }}
                                class="btn btn-danger rounded-pill mb-3 mr-1">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Pdf</span>
                            </a>
                        @elseif (Auth::user()->hasrole('rw') == true)
                            <a href={{ url('/penduduk/exportRw/' . Auth::user()->Rw[0]->id) }}
                                class="btn btn-danger rounded-pill mb-3 mr-1">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Pdf</span>
                            </a>
                        @elseif (Auth::user()->hasrole('superadmin') == true)
                            <a href={{ route('penduduk.exportAll', Auth::user()->id) }}
                                class="btn btn-danger rounded-pill mb-3 mr-1">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Pdf</span>
                            </a>
                        @endif
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Kelamin</th>
                                    <th>NIK</th>
                                    <th>No. KK</th>
                                    <th>Alamat</th>
                                    <th>RW / RT</th>
                                    <th>Agama</th>
                                    <th>Tempat & Tanggal Lahir</th>
                                    <th>Usia</th>
                                    <th>Status Keluarga</th>
                                    <th>Status Ekonomi</th>
                                    <th>Pekerjaan</th>
                                    <th>Status Pernikahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->gender }}</td>
                                        <td>{{ $d->nik }}</td>
                                        <td>{{ $d->kk->no_kk }}</td>
                                        <td>{{ $d->alamat }}</td>
                                        <td>{{ $d->rw->rw }} / {{ $d->rt->rt }}</td>
                                        <td>{{ $d->agama }}</td>
                                        <td>{{ $d->tmp_lahir }},
                                            {{ Carbon\Carbon::parse($d->tgl_lahir)->format('d-m-Y') }}</td>
                                        <td>{{ $d->usia }}</td>
                                        <td>{{ $d->status_keluarga }}</td>
                                        <td>{{ $d->kk->status_ekonomi }}</td>
                                        <td>{{ $d->pekerjaan }}</td>
                                        <td>{{ $d->status_pernikahan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        {{-- @include('rt/edit') --}}
        </section>
    </div>
@endsection
