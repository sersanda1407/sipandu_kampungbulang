<title>SIPANDU - Dashboard</title>

@extends('layouts.master')


@section('master')
    <section>
        <div class="container-fluid">
            <div class="page-heading d-flex justify-content-between align-items-center">
                <h3>Dashboard</h3>
                {{-- <div class="dropdown">
                    <a href="#" class="text-dark position-relative" id="notifDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown"
                        style="width: 300px;">
                        <li class="dropdown-header fw-bold px-3 py-2">Notifikasi</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <p class="text-center">Tidak ada notifikasi</p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary" href="#">Lihat semua</a></li>
                    </ul>
                </div> --}}
            </div>


            @hasrole('superadmin')
            <div class="page-content mt-4">
                {{-- <section class="row"> --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-3 mb-3">
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
                        <div class="col-12 col-sm-6 col-lg-3 mb-3">
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
                                                <h6 class="font-extrabold mb-0">{{ \App\DataKk::count() }}</h6>
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
                                                <h6 class="font-extrabold mb-0">{{ \App\DataPenduduk::count() }}</h6>
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
                                    <h4 class="mb-0">Pertambahan Warga Setiap Bulan</h4>
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

                                <!-- Usia -->
                                <div class="accordion-item shadow mb-2">
                                    <h2 class="accordion-header" id="headingUsia">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseUsia" aria-expanded="false"
                                            aria-controls="collapseUsia">
                                            Usia
                                        </button>
                                    </h2>
                                    <div id="collapseUsia" class="accordion-collapse collapse" aria-labelledby="headingUsia"
                                        data-bs-parent="#grafikAccordion">
                                        <div class="accordion-body chart-container" style="height: 300px;">
                                            <canvas id="usia"></canvas>
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
                                    <div id="collapseGender" class="accordion-collapse collapse"
                                        aria-labelledby="headingGender" data-bs-parent="#grafikAccordion">
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
                                            data-bs-target="#collapseGaji" aria-expanded="false"
                                            aria-controls="collapseGaji">
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

                            {{-- Ketua RT --}}
                            <div class="mb-4 row align-items-start">
                                <div class="col-md-9">
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
                                    </p>

                                    <div class="mt-3">
                                        <label for="keperluan" class="form-label">Ada yang mau diurus?</label>
                                        <select class="form-select" id="keperluan" onchange="toggleInput(this)">
                                            <option value="" selected>Pilih keperluan...</option>
                                            <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                                            <option value="Pendaftaran/Perubahan KTP">Pendaftaran/Perubahan KK</option>
                                            <option value="Pengurusan KTP Baru">Pengurusan KTP Baru</option>
                                            <option value="Izin Keramaian">Izin Keramaian</option>
                                            <option value="Pelaporan Warga Baru">Pelaporan Warga Baru</option>
                                            <option value="Pelaporan Warga Meninggal">Pelaporan Warga Meninggal</option>
                                            <option value="lainnya">Lainnya...</option>
                                        </select>
                                    </div>
                                    <div class="mt-3" id="keperluan-lainnya" style="display: none;">
                                        <label for="keperluan_custom" class="form-label">Tuliskan keperluan Anda:</label>
                                        <input type="text" class="form-control" id="keperluan_custom"
                                            placeholder="Masukkan keperluan lainnya">
                                    </div>

                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" onclick="bukaModal()">Lanjutkan</button>
                                    </div>

                                </div>
                                <div class="col-md-3 mt-3 mt-md-0 text-md-end text-center">
                                    @if($rt && $rt->image_rt)
                                        {{-- Cek apakah isinya berupa base64 atau URL langsung dari database --}}
                                        @if(Str::startsWith($rt->image_rt, ['data:image', 'http', 'https']))
                                            <img src="{{ $rt->image_rt }}" alt="Foto Ketua RT" class="img-fluid rounded shadow-sm"
                                                style="max-width: 150px;">
                                        @elseif(file_exists(public_path('storage/foto_rt/' . $rt->image_rt)))
                                            {{-- Fallback: ambil dari storage jika nama file tersimpan --}}
                                            <img src="{{ asset('storage/foto_rt/' . $rt->image_rt) }}" alt="Foto Ketua RT"
                                                class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                                        @else
                                            <p class="text-muted">Foto Ketua RT tidak ditemukan.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">Foto Ketua RT belum tersedia.</p>
                                    @endif
                                </div>

                            </div>

                            {{-- Modal Konfirmasi --}}
                            <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body" id="modalBodyContent"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-primary" id="btnKirimWA">Kirim</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- Ketua RW --}}
                            <div class="row align-items-start">
                                <div class="col-md-9">
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
                                <div class="col-md-3 mt-3 mt-md-0 text-md-end text-center">
                                    @if($rw && $rw->image_rw)
                                        @if(Str::startsWith($rw->image_rw, ['data:image', 'http', 'https']))
                                            <img src="{{ $rw->image_rw }}" alt="Foto Ketua RW" class="img-fluid rounded shadow-sm"
                                                style="max-width: 150px;">
                                        @elseif(file_exists(public_path('storage/foto_rw/' . $rw->image_rw)))
                                            <img src="{{ asset('storage/foto_rw/' . $rw->image_rw) }}" alt="Foto Ketua RW"
                                                class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                                        @else
                                            <p class="text-muted">Foto Ketua RW tidak ditemukan.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">Foto Ketua RW belum tersedia.</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <script>
                    const keperluanSelect = document.getElementById('keperluan');
                    const customInput = document.getElementById('keperluan_custom');
                    const modalBodyContent = document.getElementById('modalBodyContent');
                    const btnKirimWA = document.getElementById('btnKirimWA');
                    const keperluanLainnyaDiv = document.getElementById('keperluan-lainnya');

                    let selectedKeperluan = '';

                    const nama = "{{ Auth::user()->name ?? 'nama' }}";
                    const namaRt = "{{ $rt->nama ?? '' }}";
                    const alamatWarga = "{{ $warga->alamat ?? '' }}";
                    const rtId = "{{ $rt->rt ?? '' }}";
                    const rwId = "{{ $rw->rw ?? '' }}";
                    const noHpRt = "{{ preg_replace('/^0/', '62', $rt->no_hp) }}";

                    // Tampilkan input jika pilih 'lainnya'
                    keperluanSelect.addEventListener('change', function () {
                        if (this.value === 'lainnya') {
                            keperluanLainnyaDiv.style.display = 'block';
                        } else {
                            keperluanLainnyaDiv.style.display = 'none';
                        }
                    });

                    function bukaModal() {
                        const selectedValue = keperluanSelect.value;
                        const customValue = customInput.value.trim();

                        // Validasi input
                        if (!selectedValue) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Keperluan belum dipilih',
                                text: 'Silakan pilih keperluan terlebih dahulu.',
                            });
                            return;
                        }

                        if (selectedValue === 'lainnya') {
                            if (!customValue) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Keperluan belum diisi',
                                    text: 'Silakan tuliskan keperluan Anda terlebih dahulu.',
                                });
                                return;
                            }
                            selectedKeperluan = customValue;
                        } else {
                            selectedKeperluan = selectedValue;
                        }

                        // Tampilkan konfirmasi di modal
                        modalBodyContent.innerHTML = `Anda ingin mengirim pesan ke Ketua RT ${rtId} ${namaRt} untuk mengurus <strong>${selectedKeperluan}</strong>?`;

                        const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                        modal.show();
                    }

                    btnKirimWA.addEventListener('click', function () {
                        const now = new Date();
                        const hour = now.getHours();
                        let greeting = "Selamat pagi";

                        if (hour >= 12 && hour < 15) {
                            greeting = "Selamat siang";
                        } else if (hour >= 15) {
                            greeting = "Selamat sore";
                        }

                        const pesan = `[PESAN DARI APLIKASI SIPANDU]  \n\n\nAssalamualaikum\n${greeting}\n\nPerkenalkan, saya:\nNama : *${nama}*\nAlamat : ${alamatWarga}, RT ${rtId} / RW ${rwId}\nKeperluan : *Ingin mengurus ${selectedKeperluan}*\n\nTerima kasih banyak atas perhatian dan waktunya. Semoga sehat selalu dan dilancarkan segala aktivitasnya. 🙏🏻\n\n\n_*Pesan ini dikirim secara otomatis_`;

                        const url = `https://wa.me/${noHpRt}?text=${encodeURIComponent(pesan)}`;
                        window.open(url, '_blank');
                    });
                </script>

                @endhasrole

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
                                            <h6 class="font-extrabold mb-0">{{ \App\DataKk::where(
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
                                                    {{ \App\DataKk::where('rt_id', $rt->id)->count() }}
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