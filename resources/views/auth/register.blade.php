@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f3f4f6;
    }

    .register-modal {
        max-width: 600px;
        margin: 4rem auto;
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
        font-size: 1.5rem;
        color: #198754;
    }
</style>

<div class="register-modal">
    <div class="logo-center">
        <img src="{{ asset('assets/images/logo/logo_sipandu.webp') }}" alt="Logo SIPANDU" loading="lazy">
        <h1>SIPANDU</h1>
        <p>Sistem Informasi Pendataan Penduduk Terpadu</p>
    </div>

    <h3 class="text-center fw-bold mb-4">Pendaftaran Akun Warga</h3>

    <form method="POST" action="{{ route('kk.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama Kepala Keluarga</label>
            <input type="text" name="kepala_keluarga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nomor Kartu Keluarga (No. KK)</label>
            <input type="text" name="no_kk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>RT</label>
                <select name="rt_id" class="form-select" required>
                    <option value="">-- Pilih RT --</option>
                    @foreach ($selectRt as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>RW</label>
                <select name="rw_id" class="form-select" required>
                    <option value="">-- Pilih RW --</option>
                    @foreach ($selectRw as $rw)
                        <option value="{{ $rw->id }}">{{ $rw->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

       <div class="mb-3">
    <label>Upload Foto KK</label>
    <input type="file" name="image" class="form-control upload-gambar" accept="image/*" required>
</div>


        <div class="alert alert-info text-sm">
            Setelah mendaftar, akun akan dibuat otomatis.<br>
            <strong>Username:</strong> Nomor KK<br>
            <strong>Password (default):</strong> password
        </div>

        <button type="submit" class="btn btn-success w-100">Daftar</button>

        <p class="text-center mt-3 mb-0">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none text-primary">Login di sini</a>
        </p>
    </form>
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
                    title: 'Format tidak didukung',
                    text: 'Hanya file gambar yang diperbolehkan! (jpg, jpeg, png, gif, webp)'
                });
                this.value = '';
            }
        });
    });
</script>

@endsection
