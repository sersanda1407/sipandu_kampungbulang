<title>SIPANDU - Dashboard</title>

@extends('layouts.master')


@section('master')
    <section>
        <div class="container-fluid">
            <div class="page-heading d-flex justify-content-between align-items-center">
                <h3>Dashboard</h3>
            </div>


            @hasrole('rw|rt|warga')
            <div class="page-content mt-4">

                <h1>Selamat Datang, {{ Auth::check() ? Auth::user()->name : 'User' }}!</h1>
                <h3>Ada Perlu apa hari ini ?</h3>

                @hasrole('warga')
                @php
                    $warga = \App\DataKK::where('user_id', Auth::id())->first();
                    $rt = $warga ? \App\DataRt::find($warga->rt_id) : null;
                    $rw = $rt ? \App\DataRw::find($rt->rw_id) : null;
                    $user = Auth::user();
                @endphp

                @if($warga && $rt && $rw)
                    <div class="card shadow mt-4" style="border-radius: 12px;">
                        <div class="card-body">
                            <h5 class="mb-4" style="font-weight: 600; color: #333;">Kontak Penting</h5>

                            {{-- Ketua RT --}}
                            <div class="mb-4 row align-items-start">
                                <div class="col-md-9">
                                    <h6 class="text-muted mb-1">Ketua RT {{ $rt->rt ?? '-' }} Kampung Bulang</h6>
                                    <p class="mb-0">Nama: <strong>{{ $rt->nama ?? '-' }}</strong></p>
                                    <p>No HP:
                                        @if($rt && $rt->no_hp)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $rt->no_hp) }}" target="_blank">
                                                <strong>{{ $rt->no_hp }}</strong>
                                            </a>
                                        @else
                                            <strong>-</strong>
                                        @endif
                                    </p>

                                    <div class="mt-3">
                                        <label for="keperluan" class="form-label">Ada yang mau diurus?</label>
                                        <select class="form-select" id="keperluan" onchange="toggleInput(this)">
                                            <option value="" selected>Pilih keperluan...</option>
                                            <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                                            <option value="Pendaftaran/Perubahan KTP">Pendaftaran/Perubahan KK</option>
                                            <option value="Pengurusan KTP Baru">Pengurusan KTP Baru</option>
                                            <option value="Izin Keramaian">Izin Keramaian</option>
                                            <option value="Pelaporan Warga Baru">Pelaporan Warga Baru</option>
                                            <option value="Pelaporan Warga Meninggal">Pelaporan Warga Meninggal</option>
                                            <option value="lainnya">Lainnya...</option>
                                        </select>
                                    </div>
                                    <div class="mt-3" id="keperluan-lainnya" style="display: none;">
                                        <label for="keperluan_custom" class="form-label">Tuliskan keperluan Anda:</label>
                                        <input type="text" class="form-control" id="keperluan_custom"
                                            placeholder="Masukkan keperluan lainnya">
                                    </div>

                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" onclick="bukaModal()">Lanjutkan</button>
                                    </div>

                                </div>
                                <div class="col-md-3 mt-3 mt-md-0 text-md-end text-center">
                                    @if($rt && $rt->image_rt)
                                        {{-- Cek apakah isinya berupa base64 atau URL langsung dari database --}}
                                        @if(Str::startsWith($rt->image_rt, ['data:image', 'http', 'https']))
                                            <img src="{{ $rt->image_rt }}" alt="Foto Ketua RT" class="img-fluid rounded shadow-sm"
                                                style="width: 150px; height: 200px; object-fit: cover;">
                                        @elseif(file_exists(public_path('storage/foto_rt/' . $rt->image_rt)))
                                            {{-- Fallback: ambil dari storage jika nama file tersimpan --}}
                                            <img src="{{ asset('storage/foto_rt/' . $rt->image_rt) }}" alt="Foto Ketua RT"
                                                class="img-fluid rounded shadow-sm"
                                                style="width: 150px; height: 200px; object-fit: cover;">
                                        @else
                                            <p class="text-muted">Foto Ketua RT tidak ditemukan.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">Foto Ketua RT belum tersedia.</p>
                                    @endif
                                </div>

                            </div>

                            {{-- Modal Konfirmasi --}}
                            <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body" id="modalBodyContent"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-primary" id="btnKirimWA">Kirim</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- Ketua RW --}}
                            <div class="row align-items-start">
                                <div class="col-md-9">
                                    <h6 class="text-muted mb-1">Ketua RW {{ $rw->rw ?? '-' }} Kampung Bulang</h6>
                                    <p class="mb-0">Nama: <strong>{{ $rw->nama ?? '-' }}</strong></p>
                                    <p>No HP:
                                        @if($rw && $rw->no_hp)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $rw->no_hp) }}" target="_blank">
                                                <strong>{{ $rw->no_hp }}</strong>
                                            </a>
                                        @else
                                            <strong>-</strong>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-3 mt-3 mt-md-0 text-md-end text-center">
                                    @if($rw && $rw->image_rw)
                                        @if(Str::startsWith($rw->image_rw, ['data:image', 'http', 'https']))
                                            <img src="{{ $rw->image_rw }}" alt="Foto Ketua RW" class="img-fluid rounded shadow-sm"
                                                style="width: 150px; height: 200px; object-fit: cover;">
                                        @elseif(file_exists(public_path('storage/foto_rw/' . $rw->image_rw)))
                                            <img src="{{ asset('storage/foto_rw/' . $rw->image_rw) }}" alt="Foto Ketua RW"
                                                class="img-fluid rounded shadow-sm"
                                                style="width: 150px; height: 200px; object-fit: cover;">
                                        @else
                                            <p class="text-muted">Foto Ketua RW tidak ditemukan.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">Foto Ketua RW belum tersedia.</p>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <script>
                    const keperluanSelect = document.getElementById('keperluan');
                    const customInput = document.getElementById('keperluan_custom');
                    const modalBodyContent = document.getElementById('modalBodyContent');
                    const btnKirimWA = document.getElementById('btnKirimWA');
                    const keperluanLainnyaDiv = document.getElementById('keperluan-lainnya');

                    let selectedKeperluan = '';

                    const nama = "{{ Auth::user()->name ?? 'nama' }}";
                    const namaRt = "{{ $rt->nama ?? '' }}";
                    const alamatWarga = "{{ $warga->alamat ?? '' }}";
                    const rtId = "{{ $rt->rt ?? '' }}";
                    const rwId = "{{ $rw->rw ?? '' }}";
                    const noHpRt = "{{ preg_replace('/^0/', '62', $rt->no_hp) }}";

                    // Tampilkan input jika pilih 'lainnya'
                    keperluanSelect.addEventListener('change', function () {
                        if (this.value === 'lainnya') {
                            keperluanLainnyaDiv.style.display = 'block';
                        } else {
                            keperluanLainnyaDiv.style.display = 'none';
                        }
                    });

                    function bukaModal() {
                        const selectedValue = keperluanSelect.value;
                        const customValue = customInput.value.trim();

                        // Validasi input
                        if (!selectedValue) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Keperluan belum dipilih',
                                text: 'Silakan pilih keperluan terlebih dahulu.',
                            });
                            return;
                        }

                        if (selectedValue === 'lainnya') {
                            if (!customValue) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Keperluan belum diisi',
                                    text: 'Silakan tuliskan keperluan Anda terlebih dahulu.',
                                });
                                return;
                            }
                            selectedKeperluan = customValue;
                        } else {
                            selectedKeperluan = selectedValue;
                        }

                        // Tampilkan konfirmasi di modal
                        modalBodyContent.innerHTML = `Anda ingin mengirim pesan ke Ketua RT ${rtId} ${namaRt} untuk mengurus <strong>${selectedKeperluan}</strong>?`;

                        const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                        modal.show();
                    }

                    btnKirimWA.addEventListener('click', function () {
                        const now = new Date();
                        const hour = now.getHours();
                        let greeting = "Selamat pagi";

                        if (hour >= 12 && hour < 15) {
                            greeting = "Selamat siang";
                        } else if (hour >= 15) {
                            greeting = "Selamat sore";
                        }

                        const pesan = `[PESAN DARI APLIKASI SIPANDU]  \n\n\nAssalamualaikum\n${greeting}\n\nPerkenalkan, saya:\nNama : *${nama}*\nAlamat : ${alamatWarga}, RT ${rtId} / RW ${rwId}\nKeperluan : *Ingin mengurus ${selectedKeperluan}*\n\nTerima kasih banyak atas perhatian dan waktunya. Semoga sehat selalu dan dilancarkan segala aktivitasnya. 🙏🏻\n\n\n_*Pesan ini dikirim secara otomatis_`;

                        const url = `https://wa.me/${noHpRt}?text=${encodeURIComponent(pesan)}`;
                        window.open(url, '_blank');
                    });
                </script>

                @endhasrole

            </div>
            @endhasrole
        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection