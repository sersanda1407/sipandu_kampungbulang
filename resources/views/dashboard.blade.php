<title>SIPANDU - Dashboard</title>

@extends('layouts.master')


@section('master')
    <section>
        <div class="container-fluid">
            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
            @hasrole('superadmin')
            <div class="page-content mt-4">
                {{-- <section class="row"> --}}
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rw') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data RW</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRw::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rt') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data RT</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRt::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/kk') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data KK</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataKk::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/penduduk') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Warga</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataPenduduk::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h4>Pertambahan warga setiap bulan</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="warga"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div>
                            <div class="col-12 col-lg-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h4>Usia</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="usia"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col-12 col-lg-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h4>Gender</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="gender"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endhasrole

            @hasrole('rw|rt|warga')
            <div class="page-content mt-4">

            <h1>Selamat Datang, {{ Auth::check() ? Auth::user()->name : 'User' }}!</h1>
            <h3>Ada Perlu apa hari ini ?</h3>

            @hasrole('warga')
            @php
                $warga = \App\DataKK::where('user_id', Auth::id())->first();
                $rt = $warga ? \App\DataRt::find($warga->rt_id) : null;
                $rw = $rt ? \App\DataRw::find($rt->rw_id) : null;
                $user = Auth::user();
            @endphp

            @if($warga && $rt && $rw)
            <div class="card shadow mt-4" style="border-radius: 12px;">
                <div class="card-body">
                    <h5 class="mb-4" style="font-weight: 600; color: #333;">Kontak Penting</h5>

                    <div class="mt-3">
                        <h6 class="text-muted mb-1">Ketua RW {{ $rw->rw ?? '-' }} Kampung Bulang</h6>
                        <p class="mb-0">Nama: <strong>{{ $rw->nama ?? '-' }}</strong></p>
                        <p>No HP: 
                            @if($rw && $rw->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $rw->no_hp) }}" target="_blank">
                                    <strong>{{ $rw->no_hp }}</strong>
                                </a>
                            @else
                                <strong>-</strong>
                            @endif
                        </p>

                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Ketua RT {{ $rt->rt ?? '-' }} Kampung Bulang</h6>
                        <p class="mb-0">Nama: <strong>{{ $rt->nama ?? '-' }}</strong></p>
                        <p>No HP: 
                        @if($rt && $rt->no_hp)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $rt->no_hp) }}" target="_blank">
                                <strong>{{ $rt->no_hp }}</strong>
                            </a>
                        @else
                            <strong>-</strong>
                        @endif
                        <div class="mt-3">
                        <label for="keperluan" class="form-label">Ada yang mau diurus?</label>
                        <select class="form-select" id="keperluan">
                            <option value="" selected>Pilih keperluan...</option>
                            <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                            <option value="Surat Pengantar SKCK">Surat Pengantar SKCK</option>
                            <option value="Pendaftaran/Perubahan KK">Pendaftaran/Perubahan KK</option>
                            <option value="Pengurusan KTP Baru">Pengurusan KTP Baru</option>
                            <option value="Izin Keramaian">Izin Keramaian</option>
                            <option value="Pelaporan Warga Baru">Pelaporan Warga Baru</option>
                            <option value="Pelaporan Warga Meninggal">Pelaporan Warga Meninggal</option>
                        </select>
                    </div>

                    <!-- Modal Konfirmasi -->
                    <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body" id="modalBodyContent">
                            <!-- Isi akan diganti dinamis -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="btnKirimWA">Kirim</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    </p>

                    </div>
                    
                </div>
            </div>
            <script>
                const keperluanSelect = document.getElementById('keperluan');
                const btnKirimWA = document.getElementById('btnKirimWA');
                let selectedKeperluan = '';

                const namaRt = "{{ $rt->nama ?? '' }}";
                const rtId = "{{ $rt->rt ?? '' }}";

                keperluanSelect.addEventListener('change', function() {
                    if (this.value) {
                        selectedKeperluan = this.value;

                        modalBodyContent.innerHTML = `Anda ingin mengirim pesan ke Ketua RT ${rtId} ${namaRt} untuk mengurus <strong>${selectedKeperluan}</strong>?`;

                        var modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                        modal.show();
                    }
                });

                btnKirimWA.addEventListener('click', function() {
                    const now = new Date();
                    const hour = now.getHours();
                    let greeting = "Selamat pagi";

                    if (hour >= 12 && hour < 15) {
                        greeting = "Selamat siang";
                    } else if (hour >= 15) {
                        greeting = "Selamat sore";
                    }

                    const nama = "{{ Auth::user()->name ?? 'nama' }}";
                    const namaRt = "{{ $rt->nama ?? '' }}";
                    const rtId = "{{ $rt->rt ?? '' }}";
                    const rwId = "{{ $rw->rw ?? '' }}";
                    const noHpRt = "{{ preg_replace('/^0/', '62', $rt->no_hp) }}";
                    const pesan = `[PESAN DARI APLIKASI SIPANDU]  \n\n\nAssalamualaikum\n${greeting}\n\nPerkenalkan, saya:\nNama : *${nama}*\nAlamat : (alamat) RT ${rtId} / RW ${rwId}\nKeperluan : *Ingin mengurus ${selectedKeperluan}*\n\nTerima kasih banyak atas perhatian dan waktunya. Semoga sehat selalu dan dilancarkan segala aktivitasnya. 🙏🏻\n\n\n_*Pesan ini dikirim secara otomatis_`;

                    const url = `https://wa.me/${noHpRt}?text=${encodeURIComponent(pesan)}`;
                    window.open(url, '_blank');
                });
            </script>
            @endif

            @endhasrole

            @hasrole('rw')
            <p>Cek data warga di Lingkungan RW {{ \App\DataRw::where('user_id', Auth::id())->value('rw') ?? '' }}, Kelurahan Kampung Bulang</p>
            @endhasrole

            @hasrole('rt')
            <p>
                Cek data warga di Lingkungan RT 
                {{ \App\DataRt::where('user_id', Auth::id())->value('rt') ?? '' }} / 
                RW {{ \App\DataRw::where('id', \App\DataRt::where('user_id', Auth::id())->value('rw_id'))->value('rw') ?? '' }}, 
                Kelurahan Kampung Bulang
            </p>
            @endhasrole


            @hasrole('rw')
            <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rt') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold"> Data RT</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRt::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    
              
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/kk') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data KK</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataKk::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/penduduk') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Warga</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataPenduduk::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                @endhasrole

                @hasrole('rt')
