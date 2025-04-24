<!-- @extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Histori Aktivitas Pengguna</h4>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>IP</th>
                    <th>User Agent</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->user ? $log->user->name : 'Guest' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->user_agent }}</td>
                        <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</div>
@endsection -->
