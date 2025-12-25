<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        SIPANDU -
        @php
            $titleMap = [
                'dashboard' => 'Dashboard',
                'inbox' => 'Kotak Verifikasi',
                'rw' => 'Data Ketua RW Kampung Bulang',
                'rt' => 'Data Ketua RT Kampung Bulang',
                'kk' => 'Data Kartu Keluarga',
                'penduduk' => 'Data Penduduk Kelurahan Kampung Bulang',
                'histori' => 'History Log',
            ];
            $segment = Request::segment(1);
            echo $titleMap[$segment] ?? 'Dashboard';
        @endphp
    </title>


    <link rel="stylesheet" href={{ asset('assets/css/main/app.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/main/app-dark.css') }}>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/2.webp') }}" type="image/png">


    <link rel="stylesheet" href={{ asset('assets/css/shared/iconly.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/pages/simple-datatables.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/pages/fontawesome.css') }}>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .chart-container {
            position: relative;
            width: 100%;
            height: 180px;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .accordion-button::after {
            display: none !important;
        }

        .dot-indicator {
            width: 10px;
            height: 10px;
            background-color: #28a745;
            border-radius: 50%;
            display: inline-block;
        }

        /* Privacy Policy Modal Styles */
        .privacy-modal {
            display: none;
            position: fixed;
            z-index: 1060;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .privacy-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 80%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .privacy-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .privacy-close:hover {
            color: #000;
        }

        .privacy-title {
            color: #2c3e50;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .privacy-section {
            margin-bottom: 20px;
        }

        .privacy-section h3 {
            color: #3498db;
            margin-bottom: 10px;
        }

        .log-table th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }
    </style>


</head>


<body>
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kelola Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/edit-profile" method="POST" id="profileForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Field untuk semua role -->
                        @php
                            $namaValue = Auth::user()->name;

                            if (Auth::user()->hasRole('warga')) {
                                $kkData = Auth::user()->Kk->first();
                                $namaValue = $kkData ? $kkData->kepala_keluarga : Auth::user()->name;
                            } elseif (Auth::user()->hasRole('rw')) {
                                $rwData = Auth::user()->Rw->first();
                                $namaValue = $rwData ? $rwData->nama : Auth::user()->name;
                            } elseif (Auth::user()->hasRole('rt')) {
                                $rtData = Auth::user()->Rt->first();
                                $namaValue = $rtData ? $rtData->nama : Auth::user()->name;
                            }
                        @endphp

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control text-capitalize" name="nama" id="nama"
                                value="{{ $namaValue }}" required>
                        </div>

                        <!-- Field khusus untuk RW -->
                        @if(Auth::user()->hasRole('rw'))
                            @php $rwData = \App\DataRw::where('user_id', Auth::id())->first(); @endphp
                            @if($rwData)
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">Nomor Telepon / WhatsApp</label>
                                    <input type="text" class="form-control" name="no_hp" id="no_hp" value="{{ $rwData->no_hp }}"
                                        required data-current-value="{{ $rwData->no_hp }}" minlength="8" maxlength="12"
                                        pattern="[0-9]{8,12}" title="Nomor HP harus 8-12 digit angka">
                                    <div class="invalid-feedback" id="no_hp_error"></div>
                                    <small class="form-text text-muted">Minimal 8 digit, maksimal 12 digit (hanya angka)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="gmail_rw" class="form-label">Email Pribadi</label>
                                    <input type="email" class="form-control" name="gmail_rw" id="gmail_rw"
                                        value="{{ $rwData->gmail_rw }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_rw" class="form-label">Alamat RW</label>
                                    <textarea class="form-control" name="alamat_rw" id="alamat_rw"
                                        required>{{ $rwData->alamat_rw }}</textarea>
                                </div>
                            @endif
                        @endif

                        <!-- Field khusus untuk RT -->
                        @if(Auth::user()->hasRole('rt'))
                            @php $rtData = \App\DataRt::where('user_id', Auth::id())->first(); @endphp
                            @if($rtData)
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">Nomor Telepon / WhatsApp</label>
                                    <input type="text" class="form-control" name="no_hp" no_hp" id="no_hp"
                                        value="{{ $rtData->no_hp }}" required data-current-value="{{ $rtData->no_hp }}"
                                        minlength="8" maxlength="12" pattern="[0-9]{8,12}"
                                        title="Nomor HP harus 8-12 digit angka">
                                    <div class="invalid-feedback" id="no_hp_error"></div>
                                    <small class="form-text text-muted">Minimal 8 digit, maksimal 12 digit (hanya angka)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="gmail_rt" class="form-label">Email Pribadi</label>
                                    <input type="email" class="form-control" name="gmail_rt" id="gmail_rt"
                                        value="{{ $rtData->gmail_rt }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_rt" class="form-label">Alamat RT</label>
                                    <textarea class="form-control" name="alamat_rt" id="alamat_rt"
                                        required>{{ $rtData->alamat_rt }}</textarea>
                                </div>
                            @endif
                        @endif

                        <!-- Field khusus untuk Warga -->
                        @if(Auth::user()->hasRole('warga'))
                            @php $kkData = \App\DataKk::where('user_id', Auth::id())->first(); @endphp
                            @if($kkData)
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" id="alamat"
                                        required>{{ $kkData->alamat }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="no_telp" class="form-label">Nomor Telepon / WhatsApp</label>
                                    <input type="text" class="form-control" name="no_telp" id="no_telp"
                                        value="{{ $kkData->no_telp }}" required data-current-value="{{ $kkData->no_telp }}"
                                        minlength="8" maxlength="12" pattern="[0-9]{8,12}"
                                        title="Nomor telepon harus 8-12 digit angka">
                                    <div class="invalid-feedback" id="no_telp_error"></div>
                                    <small class="form-text text-muted">Minimal 8 digit, maksimal 12 digit (hanya angka)</small>
                                </div>
                            @endif
                        @endif

                        <!-- Field untuk semua role -->
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Email / No KK</label>
                            <input type="text" class="form-control" name="email" id="exampleInputPassword1"
                                value="{{ \Auth::user()->email }}" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" minlength="6"
                                pattern=".{6,}" title="Password minimal 6 karakter">
                            <div class="invalid-feedback" id="password_error"></div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password (minimal 6
                                karakter)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editLurahModal" tabindex="-1" aria-labelledby="editLurahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('edit.lurah') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLurahModalLabel">Edit Data Lurah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="nama"
                                value="{{ $lurah->nama ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" id="jabatan"
                                value="{{ $lurah->jabatan ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" id="nip" value="{{ $lurah->nip ?? '' }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="privacy-modal">
        <div class="privacy-modal-content">
            <span class="privacy-close" onclick="closePrivacyModal()">&times;</span>
            <h2 class="privacy-title">Kebijakan Privasi SIPANDU Kampung Bulang</h2>

            <div class="privacy-section">
                <h3>1. Pengumpulan Informasi</h3>
                <p>Kami mengumpulan informasi pribadi yang Anda berikan secara langsung saat menggunakan layanan
                    SIPANDU, sesuai dengan ketentuan Undang-Undang Dasar 1945 Pasal 28G dan Undang-Undang Nomor 27 Tahun
                    2022 tentang Perlindungan Data Pribadi. Informasi yang dikumpulkan mencakup:</p>
                <ul>
                    <li>Data identitas (nama, NIK, nomor KK)</li>
                    <li>Data kontak (alamat, nomor telepon, email)</li>
                    <li>Data kependudukan (status keluarga, data anggota keluarga)</li>
                    <li>Data lokasi (alamat tempat tinggal, RT/RW)</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h3>2. Penggunaan Informasi</h3>
                <p>Informasi pribadi digunakan untuk kepentingan pelayanan publik, termasuk namun tidak terbatas pada:
                </p>
                <ul>
                    <li>Memverifikasi identitas Anda sebagai warga Kampung Bulang</li>
                    <li>Menyediakan layanan administrasi kependudukan</li>
                    <li>Mempermudah proses pelayanan publik di tingkat RT/RW</li>
                    <li>Komunikasi terkait layanan dan informasi penting</li>
                    <li>Peningkatan kualitas layanan</li>
                    <li>Statistik dan perencanaan pembangunan wilayah</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h3>3. Perlindungan Data</h3>
                <p>Kami berkomitmen melindungi data pribadi Anda dengan langkah teknis dan organisasi yang sesuai. Data
                    hanya dapat diakses oleh pihak berwenang dan dilindungi dari akses, penggunaan, atau pengungkapan
                    yang tidak sah, sebagaimana diatur dalam UU Perlindungan Data Pribadi.</p>
            </div>

            <div class="privacy-section">
                <h3>4. Penyimpanan Data</h3>
                <p>Data pribadi Anda akan disimpan selama diperlukan untuk tujuan yang dijelaskan dalam kebijakan ini
                    atau sesuai ketentuan hukum. Apabila data tidak lagi diperlukan, data akan dihapus atau dianonimkan
                    sesuai dengan peraturan yang berlaku.</p>
            </div>

            <div class="privacy-section">
                <h3>5. Hak Anda</h3>
                <p>Sesuai UU Perlindungan Data Pribadi, Anda memiliki hak untuk:</p>
                <ul>
                    <li>Mengakses informasi pribadi yang kami simpan</li>
                    <li>Memperbaiki data pribadi yang tidak akurat</li>
                    <li>Meminta penghapusan data dalam kondisi tertentu</li>
                    <li>Menarik kembali persetujuan atas pemrosesan data pribadi</li>
                    <li>Mengajukan keberatan atas penggunaan data Anda</li>
                </ul>
                <p>Untuk menggunakan hak-hak ini, silakan hubungi admin SIPANDU Kampung Bulang.</p>
            </div>

            <div class="privacy-section">
                <h3>6. Pembagian Informasi</h3>
                <p>Kami tidak menjual atau memperdagangkan informasi pribadi Anda. Data hanya dapat dibagikan kepada
                    pihak ketiga dalam kondisi:</p>
                <ul>
                    <li>Atas persetujuan Anda</li>
                    <li>Pemenuhan kewajiban hukum atau perintah pengadilan</li>
                    <li>Penyediaan layanan publik yang Anda minta</li>
                    <li>Perlindungan hak, keamanan, dan properti Kelurahan atau warga</li>
                </ul>
            </div>

            <div class="privacy-section">
                <h3>7. Perubahan Kebijakan</h3>
                <p>Kebijakan ini dapat diperbarui sewaktu-waktu sesuai perkembangan hukum dan teknologi. Setiap
                    perubahan akan diumumkan melalui situs resmi atau media komunikasi lain yang ditetapkan oleh
                    Kelurahan Kampung Bulang. Dengan tetap menggunakan layanan SIPANDU, Anda dianggap menyetujui
                    perubahan kebijakan tersebut.</p>
            </div>


            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="closePrivacyModal()">Saya Mengerti</button>
            </div>
        </div>
    </div>



    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="title">
                            <h3>SIPANDU</h3>
                            <p class="small" style="font-size: 10px;">Sistem Informasi Pendataan Penduduk Terpadu
                                Kelurahan
                                Kampung Bulang
                            </p>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" />
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        {{-- sesuaikan --}}
                        <li class="sidebar-item px-3 pb-1">
                            <div class="accordion" id="accordionUserInfo">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingUser">
                                        <button
                                            class="accordion-button collapsed px-0 py-2 bg-transparent shadow-none d-flex align-items-center gap-2"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser"
                                            aria-expanded="false" aria-controls="collapseUser"
                                            style="padding-right: 0; background: none;">
                                            <span class="dot-indicator"></span>
                                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                                        </button>
                                    </h2>
                                    <div id="collapseUser" class="accordion-collapse collapse"
                                        aria-labelledby="headingUser" data-bs-parent="#accordionUserInfo">
                                        <div class="accordion-body px-0 py-1">
                                            @php
                                                $role = ucfirst(Auth::user()->getRoleNames()->first());
                                                switch ($role) {
                                                    case 'Rt':
                                                        $roleDisplay = 'Ketua RT';
                                                        break;
                                                    case 'Rw':
                                                        $roleDisplay = 'Ketua RW';
                                                        break;
                                                    case 'Superadmin':
                                                        $roleDisplay = 'Admin Kelurahan';
                                                        break;
                                                    default:
                                                        $roleDisplay = $role;
                                                        break;
                                                }
                                            @endphp

                                            <span class="d-block text-muted small">
                                                Role:
                                                <strong>{{ $roleDisplay }}</strong>
                                            </span>

                                            <span class="d-block text-muted small">
                                                Status: <span class="text-success">Aktif</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>


                        <li class="sidebar-title">Dashboard</li>

                        <li class="sidebar-item {{ request()->is('dashboard*') ? 'active' : '' }} ">
                            <a href="/dashboard" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @hasrole('superadmin|rw|rt')
                        <li class="sidebar-item {{ request()->is('inbox*') ? 'active' : '' }}">
                            <a href="{{ route('inbox.index') }}" class='sidebar-link position-relative'>
                                <i class="bi bi-inbox-fill fs-5 me-2"></i>
                                <span>Kotak Verifikasi</span>
                                @if ($inboxCount > 0)
                                    <span class="badge bg-danger rounded-pill position-absolute"
                                        style="top: 46%; left: 80%; transform: translate(-50%, -50%)">
                                        {{ $inboxCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        @endhasrole



                        <li class="sidebar-title">Menu</li>

                        @hasrole('superadmin')
                        <li class="sidebar-item {{ request()->is('rw*') ? 'active' : '' }}">
                            <a href={{ url('/rw') }} class='sidebar-link'>
                                <i class="fas fa-user"></i>
                                <span> Data RW</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasrole('superadmin|rw')
                        <li class="sidebar-item {{ request()->is('rt*') ? 'active' : '' }}">
                            <a href={{ url('/rt') }} class='sidebar-link'>
                                <i class="fas fa-user-friends"></i>
                                <span>Data RT</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasrole('superadmin|rw|rt|warga')
                        <li class="sidebar-item {{ request()->is('kk*') ? 'active' : '' }}">
                            <a href={{ url('/kk') }} class='sidebar-link'>
                                <i class="fas fa-address-card"></i>
                                <span>Data Kartu Keluarga</span>
                            </a>
                        </li>
                        @endhasrole

                        @hasrole('superadmin|rw|rt')
                        <li class="sidebar-item {{ request()->is('penduduk*') ? 'active' : '' }}">
                            <a href={{ url('/penduduk') }} class='sidebar-link'>
                                <i class="fas fa-users"></i>
                                <span>Data Penduduk</span>
                            </a>
                        </li>
                        @endhasrole
                        <li class="sidebar-title">Privacy</li>

                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="showPrivacyPolicy()">
                                <i class="fas fa-shield"></i>
                                <span>Privacy & Policy</span>
                            </a>
                        </li>

                        @hasrole('superadmin')
                        <li class="sidebar-item {{ request()->is('histori*') ? 'active' : '' }}">
                            <a href="{{ route('histori.index') }}" class="sidebar-link">
                                <i class="fas fa-history"></i>
                                <span>History Log</span>
                            </a>
                        </li>
                        @endhasrole

                        <li class="sidebar-title">Setting</li>
                        <li class="sidebar-item {{ request()->is('pengguna*') ? 'active' : '' }}">
                            <a href="edit#pengguna" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#edit">
                                <i class="fas fa-user-edit"></i>
                                <span>Kelola Akun</span>
                            </a>
                        </li>

                        

                        {{-- <li class="sidebar-item">
                            <a href="#editLurahModal" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#editLurahModal">
                                <i class="fas fa-user-tie"></i>
                                <span>Edit Data Lurah</span>
                            </a>
                        </li> --}}


                        <li class="sidebar-item ">
                            <a class="sidebar-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt "></i>
                                <span>Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                            </a>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('master')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p id="year">&copy; SIPANDU | Kampung Bulang</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @include('sweetalert::alert')

    <script src={{ asset('assets/js/app.js') }}></script>
    <script src={{ asset('assets/js/pages/dashboard.js') }}></script>
    <script src={{ asset('assets/js/extensions/simple-datatables.js') }}></script>
    <script>
        document.getElementById("year").innerHTML = new Date().getFullYear() + " &copy; SIPANDU | Kampung Bulang";

        // Fungsi untuk menampilkan modal privacy policy
        function showPrivacyPolicy() {
            document.getElementById('privacyModal').style.display = 'block';
        }

        // Fungsi untuk menutup modal privacy policy
        function closePrivacyModal() {
            document.getElementById('privacyModal').style.display = 'none';
        }

        // Tutup modal jika klik di luar konten
        window.onclick = function (event) {
            const privacyModal = document.getElementById('privacyModal');
            if (event.target == privacyModal) {
                privacyModal.style.display = 'none';
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fungsi untuk validasi input nomor
            function setupPhoneValidation(inputId, errorId) {
                const input = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);

                if (input && errorElement) {
                    // Validasi input hanya angka
                    input.addEventListener("input", function () {
                        this.value = this.value.replace(/\D/g, '');

                        // Validasi length
                        if (this.value.length > 12) {
                            this.value = this.value.slice(0, 12);
                        }

                        // Hapus error saat user mengetik
                        if (this.value.length >= 8) {
                            this.classList.remove('is-invalid');
                            errorElement.textContent = '';
                        }
                    });

                    // Validasi saat blur
                    input.addEventListener("blur", function () {
                        if (this.value.length < 8 && this.value.length > 0) {
                            this.classList.add('is-invalid');
                            errorElement.textContent = 'Nomor telepon/HP minimal 8 digit.';
                        } else {
                            this.classList.remove('is-invalid');
                            errorElement.textContent = '';
                        }
                    });
                }
            }

            // Setup validasi untuk password
            function setupPasswordValidation() {
                const passwordInput = document.getElementById('password');
                const passwordError = document.getElementById('password_error');

                if (passwordInput && passwordError) {
                    // Validasi saat input
                    passwordInput.addEventListener("input", function () {
                        if (this.value.length > 0 && this.value.length < 6) {
                            this.classList.add('is-invalid');
                            passwordError.textContent = 'Password minimal 6 karakter.';
                        } else {
                            this.classList.remove('is-invalid');
                            passwordError.textContent = '';
                        }
                    });

                    // Validasi saat blur
                    passwordInput.addEventListener("blur", function () {
                        if (this.value.length > 0 && this.value.length < 6) {
                            this.classList.add('is-invalid');
                            passwordError.textContent = 'Password minimal 6 karakter.';
                        } else {
                            this.classList.remove('is-invalid');
                            passwordError.textContent = '';
                        }
                    });
                }
            }

            // Setup validasi untuk semua input no HP/telepon
            setupPhoneValidation('no_telp', 'no_telp_error');
            setupPhoneValidation('no_hp', 'no_hp_error');
            setupPasswordValidation();

            // Validasi duplikasi no HP/telepon dan password
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitButton = document.getElementById('submitButton');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memeriksa...';

                let isValid = true;

                // Validasi length no_telp
                const noTelpInput = document.getElementById('no_telp');
                if (noTelpInput && noTelpInput.value.length > 0 && noTelpInput.value.length < 8) {
                    noTelpInput.classList.add('is-invalid');
                    document.getElementById('no_telp_error').textContent = 'Nomor telepon minimal 8 digit.';
                    isValid = false;
                }

                // Validasi length no_hp
                const noHpInput = document.getElementById('no_hp');
                if (noHpInput && noHpInput.value.length > 0 && noHpInput.value.length < 8) {
                    noHpInput.classList.add('is-invalid');
                    document.getElementById('no_hp_error').textContent = 'Nomor HP minimal 8 digit.';
                    isValid = false;
                }

                // Validasi password
                const passwordInput = document.getElementById('password');
                if (passwordInput && passwordInput.value.length > 0 && passwordInput.value.length < 6) {
                    passwordInput.classList.add('is-invalid');
                    document.getElementById('password_error').textContent = 'Password minimal 6 karakter.';
                    isValid = false;
                }

                if (!isValid) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Simpan Perubahan';
                    // Focus pada input pertama yang error
                    if (noTelpInput && noTelpInput.classList.contains('is-invalid')) {
                        noTelpInput.focus();
                    } else if (noHpInput && noHpInput.classList.contains('is-invalid')) {
                        noHpInput.focus();
                    } else if (passwordInput && passwordInput.classList.contains('is-invalid')) {
                        passwordInput.focus();
                    }
                    return;
                }

                // Validasi duplikasi no HP untuk RW/RT
                if (noHpInput) {
                    const currentNoHp = noHpInput.getAttribute('data-current-value');
                    const newNoHp = noHpInput.value;

                    if (newNoHp !== currentNoHp) {
                        const isDuplicate = await checkDuplicatePhone(newNoHp);
                        if (isDuplicate) {
                            noHpInput.classList.add('is-invalid');
                            document.getElementById('no_hp_error').textContent = 'Nomor HP sudah digunakan oleh pengguna lain.';
                            isValid = false;
                        } else {
                            noHpInput.classList.remove('is-invalid');
                        }
                    }
                }

                // Validasi duplikasi no telepon untuk warga
                if (noTelpInput) {
                    const currentNoTelp = noTelpInput.getAttribute('data-current-value');
                    const newNoTelp = noTelpInput.value;

                    if (newNoTelp !== currentNoTelp) {
                        const isDuplicate = await checkDuplicatePhone(newNoTelp);
                        if (isDuplicate) {
                            noTelpInput.classList.add('is-invalid');
                            document.getElementById('no_telp_error').textContent = 'Nomor telepon sudah digunakan oleh pengguna lain.';
                            isValid = false;
                        } else {
                            noTelpInput.classList.remove('is-invalid');
                        }
                    }
                }

                if (isValid) {
                    this.submit();
                } else {
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Simpan Perubahan';
                }
            });

            // Fungsi untuk memeriksa duplikasi no HP/telepon
            async function checkDuplicatePhone(phoneNumber) {
                try {
                    const response = await fetch('/api/check-nophone?no_telp=' + encodeURIComponent(phoneNumber));
                    const data = await response.json();
                    return data.exists;
                } catch (error) {
                    console.error('Error checking duplicate phone:', error);
                    return false;
                }
            }
        });
    </script>

</body>

</html>