<?php

namespace App\Http\Controllers;

use App\DataKk;
use App\DataPenduduk;
use App\DataRt;
use App\DataRw;
use App\User;
use App\Lurah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Providers\FonnteService;
use Illuminate\Support\Facades\Log;
use App\Helpers\HistoryLogHelper;

class KkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = new FonnteService();
    }

    public function index()
    {
        $user = Auth::user();

        // Superadmin: Lihat semua data yang sudah diterima
        if ($user->hasRole('superadmin')) {
            $data = DataKk::where('verifikasi', 'diterima')->get();

            // RW: Lihat data dari wilayah RW-nya yang sudah diterima
        } elseif ($user->hasRole('rw')) {
            $data = DataKk::where('verifikasi', 'diterima')
                ->where('rw_id', $user->Rw[0]->id)
                ->get();

            // RT: Lihat data dari wilayah RT-nya yang sudah diterima
        } elseif ($user->hasRole('rt')) {
            $data = DataKk::where('verifikasi', 'diterima')
                ->where('rt_id', $user->Rt[0]->id)
                ->get();

            // Warga: hanya lihat data miliknya (tidak perlu cek verifikasi)
        } elseif ($user->hasRole('warga')) {
            $data = DataKk::where('user_id', $user->Kk[0]->user_id)->get();

            // Default: kosongkan
        } else {
            $data = collect(); // atau bisa kasih abort(403)
        }

        $lurah = Lurah::first();
        $selectRt = DataRt::all();
        $selectRw = DataRw::all();

        return view('kk.index', compact(['selectRt', 'selectRw', 'data', 'lurah']));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $selectRt = \App\DataRt::all();
        $selectRw = \App\DataRw::all();
        return view('auth.register', compact('selectRt', 'selectRw'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'kepala_keluarga' => 'required',
            'no_kk' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'rt_id' => 'required',
            'rw_id' => 'required',
            'alamat' => 'required',
        ]);

        try {
            // Membuat User untuk Kepala Keluarga
            $kk = User::create([
                'name' => $request->kepala_keluarga,
                'email' => $request->no_kk,
                'password' => bcrypt('password'),
            ]);

            // Menyimpan data KK
            $data = new DataKk();
            $data->kepala_keluarga = $request->kepala_keluarga;
            $data->no_kk = $request->no_kk;
            $data->rt_id = $request->rt_id;
            $data->rw_id = $request->rw_id;
            $data->user_id = $kk->id;
            $data->alamat = $request->alamat;
            $data->verifikasi = 'diterima';

            // Menyimpan Gambar dengan UUID
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension(); // Menggunakan UUID untuk nama file gambar
                $request->file('image')->storeAs('foto_kk', $filename, 'public'); // Menyimpan gambar ke folder 'foto_kk'
                $data->image = $filename;
            }

            // Menyimpan data KK
            $data->save();

            // Memberikan role 'warga' ke user
            $kk->assignRole('warga');

            // Catat log penambahan data KK
            createHistoryLog('create', 'Menambahkan data KK baru: ' . $request->kepala_keluarga . ' (No. KK: ' . $request->no_kk . ')');

            Alert::success('Sukses!', 'Berhasil menambah kartu keluarga');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                Alert::error('Gagal!', 'No KK sudah terdaftar.');
                return redirect()->back()->withInput();
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan.');
                return redirect()->back()->withInput();
            }
        }
    }


