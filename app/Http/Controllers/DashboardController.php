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
    public function index()
    {
        $lurah = Lurah::first();
        $this_year = Carbon::now()->format('Y');
        $chart = DataPenduduk::where('created_at', 'like', $this_year . '%')->get();
    
        $gender_laki = DataPenduduk::where('gender', 'like', '%Laki-laki%')->count();
        $gender_cewe = DataPenduduk::where('gender', 'like', '%Perempuan%')->count();
    
        for ($i = 1; $i <= 12; $i++) {
            $data_month[(int)$i] = 0;
        }
    
        foreach ($chart as $c) {
            $check = explode('-', $c->created_at)[1];
            $data_month[(int)$check] += 1;
        }
    
        // Logika usia
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
            if (!$data->tgl_lahir) continue; // pastikan ada tanggal lahir
    
            $umur = Carbon::parse($data->tgl_lahir)->diffInMonths($now);
    
            if ($umur <= 12) {
                $usia_counts['newborn']++;
            } elseif ($umur > 12 && $umur <= 36) {
                $usia_counts['batita']++;
            } elseif ($umur > 36 && $umur <= 60) {
                $usia_counts['balita']++;
            } elseif ($umur > 60 && $umur <= 180) {
                $usia_counts['anak_anak']++;
            } elseif ($umur > 180 && $umur <= 240) {
                $usia_counts['remaja']++;
            } else {
                $usia_counts['dewasa']++;
            }
        }
    
        return view('dashboard', compact(
            'data_month',
            'gender_laki',
            'gender_cewe',
            'lurah',
            'usia_counts'
        ));
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