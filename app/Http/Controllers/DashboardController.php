<?php

namespace App\Http\Controllers;

use App\DataPenduduk;
use App\User;
use App\Lurah;
use App\DataRt;
use App\DataRw;
use App\DataKk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Traits\HasRoles;
use App\Helpers\HistoryLogHelper;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun_terpilih = $request->get('tahun', date('Y'));
        $list_tahun = range(2025, date('Y'));
        $currentYear = now()->year;

        // Cek role user dan ambil data yang sesuai
        $user = Auth::user();
        $rw_user_id = null;
        $rt_user_id = null;
        $kk_user_id = null;

        // Cek apakah perlu menampilkan pesan pengingat password
        $showPasswordReminder = false;
        if ($user && $user->is_default_password) {
            $showPasswordReminder = true;
        }

        // Ambil ID RW, RT, atau KK berdasarkan role user
        if ($user->hasRole('rw')) {
            $rw_data = DataRw::where('user_id', $user->id)->first();
            $rw_user_id = $rw_data ? $rw_data->id : null;
        } elseif ($user->hasRole('rt')) {
            $rt_data = DataRt::where('user_id', $user->id)->first();
            $rt_user_id = $rt_data ? $rt_data->id : null;
            $rw_user_id = $rt_data ? $rt_data->rw_id : null;
        } elseif ($user->hasRole('warga')) {
            $kk_data = DataKk::where('user_id', $user->id)->first();
            $kk_user_id = $kk_data ? $kk_data->id : null;
            $rt_user_id = $kk_data ? $kk_data->rt_id : null;
            $rw_user_id = $kk_data ? $kk_data->rw_id : null;
        }

        // Ambil filter dari request
        $filter_rw = $request->get('rw');
        $filter_rt = $request->get('rt');

        // Jika user memiliki akses terbatas, gunakan data mereka
        if ($rw_user_id && !$filter_rw) {
            $filter_rw = $rw_user_id;
        }

        if ($rt_user_id && !$filter_rt) {
            $filter_rt = $rt_user_id;
        }

        // Query penduduk dengan filter - SEMUA DATA TANPA FILTER TAHUN
        $query = DataPenduduk::query();

        if ($filter_rw) {
            $query->where('rw_id', $filter_rw);
        }

        if ($filter_rt) {
            $query->where('rt_id', $filter_rt);
        }

        if ($kk_user_id) {
            $query->where('kk_id', $kk_user_id);
        }

        // Ambil semua data penduduk tanpa filter tahun
        $dataPenduduk = $query->get();

        // Hanya untuk pertambahan warga yang tetap menggunakan filter tahun
        $data_pertambahan = $this->getDataPertambahanWargaPerBulan($tahun_terpilih, $filter_rw, $filter_rt, $kk_user_id);
        $data_month = $data_pertambahan['bulanan'];
        $total_pertambahan = $data_pertambahan['total'];

        $lurah = Lurah::first();

        // Hitung gender (lebih fleksibel & tahan variasi data) - SEMUA DATA
        $gender_laki = $dataPenduduk->filter(fn($p) => stripos($p->gender, 'laki') !== false)->count();
        $gender_cewe = $dataPenduduk->filter(fn($p) => stripos($p->gender, 'perempuan') !== false)->count();

        // Hitung usia berdasarkan kategori - SEMUA DATA
        $now = Carbon::now();
        $usia_counts = [
            'newborn' => 0,
            'batita' => 0,
            'balita' => 0,
            'anak_anak' => 0,
            'remaja' => 0,
            'dewasa' => 0
        ];

        foreach ($dataPenduduk as $data) {
            if (!$data->tgl_lahir)
                continue;
            $umur = Carbon::parse($data->tgl_lahir)->diffInMonths($now);

            if ($umur <= 12)
                $usia_counts['newborn']++;
            elseif ($umur <= 36)
                $usia_counts['batita']++;
            elseif ($umur <= 60)
                $usia_counts['balita']++;
            elseif ($umur <= 180)
                $usia_counts['anak_anak']++;
            elseif ($umur <= 240)
                $usia_counts['remaja']++;
            else
                $usia_counts['dewasa']++;
        }

        $data_pendidikan = $dataPenduduk->whereNotNull('pendidikan')
            ->groupBy('pendidikan')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Data pekerjaan - SEMUA DATA
        $data_pekerjaan = $dataPenduduk->whereNotNull('pekerjaan')
            ->groupBy('pekerjaan')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Data status pernikahan - SEMUA DATA
        $data_pernikahan = $dataPenduduk->whereNotNull('status_pernikahan')
            ->groupBy('status_pernikahan')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Data status ekonomi berdasarkan klasifikasi BPS - SEMUA DATA
        $garisKemiskinan = 595000; // Garis Kemiskinan BPS
        $statusEkonomiBPS = [
            'Miskin' => 0,
            'Rentan Miskin' => 0,
            'Menuju Kelas Menengah' => 0,
            'Kelas Menengah' => 0,
            'Kelas Atas' => 0,
        ];

        // Kelompokkan data per KK - SEMUA DATA
        $kkGroups = $dataPenduduk->groupBy('kk_id');

        foreach ($kkGroups as $kk_id => $anggotaKK) {
            // Hitung rata-rata pendapatan per KK
            $rataRata = $anggotaKK->whereNotNull('jumlah_penghasilan')
                ->pluck('jumlah_penghasilan')
                ->avg();

            if ($rataRata === null) {
                continue; // Skip jika tidak ada data gaji
            }

            // Hitung rasio terhadap garis kemiskinan
            $rasio = $garisKemiskinan > 0 ? $rataRata / $garisKemiskinan : 0;

            // Klasifikasi BPS
            if ($rasio < 1) {
                $status = 'Miskin';
            } elseif ($rasio < 1.5) {
                $status = 'Rentan Miskin';
            } elseif ($rasio < 3.5) {
                $status = 'Menuju Kelas Menengah';
            } elseif ($rasio < 17) {
                $status = 'Kelas Menengah';
            } else {
                $status = 'Kelas Atas';
            }

            $statusEkonomiBPS[$status]++;
        }

        // Data agama - SEMUA DATA
        $data_agama = $dataPenduduk->whereNotNull('agama')
            ->groupBy('agama')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        // Tentukan view berdasarkan role
        if ($user->hasRole('superadmin')) {
            $viewName = 'dashboard.dashboard-admin';
        } elseif ($user->hasRole('rw')) {
            $viewName = 'dashboard.dashboard-rw';
        } elseif ($user->hasRole('rt')) {
            $viewName = 'dashboard.dashboard-rt';
        } elseif ($user->hasRole('warga')) {
            $viewName = 'dashboard.dashboard-warga';
        } else {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view($viewName, compact(
            'data_month',
            'gender_laki',
            'gender_cewe',
            'lurah',
            'usia_counts',
            'data_pendidikan',
            'data_pekerjaan',
            'statusEkonomiBPS',
            'data_pernikahan',
            'dataPenduduk',
            'tahun_terpilih',
            'list_tahun',
            'total_pertambahan',
            'filter_rw',
            'filter_rt',
            'currentYear',
            'data_agama',
            'user',
            'showPasswordReminder'
        ));
    }

    private function getDataPertambahanWargaPerBulan($tahun, $rw_id = null, $rt_id = null, $kk_id = null)
    {
        $query = DataPenduduk::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', $tahun);

        if ($rw_id) {
            $query->where('rw_id', $rw_id);
        }

        if ($rt_id) {
            $query->where('rt_id', $rt_id);
        }

        if ($kk_id) {
            $query->where('kk_id', $kk_id);
        }

        $data = $query->groupBy('bulan')->pluck('jumlah', 'bulan')->toArray();

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i - 1] = $data[$i] ?? 0;
        }

        return [
            'bulanan' => $result,
            'total' => array_sum($result),
        ];
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'nullable|min:6',
            'nama' => 'required|string|max:255',
        ]);

        // Jika user ingin mengubah password
        if ($request->filled('password')) {
            // Cek apakah password sama dengan password default "password"
            if (Hash::check($request->password, bcrypt('password'))) {
                Alert::error('Oops!', 'Tidak boleh menggunakan password akun awal ya.');
                return redirect()->back()->withInput();
            }

            // Cek apakah password sama dengan password saat ini
            if (Hash::check($request->password, $user->password)) {
                Alert::error('Oops!', 'Password baru tidak boleh sama dengan password lama.');
                return redirect()->back()->withInput();
            }
        }

        // Validasi duplikasi no HP untuk RW/RT
        if ($user->hasRole('rw')) {
            $rwData = DataRw::where('user_id', $user->id)->first();
            $request->validate([
                'no_hp' => [
                    'required',
                    'digits_between:8,12',
                    function ($attribute, $value, $fail) use ($user, $rwData) {
                        $currentNoHp = $rwData ? $rwData->no_hp : '';

                        if ($value !== $currentNoHp) {
                            // Cek duplikasi di semua tabel
                            $existsInKk = DataKk::where('no_telp', $value)->exists();
                            $existsInRt = DataRt::where('no_hp', $value)->exists();
                            $existsInRw = DataRw::where('no_hp', $value)
                                ->when($rwData, function($query) use ($rwData) {
                                    return $query->where('id', '!=', $rwData->id);
                                })
                                ->exists();

                            if ($existsInKk || $existsInRt || $existsInRw) {
                                $fail('Nomor HP sudah digunakan oleh pengguna lain.');
                            }
                        }
                    }
                ]
            ]);
        } elseif ($user->hasRole('rt')) {
            $rtData = DataRt::where('user_id', $user->id)->first();
            $request->validate([
                'no_hp' => [
                    'required',
                    'digits_between:8,12',
                    function ($attribute, $value, $fail) use ($user, $rtData) {
                        $currentNoHp = $rtData ? $rtData->no_hp : '';

                        if ($value !== $currentNoHp) {
                            // Cek duplikasi di semua tabel
                            $existsInKk = DataKk::where('no_telp', $value)->exists();
                            $existsInRt = DataRt::where('no_hp', $value)
                                ->when($rtData, function($query) use ($rtData) {
                                    return $query->where('id', '!=', $rtData->id);
                                })
                                ->exists();
                            $existsInRw = DataRw::where('no_hp', $value)->exists();

                            if ($existsInKk || $existsInRt || $existsInRw) {
                                $fail('Nomor HP sudah digunakan oleh pengguna lain.');
                            }
                        }
                    }
                ]
            ]);
        }

        // Validasi duplikasi no telepon untuk warga
        if ($user->hasRole('warga')) {
            $kkData = DataKk::where('user_id', $user->id)->first();
            $request->validate([
                'no_telp' => [
                    'required',
                    'digits_between:8,12',
                    function ($attribute, $value, $fail) use ($user, $kkData) {
                        $currentNoTelp = $kkData ? $kkData->no_telp : '';

                        if ($value !== $currentNoTelp) {
                            // Cek duplikasi di semua tabel
                            $existsInKk = DataKk::where('no_telp', $value)
                                ->when($kkData, function($query) use ($kkData) {
                                    return $query->where('id', '!=', $kkData->id);
                                })
                                ->exists();
                            $existsInRt = DataRt::where('no_hp', $value)->exists();
                            $existsInRw = DataRw::where('no_hp', $value)->exists();

                            if ($existsInKk || $existsInRt || $existsInRw) {
                                $fail('Nomor telepon sudah digunakan oleh pengguna lain.');
                            }
                        }
                    }
                ]
            ]);
        }

        // Simpan data lama untuk log
        $oldName = $user->name;
        $passwordChanged = $request->filled('password');

        // Update data user
        $user->name = $request->nama;

        // Update password jika diisi
        if ($passwordChanged) {
            $user->password = bcrypt($request->password);
            // Tandai bahwa password sudah diubah dari default
            $user->is_default_password = false;
        }

        $user->save();

        // Jika user adalah warga, update data KK terkait
        if ($user->hasRole('warga')) {
            $kkData = DataKk::where('user_id', $user->id)->first();
            if ($kkData) {
                $kkData->kepala_keluarga = $request->nama;
                if ($request->has('alamat')) {
                    $kkData->alamat = $request->alamat;
                }
                if ($request->has('no_telp')) {
                    $kkData->no_telp = $request->no_telp;
                }
                $kkData->save();
            }
        }

        // Jika user adalah RW, update data RW terkait
        if ($user->hasRole('rw')) {
            $rwData = DataRw::where('user_id', $user->id)->first();
            if ($rwData) {
                $rwData->nama = $request->nama;
                if ($request->has('no_hp')) {
                    $rwData->no_hp = $request->no_hp;
                }
                if ($request->has('gmail_rw')) {
                    $rwData->gmail_rw = $request->gmail_rw;
                }
                if ($request->has('alamat_rw')) {
                    $rwData->alamat_rw = $request->alamat_rw;
                }
                $rwData->save();
            }
        }

        // Jika user adalah RT, update data RT terkait
        if ($user->hasRole('rt')) {
            $rtData = DataRt::where('user_id', $user->id)->first();
            if ($rtData) {
                $rtData->nama = $request->nama;
                if ($request->has('no_hp')) {
                    $rtData->no_hp = $request->no_hp;
                }
                if ($request->has('gmail_rt')) {
                    $rtData->gmail_rt = $request->gmail_rt;
                }
                if ($request->has('alamat_rt')) {
                    $rtData->alamat_rt = $request->alamat_rt;
                }
                $rtData->save();
            }
        }

        // Catat log update profile
        $logMessage = 'Memperbarui profil: ' . $oldName . ' → ' . $request->nama;
        if ($passwordChanged) {
            $logMessage .= ' (termasuk perubahan password)';
        }
        createHistoryLog('update', $logMessage);

        Alert::success('Sukses!', 'Berhasil memperbarui data pengguna');
        return redirect()->back();
    }

    public function checkDuplicatenoPhone(Request $request)
    {
        $phone = $request->query('no_telp');

        if (!$phone) {
            return response()->json(['exists' => false, 'message' => 'Nomor telepon tidak diberikan']);
        }

        // Cek di tabel DataKk (kolom 'no_telp')
        $existsInKk = DataKk::where('no_telp', $phone)->exists();

        // Cek di tabel Rt (kolom 'no_hp')
        $existsInRt = DataRt::where('no_hp', $phone)->exists();

        // Cek di tabel Rw (kolom 'no_hp')
        $existsInRw = DataRw::where('no_hp', $phone)->exists();

        // Jika nomor ada di SALAH SATU dari ketiga tabel, dianggap terdaftar
        $exists = $existsInKk || $existsInRt || $existsInRw;

        return response()->json(['exists' => $exists]);
    }

    public function editLurah(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
        ]);

        $lurah = Lurah::first();

        // Simpan data lama untuk log
        $oldData = $lurah ? clone $lurah : null;

        if ($lurah) {
            $lurah->update($request->only(['nama', 'jabatan', 'nip']));
        } else {
            $lurah = Lurah::create($request->only(['nama', 'jabatan', 'nip']));
        }

        // Catat log update data lurah
        if ($oldData) {
            $changes = [];
            if ($oldData->nama !== $lurah->nama) {
                $changes[] = 'Nama: ' . $oldData->nama . ' → ' . $lurah->nama;
            }
            if ($oldData->jabatan !== $lurah->jabatan) {
                $changes[] = 'Jabatan: ' . $oldData->jabatan . ' → ' . $lurah->jabatan;
            }
            if ($oldData->nip !== $lurah->nip) {
                $changes[] = 'NIP: ' . $oldData->nip . ' → ' . $lurah->nip;
            }

            if (!empty($changes)) {
                createHistoryLog('update', 'Memperbarui data Lurah: ' . implode(', ', $changes));
            }
        } else {
            createHistoryLog('create', 'Menambahkan data Lurah: ' . $lurah->nama . ' (' . $lurah->jabatan . ')');
        }

        Alert::success('Sukses!', 'Data Lurah berhasil diperbarui.');
        return redirect()->back();
    }

    public function resetPassword($id)
    {
        $kk = DataKk::findOrFail($id);
        $user = User::findOrFail($kk->user_id);

        // Reset password ke nilai default
        $user->password = bcrypt('password');
        $user->is_default_password = true;
        $user->save();

        // Catat log reset password
        createHistoryLog('update', 'Reset password untuk KK: ' . $kk->kepala_keluarga . ' (No. KK: ' . $kk->no_kk . ')');

        Alert::success('Berhasil!', 'Password berhasil direset ke: password');

        return redirect()->back();
    }
}
