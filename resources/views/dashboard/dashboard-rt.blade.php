<title>SIPANDU - Dashboard</title>

@extends('layouts.master')


@section('master')
    <section>
               @if(Auth::check() && Auth::user()->is_default_password)
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-4" role="alert" style="border-radius: 12px; border-left: 5px solid #dc3545;">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-3">
                        <i class="fas fa-shield-alt me-3 fs-3" style="color: white;"></i>
                        <div>
                            <h5 class="alert-heading mb-1" style="color: white; font-weight: 600;">PERINGATAN KEAMANAN AKUN!</h5>
                            <p class="mb-2" style="color:white;">Anda masih menggunakan password default. Segera ubah password untuk melindungi akun Anda.</p>
                        </div>
                    </div>
                    <div class="ms-md-auto d-flex gap-2 align-self-stretch align-items-center">
                        <a href="#" class="btn btn-danger d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#edit"
                           style="font-weight: 500; padding: 8px 16px; white-space: nowrap;">
                            <i class="fas fa-key me-2"></i> Ubah Password Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif
        <div class="container-fluid">
            <div class="page-heading d-flex justify-content-between align-items-center">
                <h3>Dashboard</h3>
            </div>

            @hasrole('rw|rt|warga')
            <div class="page-content mt-4">

                <h1>Selamat Datang, {{ Auth::check() ? Auth::user()->name : 'User' }}!</h1>
                <h3>Ada Perlu apa hari ini ?</h3>
                @hasrole('rt')
                <p>
                    Cek data warga di Lingkungan RT
                    {{ \App\DataRt::where('user_id', Auth::id())->value('rt') ?? '' }} /
                    RW
                    {{ \App\DataRw::where('id', \App\DataRt::where('user_id', Auth::id())->value('rw_id'))->value('rw') ?? '' }},
                    Kelurahan Kampung Bulang
                </p>

                @php
                    $rt = \App\DataRt::where('user_id', Auth::id())->first();
                @endphp

                @if ($rt)
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-3 mb-3">
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
                                                    {{ \App\DataKk::where('verifikasi', 'diterima')->where('rt_id', $rt->id)->count() }}
                                                </h6>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3 mb-3">
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

                <div class="row">
                    <div class="page-heading d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-1">Statistik</h4>
                    </div>

                    <!-- Grafik Pertambahan Warga -->
                    <div class="col-12 col-lg-9 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Pertambahan Warga RT
                                    {{ \App\DataRt::where('user_id', Auth::id())->value('rt') ?? '' }} / RW
                                    {{ \App\DataRw::where('id', \App\DataRt::where('user_id', Auth::id())->value('rw_id'))->value('rw') ?? '' }}
                                    Setiap Bulan
                                </h4>
                                <form method="GET" action="{{ route('dashboard') }}" class="w-100">
                                    <div class="d-flex justify-content-end mb-2">
                                        <div style="max-width: 200px; width: 100%;">
                                            <select name="tahun" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                @foreach($list_tahun as $tahunItem)
                                                    <option value="{{ $tahunItem }}" {{ request()->get('tahun', $tahun_terpilih) == $tahunItem ? 'selected' : '' }}>
                                                        {{ $tahunItem }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body chart-container" style="height: 300px;">
                                <canvas id="warga"></canvas>
                            </div>
                            <div class="p-3">
                                <div class="accordion" id="accordionKeterangan">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingKeterangan">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseKeterangan"
                                                aria-expanded="false" aria-controls="collapseKeterangan">
                                                Keterangan Pertambahan Warga Bulanan
                                            </button>
                                        </h2>
                                        <div id="collapseKeterangan" class="accordion-collapse collapse"
                                            aria-labelledby="headingKeterangan" data-bs-parent="#accordionKeterangan">
                                            <div class="accordion-body">
                                                <ul class="mb-0">
                                                    @php
                                                        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                    @endphp
                                                    @foreach($bulan as $index => $namaBulan)
                                                        <li>{{ $namaBulan }} : {{ $data_month[$index] ?? 0 }} orang</li>
                                                    @endforeach
                                                </ul>
                                                <hr>
                                                <strong>Total Pertambahan Warga Tahun {{ $tahun_terpilih }}:
                                                    {{ $total_pertambahan }} orang</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Grafik Kanan -->
                    <div class="col-12 col-lg-3">
                        <div class="accordion" id="grafikAccordion">
                             <!-- Pendidikan Terakhir -->
                                <div class="accordion-item shadow mb-2">
                                    <h2 class="accordion-header" id="headingPendidikan">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapsePendidikan" aria-expanded="false"
                                            aria-controls="collapsePendidikan">
                                            Pendidikan Terakhir
                                        </button>
                                    </h2>
                                    <div id="collapsePendidikan" class="accordion-collapse collapse"
                                        aria-labelledby="headingPendidikan" data-bs-parent="#grafikAccordion">
                                        <div class="accordion-body chart-container" style="height: 300px;">
                                            <canvas id="pendidikan"></canvas>
                                        </div>
                                        <div class="p-3">
                                            <strong>Keterangan:</strong>
                                            <ul class="mb-0">
                                                @php
                                                    $pendidikanLabels = [
                                                        'tk' => 'TK/PAUD',
                                                        'sd' => 'SD',
                                                        'smp' => 'SMP',
                                                        'sma' => 'SMA',
                                                        's1' => 'S1',
                                                        's2' => 'S2',
                                                        's3' => 'S3',
                                                        'none' => 'Tidak Sekolah',
                                                    ];

                                                    // Urutkan sesuai urutan yang diinginkan
                                                    $sortedOrder = ['tk', 'sd', 'smp', 'sma', 's1', 's2', 's3', 'none'];
                                                @endphp

                                                @foreach ($sortedOrder as $key)
                                                    @if (isset($data_pendidikan[$key]))
                                                        <li>{{ $pendidikanLabels[$key] }} = {{ $data_pendidikan[$key] }} orang
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Tampilkan juga data lain yang tidak terdaftar --}}
                                                @foreach ($data_pendidikan as $key => $jumlah)
                                                    @if (!array_key_exists($key, $pendidikanLabels))
                                                        <li>{{ $key }} = {{ $jumlah }} orang</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            <!-- Agama -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingAgama">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAgama" aria-expanded="false" aria-controls="collapseAgama">
                                        Agama
                                    </button>
                                </h2>
                                <div id="collapseAgama" class="accordion-collapse collapse" aria-labelledby="headingAgama"
                                    data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container" style="height: 300px">
                                        <canvas id="agama"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            @foreach($data_agama as $agama => $jumlah)
                                                <li>{{ $agama }} = {{ $jumlah }} orang</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Usia -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingUsia">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseUsia" aria-expanded="false" aria-controls="collapseUsia">
                                        Usia
                                    </button>
                                </h2>
                                <div id="collapseUsia" class="accordion-collapse collapse" aria-labelledby="headingUsia"
                                    data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container" style="height: 300px;">
                                        <canvas id="usia"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            <li>Newborn (<1)={{ $usia_counts['newborn'] ?? 0 }} orang</li>
                                            <li>Batita (<3)={{ $usia_counts['batita'] ?? 0 }} orang</li>
                                            <li>Balita (<5)={{ $usia_counts['balita'] ?? 0 }} orang</li>
                                            <li>Anak-Anak (6-15) = {{ $usia_counts['anak_anak'] ?? 0 }} orang</li>
                                            <li>Remaja (17-20) = {{ $usia_counts['remaja'] ?? 0 }} orang</li>
                                            <li>Dewasa (21+) = {{ $usia_counts['dewasa'] ?? 0 }} orang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingGender">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseGender" aria-expanded="false"
                                        aria-controls="collapseGender">
                                        Jenis Kelamin
                                    </button>
                                </h2>
                                <div id="collapseGender" class="accordion-collapse collapse" aria-labelledby="headingGender"
                                    data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container">
                                        <canvas id="gender"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            <li>Laki-laki = {{ $gender_laki }} orang</li>
                                            <li>Perempuan = {{ $gender_cewe }} orang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Pekerjaan -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingPekerjaan">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePekerjaan" aria-expanded="false"
                                        aria-controls="collapsePekerjaan">
                                        Pekerjaan
                                    </button>
                                </h2>
                                <div id="collapsePekerjaan" class="accordion-collapse collapse"
                                    aria-labelledby="headingPekerjaan" data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container" style="height: 300px;">
                                        <canvas id="pekerjaan"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            @foreach($data_pekerjaan as $nama => $jumlah)
                                                <li>{{ $nama }} = {{ $jumlah }} orang</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Ekonomi -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingGaji">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseGaji" aria-expanded="false" aria-controls="collapseGaji">
                                        Status Ekonomi
                                    </button>
                                </h2>
                                <div id="collapseGaji" class="accordion-collapse collapse" aria-labelledby="headingGaji"
                                    data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container">
                                        <canvas id="gaji"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            @php
                                                // Klasifikasi BPS
                                                $statusEkonomiBPS = [
                                                    'Miskin' => 'text-danger',
                                                    'Rentan Miskin' => 'text-warning',
                                                    'Menuju Kelas Menengah' => 'text-secondary',
                                                    'Kelas Menengah' => 'text-primary',
                                                    'Kelas Atas' => 'text-success'
                                                ];

                                                // Hitung status ekonomi berdasarkan klasifikasi BPS
                                                $garisKemiskinan = 595000;
                                                $statusGroupsBPS = collect($dataPenduduk)
                                                    ->groupBy('kk_id')
                                                    ->map(function ($anggota) use ($garisKemiskinan) {
                                                        $rata2 = $anggota->pluck('gaji')->avg();
                                                        $rasio = $garisKemiskinan > 0 ? $rata2 / $garisKemiskinan : 0;

                                                        if ($rasio < 1) {
                                                            return 'Miskin';
                                                        } elseif ($rasio < 1.5) {
                                                            return 'Rentan Miskin';
                                                        } elseif ($rasio < 3.5) {
                                                            return 'Menuju Kelas Menengah';
                                                        } elseif ($rasio < 17) {
                                                            return 'Kelas Menengah';
                                                        } else {
                                                            return 'Kelas Atas';
                                                        }
                                                    })
                                                    ->countBy();

                                                // Hitung total KK dan persentase
                                                $totalKK = array_sum($statusGroupsBPS->toArray());
                                            @endphp
                                            @foreach($statusEkonomiBPS as $status => $class)
                                                @if(isset($statusGroupsBPS[$status]))
                                                    @php
                                                        $jumlahKK = $statusGroupsBPS[$status];
                                                        $persentase = $totalKK > 0 ? ($jumlahKK / $totalKK) * 100 : 0;
                                                    @endphp
                                                    <li class="{{ $class }}">
                                                        {{ $status }} = {{ $jumlahKK }} KK ({{ number_format($persentase, 1) }}%)
                                                    </li>
                                                @endif
                                            @endforeach

                                            @if($totalKK > 0)
                                                <li class="fw-bold mt-2 border-top pt-2">
                                                    Total KK: {{ $totalKK }} KK
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Pernikahan -->
                            <div class="accordion-item shadow mb-2">
                                <h2 class="accordion-header" id="headingPernikahan">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePernikahan" aria-expanded="false"
                                        aria-controls="collapsePernikahan">
                                        Status Pernikahan
                                    </button>
                                </h2>
                                <div id="collapsePernikahan" class="accordion-collapse collapse"
                                    aria-labelledby="headingPernikahan" data-bs-parent="#grafikAccordion">
                                    <div class="accordion-body chart-container">
                                        <canvas id="pernikahan"></canvas>
                                    </div>
                                    <div class="p-3">
                                        <strong>Keterangan:</strong>
                                        <ul class="mb-0">
                                            @foreach($data_pernikahan as $status => $jumlah)
                                                <li>{{ $status }} = {{ $jumlah }} orang</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endhasrole
            </div>
            @endhasrole
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $chartLabels = [];
        $chartData = [];
        $chartColors = [
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(255, 99, 132, 0.6)',
            'rgba(201, 203, 207, 0.6)',
            'rgba(100, 181, 246, 0.6)',
        ];

        $colorIndex = 0;

        // Urutkan data sesuai sortedOrder
        foreach ($sortedOrder as $key) {
            if (isset($data_pendidikan[$key])) {
                $chartLabels[] = $pendidikanLabels[$key];
                $chartData[] = $data_pendidikan[$key];
                $colorIndex++;
            }
        }

        // Tambahkan data lain yang tidak terdaftar
        foreach ($data_pendidikan as $key => $jumlah) {
            if (!array_key_exists($key, $pendidikanLabels) && !in_array($key, $sortedOrder)) {
                $chartLabels[] = $key;
                $chartData[] = $jumlah;
            }
        }
    @endphp


    <script>
 // CHART PENDIDIKAN
        const chartPendidikan = new Chart(document.getElementById('pendidikan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Lulusan',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(201, 203, 207, 0.6)',
                        'rgba(100, 181, 246, 0.6)'
                    ],
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });


        // CHART AGAMA
        const chartAgama = new Chart(document.getElementById('agama'), {
            type: 'bar',
            data: {
                // label sesuai yang kamu inginkan
                labels: ['Islam', 'Katolik', 'Protestan', 'Konghucu', 'Buddha', 'Hindu'],
                datasets: [{
                    label: 'Jumlah Agama',
                    // pastikan urutan sesuai label
                    data: [
                                        {{ $data_agama['Islam'] ?? 0 }},
                                        {{ $data_agama['Katolik'] ?? 0 }},
                                        {{ $data_agama['Protestan'] ?? 0 }},
                                        {{ $data_agama['Konghucu'] ?? 0 }},
                                        {{ $data_agama['Buddha'] ?? 0 }},
                        {{ $data_agama['Hindu'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ff6384',
                        '#ff9f40',
                        '#ffcd56',
                        '#4bc0c0',
                        '#36a2eb',
                        '#9966ff'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Auto Resize
        window.addEventListener('resize', () => {
            chartAgama.resize();
        });

        // CHART GENDER
        const chartGender = new Chart(document.getElementById('gender'), {
            type: 'doughnut',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    label: 'Jenis Kelamin',
                    data: [{{ $gender_laki }}, {{ $gender_cewe }}],
                    backgroundColor: ['#57caeb', '#ff7976'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // CHART USIA
        const chartUsia = new Chart(document.getElementById('usia'), {
            type: 'bar',
            data: {
                labels: ['Newborn(<1)', 'Batita(<3)', 'Balita(<5)', 'Anak-anak(6-15)', 'Remaja(17-20)', 'Dewasa(21+)'],
                datasets: [{
                    label: 'Kategori Usia',
                    data: [
                                                {{ $usia_counts['newborn'] ?? 0 }},
                                                {{ $usia_counts['batita'] ?? 0 }},
                                                {{ $usia_counts['balita'] ?? 0 }},
                                                {{ $usia_counts['anak_anak'] ?? 0 }},
                                                {{ $usia_counts['remaja'] ?? 0 }},
                        {{ $usia_counts['dewasa'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ffce56', '#36a2eb', '#ff6384',
                        '#9966ff', '#4bc0c0', '#ff9f40'
                    ],
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true }
                }
            }
        });

        // CHART WARGA PER BULAN
        const chartWarga = new Chart(document.getElementById('warga'), {
            type: 'line',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Warga',
                    data: [
                        @foreach ($data_month as $data)
                            {{ $data }},
                        @endforeach
                                                                                                                                            ],
                    fill: true,
                    borderColor: '#56b6f7',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });


        // CHART PEKERJAAN
        const chartPekerjaan = new Chart(document.getElementById('pekerjaan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($data_pekerjaan)) !!},
                datasets: [{
                    label: 'Jumlah Pekerjaan',
                    data: {!! json_encode(array_values($data_pekerjaan)) !!},
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(201, 203, 207, 0.6)',
                        'rgba(100, 181, 246, 0.6)',
                        'rgba(0, 200, 83, 0.6)',
                        'rgba(255, 87, 34, 0.6)'
                    ],
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true }
                }
            }
        });

        // CHART STATUS EKONOMI
        const penduduk = {!! json_encode($dataPenduduk) !!};

        // 1. Group berdasarkan KK
        const kkMap = {};
        penduduk.forEach(p => {
            const kk = p.kk_id;
            if (!kkMap[kk]) {
                kkMap[kk] = [];
            }
            kkMap[kk].push(p.gaji);
        });

        // 2. Hitung status ekonomi per KK berdasarkan klasifikasi BPS
        const gariskemiskinan = 595000;
        const statusGroupsBPS = {
            'Miskin': [],
            'Rentan Miskin': [],
            'Menuju Kelas Menengah': [],
            'Kelas Menengah': [],
            'Kelas Atas': []
        };

        Object.values(kkMap).forEach(gajiList => {
            const total = gajiList.reduce((a, b) => a + b, 0);
            const rata2 = total / gajiList.length;
            const rasio = gariskemiskinan > 0 ? rata2 / gariskemiskinan : 0;

            let status;
            if (rasio < 1) {
                status = 'Miskin';
            } else if (rasio < 1.5) {
                status = 'Rentan Miskin';
            } else if (rasio < 3.5) {
                status = 'Menuju Kelas Menengah';
            } else if (rasio < 17) {
                status = 'Kelas Menengah';
            } else {
                status = 'Kelas Atas';
            }

            statusGroupsBPS[status].push({
                rataRata: Math.round(rata2),
                rasio: rasio.toFixed(2)
            });
        });

        // 3. Ambil hanya kategori yang punya data
        const labelsBPS = [];
        const dataBPS = [];
        const backgroundColorBPS = [];
        const detailListMapBPS = {};

        const colorMapBPS = {
            'Miskin': '#dc3545',
            'Rentan Miskin': '#fd7e14',
            'Menuju Kelas Menengah': '#ffc107',
            'Kelas Menengah': '#0d6efd',
            'Kelas Atas': '#198754'
        };

        for (const [status, detailList] of Object.entries(statusGroupsBPS)) {
            if (detailList.length > 0) {
                labelsBPS.push(status);
                dataBPS.push(detailList.length);
                backgroundColorBPS.push(colorMapBPS[status]);
                detailListMapBPS[status] = detailList;
            }
        }

        // 4. Buat chart
        const chartEkonomi = new Chart(
            document.getElementById('gaji'),
            {
                type: 'doughnut',
                data: {
                    labels: labelsBPS,
                    datasets: [{
                        label: 'Status Ekonomi (BPS)',
                        data: dataBPS,
                        backgroundColor: backgroundColorBPS,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const count = context.parsed || 0;
                                    const detailList = detailListMapBPS[label] || [];
                                    const listString = detailList.map(d =>
                                        `- Rp${d.rataRata.toLocaleString()} (${d.rasio})`
                                    ).join('\n');
                                    return `${count} KK\n${listString}`;
                                }
                            }
                        }
                    }
                }
            }
        );

        // CHART STATUS PERNIKAHAN
        const chartPernikahan = new Chart(document.getElementById('pernikahan'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($data_pernikahan)) !!},
                datasets: [{
                    label: 'Status Pernikahan',
                    data: {!! json_encode(array_values($data_pernikahan)) !!},
                    backgroundColor: [
                        '#0dcaf0',
                        '#6f42c1',
                        '#d63384',
                        '#ffc107',
                        '#198754'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Auto Resize
        window.addEventListener('resize', () => {
            chartGender.resize();
            chartUsia.resize();
            chartWarga.resize();
            chartPekerjaan.resize();
            chartEkonomi.resize();
            chartPernikahan.resize();
        });
    </script>
@endsection
