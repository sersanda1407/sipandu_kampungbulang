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
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Traits\HasRoles;

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

        // Query penduduk dengan filter
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

        $dataPenduduk = $query->whereYear('created_at', $tahun_terpilih)->get();

        // Ambil data pertambahan per bulan
        $data_pertambahan = $this->getDataPertambahanWargaPerBulan($tahun_terpilih, $filter_rw, $filter_rt, $kk_user_id);
        $data_month = $data_pertambahan['bulanan'];
        $total_pertambahan = $data_pertambahan['total'];

        $lurah = Lurah::first();

        // Hitung gender (lebih fleksibel & tahan variasi data)
        $gender_laki = $dataPenduduk->filter(fn($p) => stripos($p->gender, 'laki') !== false)->count();
        $gender_cewe = $dataPenduduk->filter(fn($p) => stripos($p->gender, 'perempuan') !== false)->count();

        // Hitung usia berdasarkan kategori
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

        // Data pekerjaan
        $data_pekerjaan = $dataPenduduk->whereNotNull('pekerjaan')
            ->groupBy('pekerjaan')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Data status pernikahan
        $data_pernikahan = $dataPenduduk->whereNotNull('status_pernikahan')
            ->groupBy('status_pernikahan')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Data status ekonomi
        $data_ekonomi = [
            'Sangat Tidak Mampu' => 0,
            'Tidak Mampu' => 0,
            'Menengah ke Bawah' => 0,
            'Menengah' => 0,
            'Menengah ke Atas' => 0,
            'Mampu' => 0,
        ];

        foreach ($dataPenduduk as $data) {
            if (!$data->jumlah_penghasilan)
                continue;
            $gaji = $data->jumlah_penghasilan;

            if ($gaji < 1000000)
                $data_ekonomi['Sangat Tidak Mampu']++;
            elseif ($gaji < 2000000)
                $data_ekonomi['Tidak Mampu']++;
            elseif ($gaji < 3000000)
                $data_ekonomi['Menengah ke Bawah']++;
            elseif ($gaji < 4000000)
                $data_ekonomi['Menengah']++;
            elseif ($gaji < 5000000)
                $data_ekonomi['Menengah ke Atas']++;
            else
                $data_ekonomi['Mampu']++;
        }

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
            'data_pekerjaan',
            'data_ekonomi',
            'data_pernikahan',
            'dataPenduduk',
            'tahun_terpilih',
            'list_tahun',
            'total_pertambahan',
            'filter_rw',
            'filter_rt',
            'currentYear',
            'data_agama',
            'user'
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

    // Update data user
    $user->name = $request->nama;
    
    // Update password hanya jika diisi
    if (!empty($request->password)) {
        $user->password = bcrypt($request->password);
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

        if ($lurah) {
            $lurah->update($request->only(['nama', 'jabatan', 'nip']));
        } else {
            Lurah::create($request->only(['nama', 'jabatan', 'nip']));
        }

        Alert::success('Sukses!', 'Data Lurah berhasil diperbarui.');
        return redirect()->back();
    }
}