public function storePublic(Request $request)
{
    $this->validate($request, [
        'kepala_keluarga' => 'required',
        'no_kk' => 'required',
        'image' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
        'rt_id' => 'required|integer',
        'rw_id' => 'required|integer',
        'alamat' => 'required',
        'no_telp' => 'required'
    ]);

    try {
        // Membuat User untuk Kepala Keluarga
        $user = User::create([
            'name' => $request->kepala_keluarga,
            'email' => $request->no_kk,
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('warga');

        // Menghubungkan User tersebut ke Kartu Keluarga
        $data = new DataKk([
            'kepala_keluarga' => $request->kepala_keluarga,
            'no_kk' => $request->no_kk,
            'rt_id' => $request->rt_id,
            'rw_id' => $request->rw_id,
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'verifikasi' => 'pending'
        ]);

        // Mengupload Gambar
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $filename = (string) Str::uuid() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('foto_kk', $filename, 'public');
            $data->image = $filename;
        }

        $data->save();

        // ✅ KIRIM NOTIFIKASI WHATSAPP SETELAH DATA DISIMPAN
        $this->sendWhatsAppNotifications($data);

        return redirect()
            ->back()
            ->with('success', 'Pendaftaran berhasil! Notifikasi WhatsApp telah dikirim. Data anda akan segera diverifikasi. Silahkan tunggu 1x24 jam');

    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            return redirect()
                ->back()
                ->with('error', 'No Kartu Keluarga sudah terdaftar di sistem ini. Silakan periksa kembali dan coba lagi.');
        } else {
            Log::error('StorePublic error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    } catch (\Exception $e) {
        Log::error('StorePublic general error: ' . $e->getMessage());
        return redirect()
            ->back()
            ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}

/**
 * Kirim notifikasi WhatsApp untuk registrasi baru
 */
protected function sendWhatsAppNotifications($kk)
{
    try {
        Log::info('Mengirim notifikasi WhatsApp untuk KK: ' . $kk->id);

        // 1. Kirim ke user
        $userResult = $this->fonnteService->sendToUser($kk);
        Log::info('Notifikasi ke user: ' . ($userResult ? 'Berhasil' : 'Gagal'));

        // 2. Kirim ke RT terkait
        $rtResult = $this->fonnteService->sendToRT($kk);
        Log::info('Notifikasi ke RT: ' . ($rtResult ? 'Berhasil' : 'Gagal'));

        // 3. Kirim ke RW terkait
        $rwResult = $this->fonnteService->sendToRW($kk);
        Log::info('Notifikasi ke RW: ' . ($rwResult ? 'Berhasil' : 'Gagal'));

    } catch (\Exception $e) {
        Log::error('WhatsApp notifications error: ' . $e->getMessage());
        // Jangan throw exception, biarkan proses registrasi tetap berhasil
    }
}

    public function resetPassword($id)
    {
        $kk = DataKk::findOrFail($id);
        $user = User::findOrFail($kk->user_id);

        // Reset password ke nilai default
        $user->password = bcrypt('password');
        $user->save();

        // Catat log reset password
        createHistoryLog('update', 'Reset password untuk KK: ' . $kk->kepala_keluarga . ' (No. KK: ' . $kk->no_kk . ')');

        Alert::success('Berhasil!', 'Password berhasil direset ke: password');

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Dekripsi ID terlebih dahulu
            $decryptedId = Crypt::decryptString($id);

            $data = DataKk::findOrFail($decryptedId);
            $penduduk = DataPenduduk::where('kk_id', $data->id)->get();

            return view('kk.showPenduduk', compact(['data', 'penduduk']));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Jika ID tidak valid atau gagal didekripsi
            abort(404, 'Data tidak ditemukan');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $data = DataKk::findOrFail($id);

        $request->validate([
            'kepala_keluarga' => 'required',
            'no_kk' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'rt_id' => 'required',
            'rw_id' => 'required',
            'alamat' => 'required',
        ]);

        // Cek kalau no_kk diinput berbeda dengan yang lama
        if ($request->no_kk != $data->no_kk) {
            // Cari apakah no_kk baru sudah ada di tabel data_kks
            $existing = DataKk::where('no_kk', $request->no_kk)->first();

            if ($existing) {
                // Kalau sudah ada, kasih alert error dan balik
                Alert::error('Gagal!', 'No KK sudah terdaftar, tidak bisa diubah.');
                return redirect()->back()->withInput();
            }
        }

        if ($request->hasFile('image')) {
            if ($data->image) {
                Storage::disk('public')->delete('foto_kk/' . $data->image);
            }

            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('foto_kk', $filename, 'public');

            $data->image = $filename;
        }

        // Update data KK
        $data->kepala_keluarga = $request->kepala_keluarga;
        $data->no_kk = $request->no_kk;
        $data->rt_id = $request->rt_id;
        $data->rw_id = $request->rw_id;
        $data->alamat = $request->alamat;
        $data->save();

        // Update data User kalau ada
        if ($data->user_id) {
            User::where('id', $data->user_id)->update([
                'name' => $request->kepala_keluarga,
                'email' => $request->no_kk,
            ]);
        }

        // Catat log update data KK
        createHistoryLog('update', 'Mengupdate data KK: ' . $request->kepala_keluarga . ' (No. KK: ' . $request->no_kk . ')');

        Alert::success('Sukses!', 'Berhasil mengedit kartu keluarga');
        return redirect()->back();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = DataKk::find($id);
        
        // Catat log sebelum menghapus data
        createHistoryLog('delete', 'Menghapus data KK: ' . $data->kepala_keluarga . ' (No. KK: ' . $data->no_kk . ')');
        
        if ($data->image) {
            Storage::delete('/foto_kk/' . $data->image);
        }

        User::where('id', '=', $data->user_id)->delete();

        $data->delete();

        Alert::Success('Sukses!', 'Berhasil menghapus kartu keluarga');

        return redirect()->back();
    }
}