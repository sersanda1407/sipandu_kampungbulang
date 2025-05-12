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
        $list_tahun = range(2020, date('Y')); // Bisa diatur dinamis

        $data_month = $this->getDataPertambahanWargaPerBulan($tahun_terpilih);

        $lurah = Lurah::first();
        $this_year = Carbon::now()->format('Y');
        $chart = DataPenduduk::whereYear('created_at', $this_year)->get();

        $gender_laki = DataPenduduk::where('gender', 'like', '%Laki-laki%')->count();
        $gender_cewe = DataPenduduk::where('gender', 'like', '%Perempuan%')->count();


        // Usia
        $now = Carbon::now();
        $usia_counts = [
            'newborn' => 0,
            'batita' => 0,
            'balita' => 0,
            'anak_anak' => 0,
            'remaja' => 0,
            'dewasa' => 0
        ];
        $dataPenduduk = DataPenduduk::all();
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

        $data_pekerjaan = DataPenduduk::whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->select('pekerjaan', \DB::raw('count(*) as total'))
            ->groupBy('pekerjaan')
            ->pluck('total', 'pekerjaan')
            ->toArray();


        $data_pernikahan = DataPenduduk::whereNotNull('status_pernikahan')
            ->select('status_pernikahan', \DB::raw('count(*) as total'))
            ->groupBy('status_pernikahan')
            ->pluck('total', 'status_pernikahan')
            ->toArray();


        // Status Ekonomi Berdasarkan Penghasilan
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
            'data_month',
            'tahun_terpilih',
            'list_tahun',
        ));
    }

    private function getDataPertambahanWargaPerBulan($tahun)
    {
        $data = DataPenduduk::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Susun dari Januari-Desember
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i - 1] = $data[$i] ?? 0;
        }

        return $result;
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