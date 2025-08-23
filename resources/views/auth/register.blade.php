@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f3f4f6;
        font-size: 16px; /* Font lebih besar untuk readability */
    }

    .register-modal {
        max-width: 600px;
        margin: 2rem auto;
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.4s ease;
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .logo-center {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .logo-center img {
        height: 60px;
    }

    .logo-center h1 {
        font-weight: 700;
        margin-top: 0.5rem;
        font-size: 1.8rem;
        color: #198754;
    }

    .form-control {
        font-size: 16px;
        padding: 12px;
    }
    
    .btn {
        font-size: 18px;
        padding: 12px;
    }
    
    .alert-info {
        font-size: 16px;
        line-height: 1.6;
    }
    
    .whatsapp-info {
        background-color: #25D366;
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        display: none; /* Awalnya disembunyikan */
    }
</style>

<div class="register-modal">
    <div class="logo-center">
        <img src="{{ asset('assets/images/logo/logo_sipandu.webp') }}" alt="Logo SIPANDU" loading="lazy">
        <h1>SIPANDU</h1>
        <p>Sistem Informasi Pendataan Penduduk Terpadu</p>
    </div>

    <h3 class="text-center fw-bold mb-4">Pendaftaran Akun Warga</h3>

    <form method="POST" action="{{ route('kk.store') }}" enctype="multipart/form-data" id="registrationForm">
        @csrf

        <div class="mb-3">
            <label class="fw-bold">Nama Kepala Keluarga</label>
            <input type="text" name="kepala_keluarga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Nomor Kartu Keluarga (No. KK)</label>
            <input type="text" name="no_kk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="fw-bold">RT</label>
                <select name="rt_id" class="form-select" required>
                    <option value="">-- Pilih RT --</option>
                    @foreach ($selectRt as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">RW</label>
                <select name="rw_id" class="form-select" required>
                    <option value="">-- Pilih RW --</option>
                    @foreach ($selectRw as $rw)
                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- TAMBAHKAN FIELD NOMOR TELEPON INI -->
        <div class="mb-3">
            <label class="fw-bold">Nomor Telepon/WhatsApp</label>
            <input type="text" name="no_telp" class="form-control" required 
                   placeholder="Contoh: 081234567890" pattern="[0-9]{10,13}">
            <small class="text-muted">Nomor ini akan digunakan untuk notifikasi WhatsApp</small>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Upload Foto KK</label>
            <input type="file" name="image" class="form-control upload-gambar" accept="image/*" required>
            <small class="text-muted">Maksimal ukuran file: 3MB. Format: JPG, PNG, GIF, SVG, WebP</small>
        </div>

        <div class="alert alert-info text-sm">
            <strong>Informasi Penting:</strong><br>
            Setelah mendaftar, akun akan dibuat otomatis.<br>
            <strong>Username:</strong> Nomor KK<br>
            <strong>Password (default):</strong> password<br>
            <span class="fw-bold">Notifikasi konfirmasi akan dikirim via WhatsApp.</span>
        </div>

        <button type="submit" class="btn btn-success w-100 py-3">
            <i class="fas fa-user-plus"></i> Daftar Sekarang
        </button>

        <p class="text-center mt-3 mb-0">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none text-primary">Login di sini</a>
        </p>
    </form>
    
    <!-- Info WhatsApp -->
    <div class="whatsapp-info mt-4" id="whatsappInfo">
        <h5><i class="fab fa-whatsapp"></i> Notifikasi WhatsApp</h5>
        <p>Notifikasi telah dikirim ke nomor WhatsApp Anda. Silakan cek pesan WhatsApp untuk informasi lebih lanjut.</p>
    </div>
</div>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.upload-gambar').forEach(function(input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            const maxSize = 3 * 1024 * 1024; // 3MB
            
            if (file && !allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak didukung',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png, gif, webp)'
                });
                this.value = '';
            } else if (file && file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar',
                    text: 'Ukuran file maksimal adalah 3MB. Silakan pilih file yang lebih kecil.'
                });
                this.value = '';
            }
        });
    });

    // Validasi form
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const phoneInput = document.querySelector('input[name="no_telp"]');
        const phoneValue = phoneInput.value.replace(/\D/g, ''); // Hanya angka
        
        if (phoneValue.length < 10 || phoneValue.length > 13) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Nomor Telepon Tidak Valid',
                text: 'Nomor telepon harus antara 10-13 digit angka'
            });
            return false;
        }
        
        // Format nomor ke angka saja
        phoneInput.value = phoneValue;
    });

    // Tampilkan info WhatsApp jika ada session success
    @if(session('success'))
    document.getElementById('whatsappInfo').style.display = 'block';
    @endif
</script>

@endsection