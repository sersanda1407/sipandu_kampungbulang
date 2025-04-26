<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU Kampung Bulang</title>
    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <!-- <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon"> -->
    <link rel="shortcut icon" href="assets/images/logo/2.png" type="image/png">


    <style>
        .bg-image {
            width: 100%;
            height: 100%;
        }

        .overlay-logo {
            position: absolute;
            top: 30px;
            /* Jarak dari atas */
            left: 120px;
            /* Jarak dari kiri */
            width: 80px;
            /* Sesuaikan ukuran logo */
            height: auto;
            z-index: 10;
            /* Pastikan logo berada di atas gambar */

            /* Efek shadow */
            filter: drop-shadow(5px 5px 10px rgba(255, 255, 255, 0.5));
        }

        .overlay-logo1 {
            position: absolute;
            top: 30px;
            /* Jarak dari atas */
            left: 65px;
            /* Jarak dari kiri */
            width: 50px;
            /* Sesuaikan ukuran logo */
            height: auto;
            z-index: 10;
            /* Pastikan logo berada di atas gambar */

            /* Efek shadow */
            filter: drop-shadow(5px 5px 10px rgba(0, 0, 0, 0.5));
        }

        .overlay-text {
            position: absolute;
            top: 30px;
            /* Sesuaikan dengan posisi logo */
            left: 130px;
            /* Jarak dari logo */
            color: white;
            /* Warna teks */
            font-weight: bold;

            z-index: 11;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .overlay-text h2 {
            font-size: 16px;
            color: white;
            margin: 0;
        }

        .overlay-text p {
            font-size: 12px;
            margin: 5px 0 0;
        }


        .logo {
            width: 3000px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(135, 133, 255, 0.7);
            margin: 10px 0 0;
        }

        .mobile-only {
            display: none !important;
        }

        .pc-only {
            display: flex;

        }

        @media (max-width: 768px) {
            .pc-only {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .mobile-only {
                display: flex !important;
                align-items: center;
                justify-content: center;

                padding: 10px 0;
                /* Beri sedikit ruang */
            }
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(135, 133, 255, 0.7);
            margin: 10px 0 0;
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-7 d-none d-lg-block position-relative">
                <div id="auth-right">
                    <img src="assets/images/bg_data.png" alt="" class="bg-image">

                    <!-- Logo -->
                    <img src="assets/images/Lambang_Kota_Tanjungpinang.png" alt="Logo" class="overlay-logo1">
                    <!-- <img src="assets/images/logo/2.png" alt="Logo" class="overlay-logo"> -->

                    <!-- Tulisan di sebelah kanan logo -->
                    <div class="overlay-text">
                        <!-- <h2>SIPEKA | Kampung Bulang</h2> -->
                        <p>Sistem Informasi <br> Pendataan Penduduk Terpadu <br>Kampung Bulang</p>
                    </div>
                </div>
            </div>



            <div class="col-lg-5 col-14">
    <div id="auth-left">
        <!-- Logo untuk Desktop -->
        <div class="auth-logo pc-only">
            <h1>SIPANDU</h1>
            <p>Sistem Informasi Pendataan Penduduk Terpadu</p>
        </div>

        <!-- Logo untuk Mobile -->
        <div class="auth-logo mobile-only">
            <img src="assets/images/logo/logo_sipandu.png" alt="Logo" class="logo">
        </div>

        <!-- Card Login -->
        <div class="card shadow-lg bg-white rounded" style="width: 100%; max-width: 800px; margin: auto;">
            <div class="card-body">
                <h3 class="text-center fw-bold mb-4">Login</h3>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Input Email atau No. KK -->
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('email') is-invalid @enderror"
                               placeholder="Email atau No. KK" name="email" value="{{ old('email') }}" required autofocus>
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                               placeholder="Password" name="password" required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Checkbox Keep me logged in -->
                    <div class="form-check form-check-lg d-flex align-items-center mb-4">
                        <input class="form-check-input me-2" type="checkbox" id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Keep me logged in
                        </label>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg w-100">Log in</button>

                    <!-- Info Login Warga -->
                            <div class="alert alert-info d-flex align-items-center mt-4 mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    Jika login sebagai warga, gunakan:
                    <ul class="mb-0 mt-1">
                        <li>Username: <strong>Nomor Kartu Keluarga <span class="text-danger">*</span></strong></li>
                        <li>Password (default): <strong>password <span class="text-danger">*</span></strong></li>
                    </ul>
                </div>
            </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-4 text-center">
            <div class="footer clearfix mb-0 text-muted">
                <span id="year"></span> <a href="https://www.instagram.com/sersandaabagas" target="_blank" class="text-decoration-none"> &copy;</a> SIPANDU | Kampung Bulang
            </div>
        </footer>

        <script>
            document.getElementById("year").textContent = new Date().getFullYear();
        </script>
    </div>
</div>

    </div>



</body>

</html>