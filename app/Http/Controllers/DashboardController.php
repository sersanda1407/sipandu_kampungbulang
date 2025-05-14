<?php

namespace App\Http\Controllers;

use App\DataPenduduk;
use App\User;
use App\Lurah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun_terpilih = $request->get('tahun', date('Y'));
        $list_tahun = range(0001, date('Y'));

        // Cek apakah user adalah RW atau RT (memiliki relasi ke DataRw / relasi ke DataRt)
        $rw_user_id = \App\DataRw::where('user_id', Auth::id())->value('id');
        $rt_user_id = \App\DataRt::where('user_id', Auth::id())->value('id');

        // Ambil filter dari request
        $filter_rw = $request->get('rw');
        $filter_rt = $request->get('rt');

        // Jika user RW, pakai rw_id-nya
        if ($rw_user_id) {
            $filter_rw = $rw_user_id;
        }

        // Jika user RW, pakai rt_id-nya
        if ($rt_user_id) {
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

        $dataPenduduk = $query->get();

        // Ambil data pertambahan per bulan
        $data_pertambahan = $this->getDataPertambahanWargaPerBulan($tahun_terpilih, $filter_rw, $filter_rt);
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

        return view('dashboard', compact(
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
            'filter_rt'
        ));
    }


    private function getDataPertambahanWargaPerBulan($tahun, $rw_id = null, $rt_id = null)
    {
        $query = DataPenduduk::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', $tahun);

        // Cek jika user RW, override rw_id
        $rw_user_id = \App\DataRw::where('user_id', Auth::id())->value('id');
        $rt_user_id = \App\DataRt::where('user_id', Auth::id())->value('id');
        if ($rw_user_id) {
            $rw_id = $rw_user_id;
        }
        if ($rt_user_id) {
            $rt_id = $rt_user_id;
        }

        if ($rw_id) {
            $query->where('rw_id', $rw_id);
        }

        if ($rt_id) {
            $query->where('rt_id', $rt_id);
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
        $users = Auth::user();
        $data = User::where('id', $users->id)->firstOrFail();

        $request->validate([
            'password' => 'required',
        ]);

        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        // dd($data);
        $data->update();

        Alert::success('Sukses!', 'Berhasil mengedit Profile');

        return redirect()->back();
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