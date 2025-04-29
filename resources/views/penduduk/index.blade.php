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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
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
                                    <select name="rw_id" id="rw_id" class="form-select">
                                        <option value="">-- Pilih RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ $rw->id }}" {{ request('rw_id') == $rw->id ? 'selected' : '' }}>
                                                {{ $rw->rw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-1">
                                    RT
                                    <select name="rt_id" id="rt_id" class="form-select">
                                        <option value="">-- Pilih RT --</option>
                                        @foreach ($selectRt as $rt)
                                            <option value="{{ $rt->id }}">{{ $rt->rt }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-4 mt-4 d-flex align-items-center gap-2">
                                    <button type="submit" class="btn btn-primary" id="filter-button">Filter</button>

                                    <a href="{{ url('/penduduk') }}" class="btn btn-warning ml-2">Reset</a>
                                </div>

                            </div>
                        </form>

                        <script>
                            const rwSelect = document.getElementById('rw_id');
                            const rtSelect = document.getElementById('rt_id');
                            const filterButton = document.getElementById('filter-button');

                            function toggleFilterButton() {
                                // Tombol hanya aktif jika RW dipilih (boleh RT kosong atau diisi)
                                filterButton.disabled = !rwSelect.value;
                            }

                            // Jalankan saat pertama load
                            toggleFilterButton();

                            // Jalankan setiap kali RW berubah
                            rwSelect.addEventListener('change', toggleFilterButton);
                        </script>



                        {{-- VALIDASI JS --}}
                        <script>
                            document.getElementById('filter-tanggal').addEventListener('submit', function (e) {
                                const rw = document.getElementById('rw_id').value;
                                const rt = document.getElementById('rt_id').value;

                                if (!rw && rt) {
                                    e.preventDefault();
                                    alert('Silakan pilih RW terlebih dahulu jika ingin memfilter berdasarkan RT.');
                                }
                            });
                        </script>

                        @endhasrole
                        {{-- @dd(App\User::role('superadmin') == true) --}}
                        @if (Auth::user()->hasrole('rt'))
                            <a href="{{ url('/penduduk/exportRt/' . encrypt(Auth::user()->Rt[0]->id)) }}" target="_blank"
                                class="btn btn-danger rounded-pill mb-3 mr-1">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Pdf</span>
                            </a>
                        @elseif (Auth::user()->hasrole('rw'))
                            <a href="{{ url('/penduduk/exportRw/' . encrypt(Auth::user()->Rw[0]->id)) }}" target="_blank"
                                class="btn btn-danger rounded-pill mb-3 mr-1">
                                <i class="fas fa-file-pdf"></i>
                                <span>Export Pdf</span>
                            </a>
                        @elseif (Auth::user()->hasrole('superadmin'))
                                            @php
                                                $params = [];
                                                if (request('rt_id'))
                                                    $params['rt_id'] = encrypt(request('rt_id'));
                                                if (request('rw_id'))
                                                    $params['rw_id'] = encrypt(request('rw_id'));
                                            @endphp

                                            @if (!empty($params))
                                                <a href="{{ route('penduduk.exportFiltered', $params) }}" target="_blank"
                                                    class="btn btn-danger rounded-pill mb-3 mr-1">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span>Export Pdf</span>
                                                </a>
                                            @else
                                                <a href="{{ route('penduduk.exportAll') }}" target="_blank"
                                                    class="btn btn-danger rounded-pill mb-3 mr-1">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span>Export Pdf</span>
                                                </a>
                                            @endif
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
                                    <th>RT/RW</th>
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
                                        <td>{{ $d->rt->rt }}/{{ $d->rw->rw }}</td>
                                        <td>{{ $d->agama }}</td>
                                        <td>{{ $d->tmp_lahir }}, {{ Carbon\Carbon::parse($d->tgl_lahir)->format('d-m-Y') }}</td>
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