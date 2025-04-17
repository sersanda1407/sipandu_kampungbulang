<!DOCTYPE html>
<html>
<head>
	<title>Data Warga</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>Data Warga</h4>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kelamin</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>RW / RT</th>
                <th>Agama</th>
                <th>Tempat & Tanggal Lahir</th>
                <th>Usia</th>
                <th>Status Keluarga</th>
                <th>Status Ekonomi</th>
                <th>Pekerjaan</th>
                <th>Status Pernikahan</th>
			</tr>
		</thead>
		<tbody>
			{{-- @php $i=1 @endphp --}}
            @foreach ($penduduk as $pd)
			<tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pd->nama }}</td>
                <td>{{ $pd->gender }}</td>
                <td>{{ $pd->nik }}</td>
                <td>{{ $pd->alamat }}</td>
                <td>{{ $pd->rw->rw }} / {{ $pd->rt->rt }}</td>
                <td>{{ $pd->agama }}</td>
                <td>{{ $pd->tmp_lahir }},
                    {{ Carbon\Carbon::parse($pd->tgl_lahir)->format('d-m-Y') }}</td>
                <td>{{ $pd->usia }}</td>
                <td>{{ $pd->status_keluarga }}</td>
                <td>{{ $pd->kk->status_ekonomi }}</td>
                <td>{{ $pd->pekerjaan }}</td>
                <td>{{ $pd->status_pernikahan }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>