<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Warga RT {{ $rt->rt ?? '-' }} / RW {{ $rw->rw ?? '-' }} Kelurahan Kampung Bulang</title>
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
      margin-top: 50px;
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
  use Carbon\Carbon;
  Carbon::setLocale('id');
  $waktu = Carbon::now('Asia/Jakarta');
  $zona = [
    'Asia/Jakarta' => 'WIB',
    'Asia/Makassar' => 'WITA',
    'Asia/Jayapura' => 'WIT'
  ];
  $zonaAktif = $zona[$waktu->timezoneName];

  $jumlah_laki = $penduduk->where('gender', 'Laki-laki')->count();
  $jumlah_perempuan = $penduduk->where('gender', 'Perempuan')->count();

  $statusCounts = [
    'Sangat Tidak Mampu' => 0,
    'Tidak Mampu' => 0,
    'Menengah ke Bawah' => 0,
    'Menengah' => 0,
    'Menengah ke Atas' => 0,
    'Mampu' => 0,
  ];

  $uniqueKks = $penduduk->pluck('kk_id')->unique();

  foreach ($uniqueKks as $kk_id) {
    $anggotaKK = $penduduk->where('kk_id', $kk_id);
    $totalGaji = $anggotaKK->sum('gaji');
    $jumlahOrang = $anggotaKK->count();
    $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

    if ($rataRata < 500000)
    $status = 'Sangat Tidak Mampu';
    elseif ($rataRata <= 1500000)
    $status = 'Tidak Mampu';
    elseif ($rataRata <= 3000000)
    $status = 'Menengah ke Bawah';
    elseif ($rataRata <= 5000000)
    $status = 'Menengah';
    elseif ($rataRata <= 10000000)
    $status = 'Menengah ke Atas';
    else
    $status = 'Mampu';

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
      DATA WARGA RT {{ $rt->rt ?? '-' }} / RW {{ $rw->rw ?? '-' }}
      KELURAHAN KAMPUNG BULANG
    </h5>
  </div>

<div class="mb-3">
    <p>
      Berikut ini adalah data lengkap warga yang berdomisili di wilayah Kelurahan Kampung Bulang khususnya di wilayah
      @if ($rt && $rw)
      RT {{ $rt->rt }} / RW {{ $rw->rw }}
    @elseif ($rw)
      RW {{ $rw->rw }}
    @elseif ($rt)
      RT {{ $rt->rt }}
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
      <td>{{ $pd->kk->no_kk ?? '-' }}</td>
      <td>{{ $pd->nik }}</td>
      <td>{{ $pd->gender }}</td>
      <td>{{ $pd->alamat }}</td>
      <td class="text-center">{{ $pd->rt->rt ?? '-' }} / {{ $pd->rw->rw ?? '-' }}</td>
      <td class="text-center">{{ $pd->usia }}</td>
      <td>{{ $pd->tmp_lahir }},{{ Carbon::parse($pd->tgl_lahir)->format('d-m-Y') }}</td>
      <td>{{ $pd->agama }}</td>
      <td>{{ $pd->status_pernikahan }}</td>
      <td>{{ $pd->pekerjaan }}</td>
      <td>
        @php
      $pendudukKK = \App\DataPenduduk::where('kk_id', $pd->kk_id)->get();
      $totalGaji = $pendudukKK->sum('gaji');
      $jumlahOrang = $pendudukKK->count();
      $rataRata = $jumlahOrang > 0 ? $totalGaji / $jumlahOrang : 0;

      if ($rataRata < 500000)
      $statusEkonomi = 'Sangat Tidak Mampu';
      elseif ($rataRata <= 1500000)
      $statusEkonomi = 'Tidak Mampu';
      elseif ($rataRata <= 3000000)
      $statusEkonomi = 'Menengah ke Bawah';
      elseif ($rataRata <= 5000000)
      $statusEkonomi = 'Menengah';
      elseif ($rataRata <= 10000000)
      $statusEkonomi = 'Menengah ke Atas';
      else
      $statusEkonomi = 'Mampu';

    @endphp
        {{ $statusEkonomi }}
        <br>
        <small class="text-muted">Rp.{{ number_format($rataRata, 0, ',', '.') }}</small>
      </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{-- Tabel Ringkasan --}}
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
      $rows = collect($statusCounts)->map(fn($jumlah, $label) => [$label, "$jumlah KK"])->values()->toArray();
      $title = 'Status Ekonomi';
      break;
      case 'agama':
      $rows = $penduduk->groupBy('agama')->map->count()->map(fn($jumlah, $label) => [$label, "$jumlah orang"])->values()->toArray();
      $title = 'Agama';
      break;
      case 'status_pernikahan':
      $rows = $penduduk->groupBy('status_pernikahan')->map->count()->map(fn($jumlah, $label) => [$label, "$jumlah orang"])->values()->toArray();
      $title = 'Status Pernikahan';
      break;
      case 'pekerjaan':
      $rows = $penduduk->groupBy('pekerjaan')->map->count()->map(fn($jumlah, $label) => [$label, "$jumlah orang"])->values()->toArray();
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
      margin-top: 20px;
      font-size: 11px;
      page-break-inside: avoid;
    }

    .summary-box {
      display: inline-block;
      width: 32%;
      margin: 0 1% 20px 0;
      vertical-align: top;
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

  <div class="footer-section">
    <div class="footer-left">
      <p>Data ini dicetak pada <strong>{{ $waktu->translatedFormat('l, d F Y') }}</strong> pukul
        <strong>{{ $waktu->format('H:i') }} {{ $zonaAktif }}</strong>.
      </p>
    </div>
    <div class="footer-right">
      <p>
        Tanjungpinang, {{ $waktu->translatedFormat('d F Y') }}<br>
        Lurah Kampung Bulang<br><br><br>
        <strong>{{ $lurah->nama ?? '-' }}</strong><br>
        {{ $lurah->jabatan ?? '-' }}<br>
        NIP. {{ $lurah->nip ?? '-' }}
      </p>
    </div>
  </div>

</body>

</html>