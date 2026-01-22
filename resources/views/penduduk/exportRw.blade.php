<?php use Carbon\Carbon; ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Warga RW {{ $rw->rw }} Kelurahan Kampung Bulang @if(isset($tahun))Tahun {{ $tahun }}@endif</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 10pt;
      margin: 20px;
    }

    .kop-surat {
      margin-top: -20px;
      margin-bottom: 10px;
    }

    h5.title {
      margin-top: 5px;
      margin-bottom: 25px;
      font-weight: bold;
      text-decoration: underline;
    }

    table th,
    table td {
      font-size: 9pt;
      vertical-align: middle !important;
    }

    .footer-section {
      page-break-inside: avoid;
      page-break-before: avoid;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-top: 0px;
    }

    .footer-left {
      flex: 1;
    }

    .footer-right {
      width: 200px;
      float: right;
      text-align: left;
    }
  </style>

  @php
 \Carbon\Carbon::setLocale('id');
  $waktu = \Carbon\Carbon::now('Asia/Jakarta');
  $zona = [
    'Asia/Jakarta' => 'WIB',
    'Asia/Makassar' => 'WITA',
    'Asia/Jayapura' => 'WIT'
  ];
  $zonaAktif = $zona[$waktu->timezoneName];

  $jumlah_laki = $penduduk->where('gender', 'Laki-laki')->count();
  $jumlah_perempuan = $penduduk->where('gender', 'Perempuan')->count();

  // Klasifikasi BPS
  $statusCounts = [
    'Miskin' => 0,
    'Rentan Miskin' => 0,
    'Menuju Kelas Menengah' => 0,
    'Kelas Menengah' => 0,
    'Kelas Atas' => 0,
  ];

  $garisKemiskinan = 595000; // Garis Kemiskinan BPS per kapita per bulan

  $uniqueKks = $penduduk->pluck('kk_id')->unique();

  foreach ($uniqueKks as $kk_id) {
    $anggotaKK = $penduduk->where('kk_id', $kk_id);
    $totalGaji = $anggotaKK->sum('gaji');
    $jumlahOrang = $anggotaKK->count();
    $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

    // Hitung rasio terhadap garis kemiskinan
    $rasio = $garisKemiskinan > 0 ? $rataRata / $garisKemiskinan : 0;

    // Klasifikasi BPS
    if ($rasio < 1) {
      $status = 'Miskin';
    } elseif ($rasio < 1.5) {
      $status = 'Rentan Miskin';
    } elseif ($rasio < 3.5) {
      $status = 'Menuju Kelas Menengah';
    } elseif ($rasio < 17) {
      $status = 'Kelas Menengah';
    } else {
      $status = 'Kelas Atas';
    }

    $statusCounts[$status]++;
  }
  @endphp
</head>

