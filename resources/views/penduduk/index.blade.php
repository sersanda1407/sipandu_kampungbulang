@extends('layouts.master')

@section('master')




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
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-2">
                                    <label for="rw_id">RW</label>
                                    <select name="rw_id" id="rw_id" class="form-select">
                                        <option value="">-- Pilih RW --</option>
                                        @foreach ($selectRw as $rw)
                                            <option value="{{ encrypt($rw->id) }}" {{ isset($rwId) && $rwId == $rw->id ? 'selected' : '' }}>
                                                {{ $rw->rw }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-2">
                                    <label for="rt_id">RT</label>
                                    <select name="rt_id" id="rt_id" class="form-select">
                                        <option value="">-- Pilih RT --</option>
                                        @foreach ($selectRt as $rt)
                                            <option value="{{ encrypt($rt->id) }}" {{ isset($rtId) && $rtId == $rt->id ? 'selected' : '' }}>
                                                {{ $rt->rt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary w-100 w-md-auto"
                                        id="filter-button">Filter</button>
                                    <a href="{{ url('/penduduk') }}" class="btn btn-warning w-100 w-md-auto">Reset</a>
                                </div>
                            </div>
                        </form>

                        <script>
                            const rwSelect = document.getElementById('rw_id');
                            const rtSelect = document.getElementById('rt_id');
                            const filterButton = document.getElementById('filter-button');

                            function toggleFilterButton() {
                                filterButton.disabled = !rwSelect.value;
                            }

                            toggleFilterButton();
                            rwSelect.addEventListener('change', toggleFilterButton);
                        </script>

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

                      @php
    $exportRoute = '';
@endphp

@if (Auth::user()->hasrole('rt'))
    <button type="button" class="btn btn-danger rounded-pill mb-3" data-bs-toggle="modal"
        data-bs-target="#exportModal">
        <i class="fas fa-file-pdf"></i>
        <span>Export PDF</span>
    </button>
    @php
        $exportRoute = route('penduduk.exportRt', encrypt(Auth::user()->Rt[0]->id));
    @endphp

@elseif (Auth::user()->hasrole('rw'))
    <button type="button" class="btn btn-danger rounded-pill mb-3" data-bs-toggle="modal"
        data-bs-target="#exportModal">
        <i class="fas fa-file-pdf"></i>
        <span>Export PDF</span>
    </button>
    @php
        $exportRoute = route('penduduk.exportRw', encrypt(Auth::user()->Rw[0]->id));
    @endphp

@elseif (Auth::user()->hasrole('superadmin'))
    <button type="button" class="btn btn-danger rounded-pill mb-3" data-bs-toggle="modal"
        data-bs-target="#exportModal">
        <i class="fas fa-file-pdf"></i>
        <span>Export PDF</span>
    </button>
    @php
        $params = [];
        if (request()->filled('rw_id')) {
            $params['rw_id'] = encrypt(request()->rw_id);
        }
        if (request()->filled('rt_id')) {
            $params['rt_id'] = encrypt(request()->rt_id);
        }

        $exportRoute = empty($params)
            ? route('penduduk.exportAll')
            : route('penduduk.exportFiltered', $params);
    @endphp
@endif

<!-- Modal Export PDF -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="exportForm" method="GET" action="{{ $exportRoute }}">
            <input type="hidden" name="rw_id" value="{{ request()->filled('rw_id') ? request()->rw_id : '' }}">
            <input type="hidden" name="rt_id" value="{{ request()->filled('rt_id') ? request()->rt_id : '' }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Pilih Keterangan Tambahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php
                            $opsi = [
                                'gender' => 'Jenis Kelamin',
                                'agama' => 'Agama',
                                'status_ekonomi' => 'Status Ekonomi',
                                'status_pernikahan' => 'Status Pernikahan',
                                'pekerjaan' => 'Pekerjaan',
                                'usia' => 'Usia'
                            ];
                        @endphp
                        @foreach ($opsi as $key => $label)
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tampilkan[]"
                                        value="{{ $key }}" id="check_{{ $key }}">
                                    <label class="form-check-label" for="check_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnExport">Export PDF</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('exportForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const url = new URL(form.action);

        const formData = new FormData(form);
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                url.searchParams.append(key, value);
            } else {
                url.searchParams.set(key, value);
            }
        });

        window.open(url.toString(), '_blank');

        setTimeout(() => {
            location.reload();
        }, 1000);
    });
</script>

                        {{-- Table Responsive --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="table1">
                                <thead class="table-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>No. KK</th>
                                        <th>NIK</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>RT/RW</th>
                                        <th>Agama</th>
                                        <th>Usia</th>
                                        <th>Tempat & Tanggal Lahir</th>
                                        <th>Pekerjaan</th>
                                        <th>Pendapatan</th>
                                        <th>Status Ekonomi (rata-rata)</th>
                                        <th>Status Keluarga</th>
                                        <th>Status Pernikahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->nama }}</td>
                                            <td>{{ $d->kk->no_kk }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->gender }}</td>
                                            <td>{{ $d->alamat }}</td>
                                            <td>{{ $d->rt->rt }}/{{ $d->rw->rw }}</td>
                                            <td>{{ $d->agama }}</td>
                                            <td>{{ $d->usia }}</td>
                                            <td>{{ $d->tmp_lahir }}, {{ \Carbon\Carbon::parse($d->tgl_lahir)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $d->pekerjaan }}</td>
                                            <td class="d-none d-lg-table-cell">Rp.{{ number_format($d->gaji, 0, '.', '.') }},-
                                            </td>
                                            <td>
                                                @php
                                                    $pendudukKK = \App\DataPenduduk::where('kk_id', $d->kk_id)->get();
                                                    $totalGaji = $pendudukKK->sum('gaji');
                                                    $jumlahOrang = $pendudukKK->count();
                                                    $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

                                                    if ($rataRata < 500000) {
                                                        $statusEkonomi = 'Sangat Tidak Mampu';
                                                    } elseif ($rataRata <= 1500000) {
                                                        $statusEkonomi = 'Tidak Mampu';
                                                    } elseif ($rataRata <= 3000000) {
                                                        $statusEkonomi = 'Menengah ke Bawah';
                                                    } elseif ($rataRata <= 5000000) {
                                                        $statusEkonomi = 'Menengah';
                                                    } elseif ($rataRata <= 10000000) {
                                                        $statusEkonomi = 'Menengah ke Atas';
                                                    } else {
                                                        $statusEkonomi = 'Mampu';
                                                    }
                                                @endphp
                                                {{ $statusEkonomi }}
                                                <br>
                                                <small class="text-muted">Rp.{{ number_format($rataRata, 0, ',', '.') }}</small>
                                            </td>


                                            <td>{{ $d->status_keluarga }}</td>
                                            <td>{{ $d->status_pernikahan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection