<title>SIPANDU - Dashboard</title>

@extends('layouts.master')


@section('master')
    <section>
        <div class="container-fluid">
            <div class="page-heading d-flex justify-content-between align-items-center">
                <h3>Dashboard</h3>
            </div>


            @hasrole('rw|rt|warga')
            <div class="page-content mt-4">

                <h1>Selamat Datang, {{ Auth::check() ? Auth::user()->name : 'User' }}!</h1>
                <h3>Ada Perlu apa hari ini ?</h3>


                @hasrole('rw')
                <p>Cek data warga di Lingkungan RW {{ \App\DataRw::where('user_id', Auth::id())->value('rw') ?? '' }},
                    Kelurahan Kampung Bulang tahun {{ $currentYear }}</p>
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-3 mb-3">
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
                                            <h6 class="font-extrabold mb-0">{{ \App\DataRt::where(
        'rw_id',
        \App\DataRw::where('user_id', Auth::id())->value('id')
    )->count() }}
                                            </h6>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>


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
                                                {{
        \App\DataKk::where('verifikasi', 'diterima')
            ->where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))
            ->count()
                                        }}
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
                                                {{ \App\DataPenduduk::where('rw_id', \App\DataRw::where(
        'user_id',
        Auth::id()
    )->value('id'))->count() }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="page-heading d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-1">Statistik</h4>
                    </div>

                    <!-- Grafik Pertambahan Warga -->
                    <div class="col-12 col-lg-9 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Pertambahan Warga RW
                                    {{ \App\DataRw::where('user_id', Auth::id())->value('rw') ?? '' }}
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
                                                Keterangan Petambahan Warga Bulanan
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
                                                $statusEkonomi = [
                                                    'Sangat Tidak Mampu' => 'text-danger',
                                                    'Tidak Mampu' => 'text-warning',
                                                    'Menengah ke Bawah' => 'text-secondary',
                                                    'Menengah' => 'text-primary',
                                                    'Menengah ke Atas' => 'text-success',
                                                    'Mampu' => 'text-success'
                                                ];
                                                $statusGroups = collect($dataPenduduk)->groupBy('kk_id')->map(function ($anggota) {
                                                    $rata2 = $anggota->pluck('gaji')->avg();
                                                    if ($rata2 < 500000)
                                                        return 'Sangat Tidak Mampu';
                                                    if ($rata2 <= 1500000)
                                                        return 'Tidak Mampu';
                                                    if ($rata2 <= 3000000)
                                                        return 'Menengah ke Bawah';
                                                    if ($rata2 <= 5000000)
                                                        return 'Menengah';
                                                    if ($rata2 <= 10000000)
                                                        return 'Menengah ke Atas';
                                                    return 'Mampu';
                                                })->countBy();
                                            @endphp
                                            @foreach($statusEkonomi as $status => $class)
                                                @if(isset($statusGroups[$status]))
                                                    <li class="{{ $class }}">{{ $status }} = {{ $statusGroups[$status] }} KK
                                                    </li>
                                                @endif
                                            @endforeach
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

    <script>
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
        const penduduk = {!! json_encode($dataPenduduk) !!}; // Data dari controller

        // 1. Group berdasarkan KK
        const kkMap = {};
        penduduk.forEach(p => {
            const kk = p.kk_id;
            if (!kkMap[kk]) {
                kkMap[kk] = [];
            }
            kkMap[kk].push(p.gaji);
        });

        // 2. Hitung status ekonomi per KK
        const statusGroups = {
            'Sangat Tidak Mampu': [],
            'Tidak Mampu': [],
            'Menengah ke Bawah': [],
            'Menengah': [],
            'Menengah ke Atas': [],
            'Mampu': []
        };

        Object.values(kkMap).forEach(gajiList => {
            const total = gajiList.reduce((a, b) => a + b, 0);
            const rata2 = total / gajiList.length;

            let status;
            if (rata2 < 500000) status = 'Sangat Tidak Mampu';
            else if (rata2 <= 1500000) status = 'Tidak Mampu';
            else if (rata2 <= 3000000) status = 'Menengah ke Bawah';
            else if (rata2 <= 5000000) status = 'Menengah';
            else if (rata2 <= 10000000) status = 'Menengah ke Atas';
            else status = 'Mampu';

            statusGroups[status].push(Math.round(rata2)); // simpan per KK
        });

        // 3. Ambil hanya kategori yang punya data
        const labels = [];
        const data = [];
        const backgroundColor = [];
        const rata2ListMap = {}; // kategori => [list rata2]

        const colorMap = {
            'Sangat Tidak Mampu': '#dc3545',
            'Tidak Mampu': '#fd7e14',
            'Menengah ke Bawah': '#ffc107',
            'Menengah': '#0d6efd',
            'Menengah ke Atas': '#20c997',
            'Mampu': '#198754'
        };

        for (const [status, rata2List] of Object.entries(statusGroups)) {
            if (rata2List.length > 0) {
                labels.push(status);
                data.push(rata2List.length);
                backgroundColor.push(colorMap[status]);
                // Simpan daftar unik dan terurut
                rata2ListMap[status] = [...new Set(rata2List)].sort((a, b) => a - b);
            }
        }

        // 4. Buat chart
        const chartEkonomi = new Chart(
            document.getElementById('gaji'),
            {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Status Ekonomi',
                        data: data,
                        backgroundColor: backgroundColor,
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
                                    const rataList = rata2ListMap[label] || [];
                                    const listString = rataList.map(r => `- Rp${r.toLocaleString()}`).join('\n');
                                    return `${label}: ${count} KK\n${listString}`;
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