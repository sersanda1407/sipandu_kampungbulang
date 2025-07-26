<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU Kampung Bulang</title>
    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link rel="shortcut icon" href="assets/images/logo/2.webp" loading="lazy" type="image/png">
    <style>
        .modal-custom {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 60px 20px;
        }

        .modal-content-custom {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 16px;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.3s ease-out;
        }

        .close-button {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .close-button:hover {
            color: #000;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-7 d-none d-lg-block position-relative">
                <div id="auth-right">
                    <img src="assets/images/bg_data.webp" loading="lazy" alt="" class="bg-image">
                    <img src="assets/images/Lambang_Kota_Tanjungpinang.webp" loading="lazy" alt="Logo"
                        class="overlay-logo1">
                    <div class="overlay-text">
                        <p>Sistem Informasi <br> Pendataan Penduduk Terpadu <br>Kampung Bulang</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-14">
                <div id="auth-left">
                    <div class="auth-logo pc-only">
                        <h1>SIPANDU</h1>
                        <p>Sistem Informasi Pendataan Penduduk Terpadu</p>
                    </div>

                    <div class="card shadow-lg bg-white rounded" style="width: 100%; max-width: 800px; margin: auto;">
                        <div class="card-body">
                            <div class="logo mobile-only">
                                <img src="assets/images/logo/logo_sipandu.webp" loading="lazy" alt="Logo" class="logo">
                            </div>
                            <div class="mobile-only">
                                <hr class="mobile-hr">
                            </div>

                            <h3 class="text-center fw-bold mb-4">Login</h3>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="form-group position-relative has-icon-left mb-4">
                                    <input type="text" class="form-control form-control-xl"
                                        placeholder="Email atau No. KK" name="email" value="{{ old('email') }}" required
                                        autofocus>
                                    <div class="form-control-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>

                                <div class="form-group position-relative has-icon-left mb-4">
                                    <input type="password" class="form-control form-control-xl" placeholder="Password"
                                        name="password" required>
                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>



                                <div class="form-check form-check-lg d-flex align-items-center mb-4">
                                    <input class="form-check-input me-2" type="checkbox" id="flexCheckDefault">
                                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                        Keep me logged in
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg w-100 mb-2">Log
                                    in</button>

                                <p class="text-center mt-3">
                                    Belum punya akun?
                                    <button type="button" onclick="openModal()"
                                        class="btn btn-outline-primary btn-sm ms-2"
                                        style="font-size: 1rem; padding: 6px 16px;">
                                        Daftar di sini
                                    </button>
                                </p>

                                <div class="alert alert-info d-flex align-items-center mt-4 mb-4" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>
                                        Jika login sebagai warga, gunakan:
                                        <ul class="mb-0 mt-1">
                                            <li>Username: <strong>Nomor Kartu Keluarga <span
                                                        class="text-danger">*</span></strong></li>
                                            <li>Password (default): <strong>password <span
                                                        class="text-danger">*</span></strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <footer class="mt-4 text-center">
                        <div class="footer clearfix mb-0 text-muted">
                            <span id="year"></span> <a href="https://www.instagram.com/sersandaabagas" target="_blank"
                                class="text-decoration-none"> &copy;</a> SIPANDU | Kampung Bulang
                        </div>
                    </footer>

                    <script>
                        document.getElementById("year").textContent = new Date().getFullYear();
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REGISTRASI -->
    <div id="registerModal" class="modal-custom">
        <div class="modal-content-custom">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h4 class="text-center mb-3 fw-bold">Pendaftaran Akun Warga</h4>
            <form action="{{ route('kk.storePublic') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label for="kepala_keluarga">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control" name="kepala_keluarga" required
                        placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group mb-3">
                    <label for="no_kk">Nomor Kartu Keluarga</label>
                    <input type="text" class="form-control" name="no_kk" id="no_kk" pattern="[0-9]+" inputmode="numeric"
                        minlength="16" maxlength="16" required placeholder="Masukan Nomor Kartu Keluarga">
                </div>

                <div class="form-group mb-3">
                    <label for="rw_id">Pilih RW</label>
                    <select name="rw_id" id="rw_id_modal" class="form-control" required>
                        <option value="">-- Pilih RW --</option>
                        @foreach($selectRw as $rw)
                            <option value="{{ $rw->id }}">{{ $rw->rw }} | {{ $rw->nama }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="rt_id">Pilih RT</label>
                    <select name="rt_id" id="rt_id_modal" class="form-control" required>
                        <option value="">-- Pilih RT --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="no_telp">No Telepon</label>
                    <input id="no_hp" name="no_telp" class="form-control" placeholder="Masukkan nomor telepon" maxlength="12" minlength="8" required>
                </div>


                <div class="form-group mb-3">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap"
                        required></textarea>
                </div>

                <div class="mb-3">
    <label>Upload Foto Kartu Keluarga</label>
    <input type="file" name="image" class="form-control upload-gambar" accept="image/*" required>
</div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.querySelectorAll('.upload-gambar').forEach(function(input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (file && !allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png, webp)'
                });
                this.value = '';
            }
        });
    });
</script>


    <script>
        document.getElementById("no_hp").addEventListener("input", function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
        });
    </script>


    <!-- SCRIPT MODAL -->
    <script>
        function openModal() {
            document.getElementById("registerModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("registerModal").style.display = "none";
        }

        window.onclick = function (event) {
            let modal = document.getElementById("registerModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script>
        document.getElementById('no_kk').addEventListener('input', function (e) {
            // Menghapus karakter non-angka
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Deteksi halaman login (tanpa modal aktif)
        @if (Route::currentRouteName() == 'login' && ($errors->has('email') || $errors->has('password')))
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                html: `
                        @if ($errors->has('email'))
                            <div style="text-align:centre;">Silahkan cek kembali. Email atau Password Salah</div>
                        @endif
                        @if ($errors->has('password'))
                            <div style="text-align:centre;"><b>Password yang anda input salah</div>
                        @endif
                    `,
                timer: 10000
            });
        @endif

        // Deteksi error dari form registrasi (modal)
        @if (Route::currentRouteName() == 'kk.storePublic' && $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Pendaftaran Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                timer: 10000
            });
        @endif

        // Session flash message (umum)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: "{{ session('error') }}",
                timer: 10000
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 10000
            });
        @endif
    </script>



    <script>
        // Menghubungkan RW dan RT
    const rtData = @json($selectRt);

        document.getElementById('rw_id_modal').addEventListener('change', function () {
            const rwId = this.value;
            const rtSelect = document.getElementById('rt_id_modal');

            // Kosongkan dahulu pilihan RT
            rtSelect.innerHTML = '<option value="">-- Pilih RT --</option>';

            // Filter sesuai rwId yang dipilih
            const filteredRt = rtData.filter(rt => rt.rw_id == rwId);

            // Tambahan option
            filteredRt.forEach(rt => {
                const option = document.createElement('option');
                option.value = rt.id;
                option.textContent = `${rt.rt} | ${rt.nama}`;
                rtSelect.appendChild(option);
            });
        });

    </script>



</body>

</html>