<body>
  <div style="text-align: center; margin-bottom: 10px;">
    <table style="width: 100%;">
      <tr>
        <td style="width: 15%; text-align: right;">
          <img src="{{ public_path('assets/images/Lambang_Kota_Tanjungpinang.webp') }}"
            style="width: 85px; height: auto;">
        </td>
        <td style="width: 70%; text-align: center;">
          <div style="font-size: 20px; font-weight: bold; line-height: 1.2;">
            PEMERINTAH KOTA TANJUNGPINANG<br>
            KECAMATAN TANJUNGPINANG TIMUR<br>
            <span style="font-size: 22px;">KELURAHAN KAMPUNG BULANG</span><br>
            <span style="font-size: 14px; font-weight: normal;">
              Jl. Sultan Sulaiman, Kampung Bulang Bawah, Kecamatan Tanjungpinang Timur<br>
              Kota Tanjungpinang, Provinsi Kepulauan Riau – 29122<br>
              Telp. +62811-7784-847 | email: <u>kmpbulang@yahoo.com</u>
            </span>
          </div>
        </td>
        <td style="width: 15%;"></td>
      </tr>
    </table>
    <hr style="border: 2px solid black; margin-top: 8px;">
  </div>

  <div class="text-center kop-surat">
    <div class="mb-4 text-right" style="margin-right: 20px;">
      <p>Tanjungpinang, {{ $waktu->translatedFormat('d F Y') }}</p>
    </div>
    <h5 class="title">
      DATA WARGA
      @if ($rw)
      RW {{ $rw->rw }}
    @endif
      KELURAHAN KAMPUNG BULANG @if(isset($tahun))
      TAHUN {{ $tahun }}
    @endif
    </h5>
  </div>

  <div class="mb-3">
    <p>
      Berikut ini adalah data lengkap warga yang berdomisili di wilayah Kelurahan Kampung Bulang khususnya di wilayah
      @if ($rw)
      RW {{ $rw->rw }}
    @endif @if(isset($tahun))
      Pada Tahun {{ $tahun }}
    @endif:
    </p>
  </div>

  <table class="table table-bordered table-sm">
    <thead class="thead-light text-center">
      <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>No KK</th>
        <th>NIK</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>RT/RW</th>
        <th>Usia</th>
        <th>Tempat & Tanggal Lahir</th>
        <th>Agama</th>
        <th>Status Pernikahan</th>
        <th>Pekerjaan</th>
        <th>Status Ekonomi<br>(rata-rata)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($penduduk as $pd)
      <tr>
      <td class="text-center">{{ $loop->iteration }}</td>
      <td>{{ $pd->nama }}</td>
      <td>{{ $pd->kk->no_kk }}</td>
      <td>{{ $pd->nik }}</td>
      <td>{{ $pd->gender }}</td>
      <td>{{ $pd->alamat }}</td>
      <td class="text-center">{{ $pd->rt->rt }} / {{ $pd->rw->rw }}</td>
      <td class="text-center">{{ $pd->usia }}</td>
      <td>{{ $pd->tmp_lahir }}, {{ Carbon::parse($pd->tgl_lahir)->format('d-m-Y') }}</td>
      <td>{{ $pd->agama }}</td>
      <td>{{ $pd->status_pernikahan }}</td>
      <td>{{ $pd->pekerjaan }}</td>
      <td>
        @php
      $pendudukKK = \App\DataPenduduk::where('kk_id', $pd->kk_id)->get();
      $totalGaji = $pendudukKK->sum('gaji');
      $jumlahOrang = $pendudukKK->count();
      $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

      // Garis Kemiskinan BPS (per kapita per bulan)
      $garisKemiskinan = 595000;

      // Rasio terhadap garis kemiskinan
      $rasio = $garisKemiskinan > 0 ? $rataRata / $garisKemiskinan : 0;

      // Klasifikasi ekonomi BPS
      if ($rasio < 1) {
        $statusEkonomi = 'Miskin';
      } elseif ($rasio < 1.5) {
        $statusEkonomi = 'Rentan Miskin';
      } elseif ($rasio < 3.5) {
        $statusEkonomi = 'Menuju Kelas Menengah';
      } elseif ($rasio < 17) {
        $statusEkonomi = 'Kelas Menengah';
      } else {
        $statusEkonomi = 'Kelas Atas';
      }
      @endphp
        {{ $statusEkonomi }}
        <br>
        <small class="text-muted">Rp {{ number_format($rataRata, 0, ',', '.') }} (Rasio: {{ number_format($rasio, 2) }})</small>
      </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <div style="page-break-inside: avoid;">
    @php
    $boxList = [];
    if (request()->has('tampilkan')) {
      foreach (request('tampilkan') as $kategori) {
      $rows = [];
      switch ($kategori) {
        case 'gender':
        $rows = [['Laki-laki', $jumlah_laki . ' orang'], ['Perempuan', $jumlah_perempuan . ' orang']];
        $title = 'Jenis Kelamin';
        break;
        case 'status_ekonomi':
        // Filter hanya status yang memiliki data lebih dari 0
        $filteredStatus = array_filter($statusCounts, function($jumlah) {
          return $jumlah > 0;
        });
        $rows = collect($filteredStatus)->map(fn($jumlah, $label) => [$label, "$jumlah KK"])->values()->toArray();
        $title = 'Status Ekonomi Keluarga (BPS)';
        break;
        case 'agama':
        $rows = $penduduk->groupBy('agama')->map->count()
          ->map(fn($jumlah, $agama) => [$agama, "$jumlah orang"])->values()->toArray();
        $title = 'Agama';
        break;
        case 'status_pernikahan':
        $rows = $penduduk->groupBy('status_pernikahan')->map->count()
          ->map(fn($jumlah, $status) => [$status, "$jumlah orang"])->values()->toArray();
        $title = 'Status Pernikahan';
        break;
        case 'pekerjaan':
        $rows = $penduduk->groupBy('pekerjaan')->map->count()
          ->map(fn($jumlah, $job) => [$job, "$jumlah orang"])->values()->toArray();
        $title = 'Pekerjaan';
        break;
        case 'usia':
        $rows = [
          ['0–5 Tahun', $penduduk->where('usia', '<=', 5)->count() . ' orang'],
          ['6–17 Tahun', $penduduk->whereBetween('usia', [6, 17])->count() . ' orang'],
          ['18–59 Tahun', $penduduk->whereBetween('usia', [18, 59])->count() . ' orang'],
          ['60+ Tahun', $penduduk->where('usia', '>=', 60)->count() . ' orang'],
        ];
        $title = 'Kategori Usia';
        break;
        default:
        $title = null;
        break;
      }
      if ($title && $rows) {
        $boxList[] = ['title' => $title, 'rows' => $rows];
      }
      }
    }
  @endphp

    @if (!empty($boxList))
    <style>
      .summary-container {
      width: 100%;
      margin-top: 20px;
      font-size: 11px;
      page-break-inside: avoid;
      page-break-before: avoid;
      }

      .summary-box {
      display: inline-block;
      width: 32%;
      vertical-align: top;
      margin: 0 1% 20px 0;
      page-break-inside: avoid;
      }

      .summary-box table {
      width: 100%;
      border: 1px solid #000;
      border-collapse: collapse;
      }

      .summary-box th,
      .summary-box td {
      border: 1px solid #000;
      padding: 4px;
      font-size: 10px;
      }

      .summary-box th {
      background-color: #f2f2f2;
      text-align: center;
      }
    </style>

    <div class="summary-container">
      <p>Berikut adalah tabel rincian data:</p>
      @foreach ($boxList as $box)
      <div class="summary-box">
      <table>
      <thead>
      <tr>
        <th colspan="2">{{ $box['title'] }}</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($box['rows'] as [$label, $value])
      <tr>
      <td>{{ $label }}</td>
      <td style="text-align: right;">{{ $value }}</td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </div>
    @endforeach
    </div>
  @endif
  </div>

  <div class="footer-section">
    <div class="footer-left">
      <p>
        Data ini dicetak pada hari <strong>{{ $waktu->translatedFormat('l, d F Y') }}</strong>
        pada jam <strong>{{ $waktu->format('H:i') }} {{ $zonaAktif }}</strong>
      </p>
    </div>
<div class="footer-right">
        <p style="margin: 0;">
          Tanjungpinang, {{ $waktu->translatedFormat('d F Y') }}<br>
          Admin Kelurahan Kampung Bulang
          <br><br><br><br>
          {{-- <strong>{{ $lurah->nama ?? '-' }}</strong><br> --}}
          {{ $lurah->jabatan ?? '-' }}<br>
          {{-- NIP. {{ $lurah->nip ?? '-' }} --}}
        </p>
      </div>
  </div>

</body>

</html>
