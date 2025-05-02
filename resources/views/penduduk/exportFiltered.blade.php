<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Warga Kelurahan Kampung Bulang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 10pt;
      margin: 20px;
    }
    .kop-surat {
      margin-top: -30px;
      margin-bottom: 10px;
    }
    .kop-surat img {
      width: 100%;
      max-width: 1000px;
    }
    h5.title {
      margin-top: 5px;
      margin-bottom: 25px;
      font-weight: bold;
      text-decoration: underline;
    }
    table th, table td {
      font-size: 9pt;
      vertical-align: middle !important;
    }
    .footer-section {
    page-break-inside: avoid;
    display: flex;
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
  @endphp
</head>
<body>

  <div class="text-center kop-surat">
    <img src="{{ public_path('assets/images/kop_surat_kpbulang.png') }}" alt="Kop Surat Kelurahan Kampung Bulang">
    <div class="mb-3 text-right" style="margin-right: 20px;">
      <p>Tanjungpinang, {{ $waktu->translatedFormat('d F Y') }}</p>
    </div>
    <h5 class="title">DATA WARGA KELURAHAN KAMPUNG BULANG</h5>
  </div>

  <div class="mb-3">
  <p>
  Berikut ini adalah data lengkap warga yang berdomisili di wilayah Kelurahan Kampung Bulang khususnya di wilayah
  @if ($rt && $rw)
    RT {{ $rt->rt }} / RW {{ $rw->rw }}
  @elseif ($rw)
    RW {{ $rw->rw }}
  @elseif ($rt)
    RT{{ $rt->rt }}
  @else
    .
  @endif
  :
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
        <th>Tempat & Tanggal Lahir</th>
        <th>Agama</th>
        <th>Usia</th>
        <th>Status Pernikahan</th>
        <th>Pekerjaan</th>
        <th>Status Ekonomi</th>
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
          <td>{{ $pd->tmp_lahir }}, {{ \Carbon\Carbon::parse($pd->tgl_lahir)->format('d-m-Y') }}</td>
          <td>{{ $pd->agama }}</td>
          <td class="text-center">{{ $pd->usia }}</td>
          <td>{{ $pd->status_pernikahan }}</td>
          <td>{{ $pd->pekerjaan }}</td>
          <td>{{ $pd->kk->status_ekonomi }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mb-3">
  <p>TOTAL : <br> <strong>{{ $penduduk->pluck('kk_id')->unique()->count() }} KK (Kartu Keluarga) dan
  {{ $penduduk->count() }} warga. <br>Jumlah Laki-laki : {{ $jumlah_laki }} <br> Jumlah Perempuan : {{ $jumlah_perempuan }} </strong> </p>
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
        Lurah Kampung Bulang
        <br><br><br>
        <strong>{{ $lurah->nama ?? '-' }}</strong><br>
        {{ $lurah->jabatan ?? '-' }}<br>
        NIP. {{ $lurah->nip ?? '-' }}
      </p>
    </div>
  </div>

</body>
</html>
