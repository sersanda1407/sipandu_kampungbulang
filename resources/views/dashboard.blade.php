<title>Dashboard</title>

@extends('layouts.master')




@section('master')
    <section>
        <div class="container-fluid">
            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
            @hasrole('superadmin')
            <div class="page-content mt-4">
                {{-- <section class="row"> --}}
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rw') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data RW</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRw::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rt') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data RT</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRt::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/kk') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data KK</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataKk::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/penduduk') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Warga</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataPenduduk::count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h4>Pertambahan warga setiap bulan</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="warga"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div>
                            <div class="col-12 col-lg-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h4>Usia</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="usia"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col-12 col-lg-12">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h4>Gender</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="gender"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endhasrole

            @hasrole('rw|rt|warga')
            <div class="page-content mt-4">

            <h1>Selamat Datang, {{ Auth::check() ? Auth::user()->name : 'User' }}!</h1>
            <h3>Ada Perlu apa hari ini ?</h3>
            @hasrole('rw')
            <p>Cek data di Lingkungan RW {{ \App\DataRw::where('user_id', Auth::id())->value('rw') ?? '' }}, Kelurahan Kampung Bulang</p>
            @endhasrole

            @hasrole('rt')
            <p>Cek data di Lingkungan RT {{ \App\DataRt::where('user_id', Auth::id())->value('rt') ?? '' }} / RW {{ \App\DataRt::where('user_id', Auth::id())->value('rw_id') ?? '' }} , Kelurahan Kampung Bulang</p>
            @endhasrole

            @hasrole('rw')
            <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/rt') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold"> Data RT</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataRt::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    
              
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/kk') }}">
                        <div class="card shadow">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="fas fa-address-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Data KK</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataKk::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <a href="{{ url('/penduduk') }}">
                        <div class="card shadow">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Warga</h6>
                                        <h6 class="font-extrabold mb-0">{{ \App\DataPenduduk::where('rw_id', \App\DataRw::where('user_id', Auth::id())->value('id'))->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                @endhasrole

                @hasrole('rt')
@php
    $rt = \App\DataRt::where('user_id', Auth::id())->first();
@endphp

@if ($rt)
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <a href="{{ url('/kk') }}">
            <div class="card shadow">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="fas fa-address-card"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Data KK</h6>
                            <h6 class="font-extrabold mb-0">
                                {{ \App\DataKk::where('rt_id', $rt->id)->count() }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <a href="{{ url('/penduduk') }}">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Warga</h6>
                            <h6 class="font-extrabold mb-0">
                                {{ \App\DataPenduduk::where('rt_id', $rt->id)->count() }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endif
@endhasrole


     
            </div>
            @endhasrole
        </div>

    </section>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    {{-- CHART GENDER --}}
    <script>
        const data = {
            labels: [
                'Laki-Laki',
                'Perempuan'
            ],
            datasets: [{
                label: 'Gender',
                data: [{{ $gender_laki }}, {{ $gender_cewe }}],
                backgroundColor: [
                    'rgb(87, 202, 235)',
                    'rgb(255, 121, 118)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        };

        const chartGender = new Chart(
            document.getElementById('gender'),
            config
        );
    </script>
    {{-- CHART GENDER --}}

    {{-- CHART USIA --}}
    <script>
        const data_usia = {
            labels: [
                'Dewasa',
                'Anak-anak'
            ],
            datasets: [{
                label: 'Usia',
                data: [{{ $dewasa }}, {{ $anak_anak }}],
                backgroundColor: [
                    'rgb(87, 202, 235)',
                    'rgb(255, 121, 118)'
                ],
                hoverOffset: 4
            }]
        };

        const usia = {
            type: 'bar',
            data: data_usia,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        };

        const chartUsia = new Chart(
            document.getElementById('usia'),
            usia
        );
    </script>
    {{-- CHART USIA --}}

    {{-- CHART PENDUDUK --}}
    <script>
        const datawarga = {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                'November',
                'Desember'
            ],
            
            datasets: [{
                label: 'Warga',
                data: [
                    @foreach ($data_month as $data)
                        {{ $data }},
                    @endforeach
                ],
                fill: true,
                borderColor: 'rgb(86, 182, 247)',
                tension: 0.3
            }]
        };

        const warga = {
            type: 'line',
            data: datawarga,
            options: {
                responsive: true,
            }
        };

        const chartWarga = new Chart(
            document.getElementById('warga'),
            warga
        );
    </script>
    {{-- CHART PENDUDUK --}}
@endsection
