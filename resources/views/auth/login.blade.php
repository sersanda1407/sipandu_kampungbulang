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
        .phone-checking {
            color: #ffc107;
        }

        .phone-available {
            color: #28a745;
        }

        .phone-taken {
            color: #dc3545;
        }

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

        /* Additional styles for privacy policy */
        .privacy-check {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .privacy-link {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
        }

        .privacy-link:hover {
            color: #0056b3;
        }

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

        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed !important;
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
                        <p>Sistem Informasi <br> Pendataan Kependudukan Terpadu <br>Kampung Bulang</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-14">
                <div id="auth-left">
                    <div class="auth-logo pc-only">
                        <h1>SIPANDU</h1>
                        <p>Sistem Pendataan Kependudukan Terpadu Kelurahan Kampung Bulang</p>
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
            <form id="registrationForm" action="{{ route('kk.storePublic') }}" method="POST" enctype="multipart/form-data">
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
                    <input id="no_hp" name="no_telp" class="form-control" placeholder="Masukkan nomor telepon"
                        maxlength="12" minlength="8" required>
                    <div id="phone-check-result" class="form-text"></div>
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

                <!-- Privacy Policy Checkbox -->
                <div class="privacy-check">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="privacyPolicy" required>
                        <label class="form-check-label" for="privacyPolicy">
                            Saya setuju dengan <span class="privacy-link" onclick="showPrivacyPolicy()">Kebijakan Privasi</span> yang berlaku
                        </label>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" id="submitButton" class="btn btn-primary w-100 btn-disabled" disabled>Daftar</button>
                </div>
            </form>
        </div>
    </div>

<!-- Privacy Policy Modal -->
<div id="privacyModal" class="privacy-modal">
    <div class="privacy-modal-content">
        <span class="privacy-close" onclick="closePrivacyModal()">&times;</span>
        <h2 class="privacy-title">Kebijakan Privasi</h2>

        <div class="privacy-section">
            <h3>1. Pengumpulan Data Pribadi</h3>
            <p>
                Kami mengumpulkan data pribadi yang Anda berikan secara langsung saat mendaftar di SIPANDU Kampung Bulang, termasuk namun tidak terbatas pada:
            </p>
            <ul>
                <li>Nama lengkap</li>
                <li>Nomor Induk Kependudukan (NIK)</li>
                <li>Nomor Kartu Keluarga (KK)</li>
                <li>Alamat domisili</li>
                <li>Nomor telepon atau kontak lain yang dapat dihubungi</li>
            </ul>
            <p>
                Pengumpulan data ini sesuai dengan Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 Pasal 28G ayat (1) dan Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi.
            </p>
        </div>

        <div class="privacy-section">
            <h3>2. Tujuan Penggunaan Data</h3>
            <p>Data pribadi Anda digunakan untuk kepentingan:</p>
            <ul>
                <li>Memverifikasi identitas Anda sebagai warga Kampung Bulang</li>
                <li>Pendataan administrasi kependudukan di tingkat RT, RW, dan Kelurahan</li>
                <li>Penyelenggaraan program pemerintah (misalnya bantuan sosial, kesehatan, atau sensus)</li>
                <li>Komunikasi resmi terkait layanan masyarakat</li>
                <li>Peningkatan kualitas layanan SIPANDU</li>
            </ul>
            <p>
                Data tidak akan digunakan untuk tujuan komersial atau disebarluaskan kepada pihak ketiga tanpa dasar hukum yang sah.
            </p>
        </div>

        <div class="privacy-section">
            <h3>3. Perlindungan Data</h3>
            <p>
                Kami menerapkan langkah-langkah teknis dan organisasi yang wajar untuk melindungi data pribadi Anda dari akses, perubahan, pengungkapan, atau penggunaan yang tidak sah.
                Akses hanya diberikan kepada pihak berwenang (RT, RW, atau Kelurahan) sesuai kebutuhan administratif.
            </p>
        </div>

        <div class="privacy-section">
            <h3>4. Penyimpanan Data</h3>
            <p>
                Data pribadi Anda akan disimpan selama masih diperlukan untuk tujuan administratif atau sesuai kewajiban hukum yang berlaku. Setelah tujuan tersebut tercapai, data dapat dihapus atau dianonimkan sesuai ketentuan hukum.
            </p>
        </div>

        <div class="privacy-section">
            <h3>5. Hak Warga</h3>
            <p>Sesuai dengan UU Perlindungan Data Pribadi, Anda memiliki hak untuk:</p>
            <ul>
                <li>Mengakses data pribadi yang tersimpan di sistem</li>
                <li>Meminta perbaikan atas data yang tidak akurat</li>
                <li>Meminta penghapusan data pribadi Anda, kecuali masih diwajibkan untuk kepentingan administrasi sesuai hukum</li>
                <li>Mendapatkan informasi mengenai penggunaan data Anda</li>
            </ul>
            <p>
                Untuk menggunakan hak ini, Anda dapat menghubungi Admin SIPANDU Kampung Bulang.
            </p>
        </div>

        <div class="privacy-section">
            <h3>6. Perubahan Kebijakan</h3>
            <p>
                Kebijakan privasi ini dapat diperbarui dari waktu ke waktu sesuai kebutuhan. Setiap perubahan akan diumumkan melalui sistem SIPANDU atau media komunikasi resmi lainnya.
            </p>
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-primary" onclick="closePrivacyModal()">Saya Mengerti</button>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.upload-gambar').forEach(function (input) {
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

    <script>
        // Fungsi untuk cek duplikasi nomor HP
        function checkDuplicatePhone(phoneNumber) {
            return fetch('/api/check-phone?no_telp=' + encodeURIComponent(phoneNumber))
                .then(response => response.json())
                .then(data => data.exists);
        }

        // Event listener untuk input nomor HP
        document.getElementById('no_hp').addEventListener('blur', function (e) {
            const phoneNumber = this.value.trim();

            if (phoneNumber.length >= 8) { // Minimal 8 digit
                checkDuplicatePhone(phoneNumber).then(isDuplicate => {
                    if (isDuplicate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor HP Sudah Terdaftar',
                            text: 'Nomor HP ini sudah terdaftar dalam sistem. Silakan gunakan nomor lain.',
                            timer: 5000
                        });

                        // Reset input
                        this.value = '';
                        this.focus();
                    }
                }).catch(error => {
                    console.error('Error checking phone:', error);
                });
            }
        });

        // Juga cek saat form disubmit
        document.querySelector('form[action="{{ route('kk.storePublic') }}"]').addEventListener('submit', function (e) {
            const phoneInput = document.getElementById('no_hp');
            const phoneNumber = phoneInput.value.trim();

            if (phoneNumber.length >= 8) {
                e.preventDefault(); // Prevent form submission sementara

                checkDuplicatePhone(phoneNumber).then(isDuplicate => {
                    if (isDuplicate) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Dapat Mendaftar',
                            text: 'Nomor HP ini sudah terdaftar dalam sistem. Silakan gunakan nomor lain.',
                            timer: 5000
                        });

                        phoneInput.value = '';
                        phoneInput.focus();
                    } else {
                        // Jika tidak duplicate, submit form
                        e.target.submit();
                    }
                }).catch(error => {
                    console.error('Error checking phone:', error);
                    e.target.submit(); // Submit anyway jika error
                });
            }
        });
    </script>

    <script>
        // Fungsi untuk menampilkan modal privacy policy
        function showPrivacyPolicy() {
            document.getElementById('privacyModal').style.display = 'block';
        }

        // Fungsi untuk menutup modal privacy policy
        function closePrivacyModal() {
            document.getElementById('privacyModal').style.display = 'none';
        }

        // Mengatur status tombol submit berdasarkan checkbox
        document.getElementById('privacyPolicy').addEventListener('change', function() {
            const submitButton = document.getElementById('submitButton');
            if (this.checked) {
                submitButton.disabled = false;
                submitButton.classList.remove('btn-disabled');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('btn-disabled');
            }
        });

        // Validasi form sebelum submit
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const privacyCheckbox = document.getElementById('privacyPolicy');

            if (!privacyCheckbox.checked) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Persetujuan Diperlukan',
                    text: 'Anda harus menyetujui Kebijakan Privasi sebelum mendaftar',
                    timer: 5000
                });
            }
        });

        // Tutup modal jika klik di luar konten
        window.onclick = function(event) {
            const privacyModal = document.getElementById('privacyModal');
            if (event.target == privacyModal) {
                privacyModal.style.display = 'none';
            }

            const registerModal = document.getElementById('registerModal');
            if (event.target == registerModal) {
                registerModal.style.display = 'none';
            }
        }
    </script>

</body>

</html>
