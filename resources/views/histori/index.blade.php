@extends('layouts.master')

@section('master')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>History Log Sistem</h3>
                <p class="text-subtitle text-muted">Rekaman aktivitas sistem SIPANDU</p>
            </div>
            {{-- <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">History Log</li>
                    </ol>
                </nav>
            </div> --}}
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Aktivitas Sistem</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->user_name }}</td>
                                <td>
                                    @if($log->activity_type == 'login')
                                        <span class="badge bg-success">Login</span>
                                    @elseif($log->activity_type == 'logout')
                                        <span class="badge bg-warning">Logout</span>
                                    @elseif($log->activity_type == 'create')
                                        <span class="badge bg-info">Tambah Data</span>
                                    @elseif($log->activity_type == 'update')
                                        <span class="badge bg-primary">Edit Data</span>
                                    @elseif($log->activity_type == 'delete')
                                        <span class="badge bg-danger">Hapus Data</span>
                                    @elseif($log->activity_type == 'verification')
                                        <span class="badge bg-secondary">Verifikasi</span>
                                    @else
                                        <span class="badge bg-dark">{{ $log->activity_type }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data log</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Inisialisasi datatable
    let table1 = document.querySelector('#table1');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
@endsection