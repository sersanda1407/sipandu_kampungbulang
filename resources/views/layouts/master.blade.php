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
                'rw' => 'Data RW',
                'rt' => 'Data RT',
                'kk' => 'Data Kartu Keluarga',
                'penduduk' => 'Data Penduduk',
            ];
            $segment = Request::segment(1);
            echo $titleMap[$segment] ?? 'Dashboard';
        @endphp
    </title>


    <link rel="stylesheet" href={{ asset('assets/css/main/app.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/main/app-dark.css') }}>
    <link rel="shortcut icon" href="assets/images/logo/2.webp" type="image/png">

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

        /* Dot hijau indikator aktif */
        .dot-indicator {
            width: 10px;
            height: 10px;
            background-color: #28a745;
            border-radius: 50%;
            display: inline-block;
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
                <form action="/edit-profile" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Email / No KK</label>
                            <input type="text" class="form-control" name="email" id="exampleInputPassword1"
                                value="{{ \Auth::user()->email }}" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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

                        <li class="sidebar-title">Setting</li>
                        <li class="sidebar-item {{ request()->is('pengguna*') ? 'active' : '' }}">
                            <a href="edit#pengguna" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#edit">
                                <i class="fas fa-user-edit"></i>
                                <span>Kelola Akun</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item">
                            <a href="#editLurahModal" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#editLurahModal">
                                <i class="fas fa-user-tie"></i>
                                <span>Edit Data Lurah</span>
                            </a>
                        </li> -->


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
    </script>


</body>

</html>