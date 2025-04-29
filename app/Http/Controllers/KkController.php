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



class KkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        // dd($user);

        if ($user->hasRole('rw') == true) {
            $data = DataKk::where('rw_id', '=', $user->Rw[0]->id)->get();
        } elseif ($user->hasRole('rt') == true) {
            $data = DataKk::where('rt_id', $user->Rt[0]->id)->get();
        } elseif ($user->hasRole('warga') == true) {
            $data = DataKk::where('user_id', $user->Kk[0]->user_id)->get();
        } else {
            $data = DataKk::all();
        }
        // $data = DataKk::all();

        $lurah = Lurah::first();

        $selectRt = DataRt::get();
        $selectRw = DataRw::get();
        return view('kk.index', compact(['selectRt', 'selectRw', 'data','lurah']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'image' => 'required|mimes:jpeg,jpg,png,gif,svg|max:3072',
            'rt_id' => 'required',
            'rw_id' => 'required',
            'alamat' => 'required',
            'status_ekonomi' => 'required',
        ]);

        try {
            $kk = User::create([
                'name' => $request->kepala_keluarga,
                'email' => $request->no_kk,
                'password' => bcrypt('password'),
            ]);

            $data = new DataKk();
            $data->kepala_keluarga = $request->kepala_keluarga;
            $data->no_kk = $request->no_kk;
            $data->rt_id = $request->rt_id;
            $data->rw_id = $request->rw_id;
            $data->user_id = $kk->id;
            $data->alamat = $request->alamat;
            $data->status_ekonomi = $request->status_ekonomi;

            $img = $request->file('image');
            $filename = $img->getClientOriginalName();

            $data->image = $filename;
            if ($request->hasFile('image')) {
                $request->file('image')->storeAs('/foto_kk', $filename);
            }

            $data->save();
            $kk->assignRole('warga');

            Alert::success('Sukses!', 'Berhasil menambah kartu keluarga');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Menampilkan pesan No KK sudah terdaftar
                Alert::error('Gagal!', 'No KK sudah terdaftar.');
                return redirect()->back()->withInput();
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan.');
                return redirect()->back()->withInput();
            }
        }
    }


    public function resetPassword($id)
    {
        $kk = DataKk::findOrFail($id);
        $user = User::findOrFail($kk->user_id);

        // Reset password ke nilai default
        $user->password = bcrypt('password');
        $user->save();

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
             'image' => 'nullable|mimes:jpeg,jpg,png,gif,svg|max:3072',
             'rt_id' => 'required',
             'rw_id' => 'required',
             'alamat' => 'required',
             'status_ekonomi' => 'required',
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
         $data->status_ekonomi = $request->status_ekonomi;
         $data->save();
     
         // Update data User kalau ada
         if ($data->user_id) {
             User::where('id', $data->user_id)->update([
                 'name' => $request->kepala_keluarga,
                 'email' => $request->no_kk,
             ]);
         }
     
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
        if ($data->image) {
            Storage::delete('/foto_kk/' . $data->image);
        }

        User::where('id', '=', $data->user_id)->delete();

        $data->delete();

        Alert::Success('Sukses!', 'Berhasil menghapus kartu keluarga');

        return redirect()->back();
    }
}