@php
    $rt = \App\DataRt::where('user_id', Auth::id())->first();
@endphp

@if ($rt)
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <a href="{{ url('/kk') }}">
            <div class="card shadow">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="fas fa-address-card"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Data KK</h6>
                            <h6 class="font-extrabold mb-0">
                                {{ \App\DataKk::where('rt_id', $rt->id)->count() }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <a href="{{ url('/penduduk') }}">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Warga</h6>
                            <h6 class="font-extrabold mb-0">
                                {{ \App\DataPenduduk::where('rt_id', $rt->id)->count() }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endif
@endhasrole


     
            </div>
            @endhasrole
        </div>

    </section>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    {{-- CHART GENDER --}}
    <script>
        const data = {
            labels: [
                'Laki-Laki',
                'Perempuan'
            ],
            datasets: [{
                label: 'Gender',
                data: [{{ $gender_laki }}, {{ $gender_cewe }}],
                backgroundColor: [
                    'rgb(87, 202, 235)',
                    'rgb(255, 121, 118)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        };

        const chartGender = new Chart(
            document.getElementById('gender'),
            config
        );
    </script>
    {{-- CHART GENDER --}}

    {{-- CHART USIA --}}
    <script>
        const data_usia = {
            labels: [
                'Dewasa',
                'Anak-anak'
            ],
            datasets: [{
                label: 'Usia',
                data: [{{ $dewasa }}, {{ $anak_anak }}],
                backgroundColor: [
                    'rgb(87, 202, 235)',
                    'rgb(255, 121, 118)'
                ],
                hoverOffset: 4
            }]
        };

        const usia = {
            type: 'bar',
            data: data_usia,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        };

        const chartUsia = new Chart(
            document.getElementById('usia'),
            usia
        );
    </script>
    {{-- CHART USIA --}}

    {{-- CHART PENDUDUK --}}
    <script>
        const datawarga = {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                'November',
                'Desember'
            ],
            
            datasets: [{
                label: 'Warga',
                data: [
                    @foreach ($data_month as $data)
                        {{ $data }},
                    @endforeach
                ],
                fill: true,
                borderColor: 'rgb(86, 182, 247)',
                tension: 0.3
            }]
        };

        const warga = {
            type: 'line',
            data: datawarga,
            options: {
                responsive: true,
            }
        };

        const chartWarga = new Chart(
            document.getElementById('warga'),
            warga
        );
    </script>
    {{-- CHART PENDUDUK --}}
@endsection
