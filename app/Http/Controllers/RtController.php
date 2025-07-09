<?php

namespace App\Http\Controllers;

use App\DataRt;
use App\DataRw;
use App\User;
use App\Lurah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class RtController extends Controller
{
    public function index()
    {
        /** @var \App\User $user */
        
        $user = Auth::user();
        $rw = DataRw::where('user_id', $user->id)->first();
        $rt = DataRt::where('user_id', $user->id)->first();

        if ($user->hasRole('rw')) {
            $data = DataRt::where('rw_id', '=', $user->Rw[0]->id)
                ->orderBy('rt', 'asc')
                ->get();
        } else {
            $data = DataRt::join('rw', 'rt.rw_id', '=', 'rw.id')
                ->orderBy('rw.rw', 'asc')
                ->orderBy('rt.rt', 'asc')
                ->select('rt.*')
                ->get();
        }

        $lurah = Lurah::first();

        $select = DataRw::get();
        return view('rt.index', compact(['data', 'select', 'user', 'lurah']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => ['required', 'digits_between:8,12'],
            'rt' => 'required',
            'alamat_rt' => 'required',
            'gmail_rt' => 'required',
            'rw_id' => 'required',
            'image_rt' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'periode_awal' => 'required',
            'periode_akhir' => 'required'
        ]);

        // ❗ Cek duplikat RT di RW yang sama
        if (DataRt::where('rt', $request->rt)->where('rw_id', $request->rw_id)->exists()) {
            Alert::error('Gagal!', 'RT ' . $request->rt . ' sudah terdaftar di RW tersebut.');
            return redirect()->back()->withInput();
        }

        // ❗ Cek duplikat no_hp
        if (DataRt::where('no_hp', $request->no_hp)->exists()) {
            Alert::error('Gagal!', 'Nomor HP sudah digunakan.');
            return redirect()->back()->withInput();
        }

        try {
            $dataRw = DataRw::findOrFail($request->rw_id);

            // Buat user untuk Ketua RT
            $rtUser = User::create([
                'name' => $request->nama,
                'email' => 'ketua-rt' . $request->rt . '.' . $dataRw->rw . '@kampungbulang',
                'password' => bcrypt('password'),
            ]);

            // Simpan data RT
            $data = new DataRt();
            $data->nama = $request->nama;
            $data->no_hp = $request->no_hp;
            $data->rt = $request->rt;
            $data->alamat_rt = $request->alamat_rt;
            $data->gmail_rt = $request->gmail_rt;
            $data->rw_id = $request->rw_id;
            $data->periode_awal = $request->periode_awal;
            $data->periode_akhir = $request->periode_akhir;
            $data->user_id = $rtUser->id;

            // Upload gambar RT
            if ($request->hasFile('image_rt')) {
                $img = $request->file('image_rt');
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
                $img->storeAs('foto_rt', $filename, 'public');
                $data->image_rt = $filename;
            }

            $data->save();
            $rtUser->assignRole('rt');

            Alert::success('Sukses!', 'Berhasil menambah Data RT');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal!', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function show($encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $data = DataRt::with('user')->findOrFail($id);

        return view('rt.showRT', compact('data'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = DataRt::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'no_hp' => ['required', 'digits_between:8,12'],
            'rt' => 'required',
            'alamat_rt' => 'required',
            'gmail_rt' => 'required',
            'rw_id' => 'required',
            'image_rt' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        // ❗ Cek jika no_hp diubah dan sudah ada di data lain
        if ($request->no_hp != $data->no_hp) {
            if (DataRt::where('no_hp', $request->no_hp)->exists()) {
                Alert::error('Gagal!', 'Nomor Handphone sudah digunakan oleh data lain.');
                return redirect()->back()->withInput();
            }
        }

        // ❗ Cek jika rt diubah dan sudah ada di RW yang sama
        if ($request->rt != $data->rt || $request->rw_id != $data->rw_id) {
            $exists = DataRt::where('rt', $request->rt)
                ->where('rw_id', $request->rw_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                Alert::error('Gagal!', 'RT ' . $request->rt . ' sudah digunakan di RW tersebut.');
                return redirect()->back()->withInput();
            }
        }

        // ❗ Ganti gambar jika ada yang baru
        if ($request->hasFile('image_rt')) {
            if ($data->image_rt) {
                Storage::disk('public')->delete('foto_rt/' . $data->image_rt);
            }

            $img = $request->file('image_rt');
            $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('foto_rt', $filename, 'public');
            $data->image_rt = $filename;
        }

        // Update data RT
        $data->nama = $request->nama;
        $data->no_hp = $request->no_hp;
        $data->rt = $request->rt;
        $data->alamat_rt = $request->alamat_rt;
        $data->gmail_rt = $request->gmail_rt;
        $data->rw_id = $request->rw_id;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->save();

        // Update User jika ada
        if ($data->user_id) {
            $dataRw = DataRw::find($request->rw_id);
            User::where('id', $data->user_id)->update([
                'name' => $request->nama,
                'email' => 'ketua-rt' . $request->rt . '.' . $dataRw->rw . '@kampungbulang',
            ]);
        }

        Alert::success('Sukses!', 'Berhasil mengedit Data RT');
        return redirect()->route('rt.index');
    }


    public function destroy($id)
    {
        $data = DataRt::find($id);
        User::where('id', '=', $data->user_id)->delete();
        $data->delete();

        Alert::Success('Sukses!', 'Berhasil menghapus Data RT');
        return redirect()->route('rt.index');
    }

    public function resetPassword($id)
    {
        $data = DataRt::findOrFail($id);

        if ($data->user_id) {
            User::where('id', $data->user_id)->update([
                'password' => bcrypt('password'),
            ]);

            Alert::success('Sukses!', 'Password berhasil direset ke: password');
        } else {
            Alert::error('Gagal!', 'User RT tidak ditemukan.');
        }

        return redirect()->route('rt.index');
    }


}
