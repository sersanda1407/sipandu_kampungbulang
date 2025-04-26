<?php

namespace App\Http\Controllers;

use App\DataRt;
use App\DataRw;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RtController extends Controller
{
    public function index()
    {
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

        $select = DataRw::get();
        return view('rt.index', compact(['data', 'select', 'user']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $dataRt = DataRw::where('id', $request->rw_id)->get();

        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => 'required',
            'rt' => 'required',
            'rw_id' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required'
        ]);

        // ❗ Cek duplikat RT di RW yang sama
        if (DataRt::where('rt', $request->rt)->where('rw_id', $request->rw_id)->exists()) {
            Alert::error('Gagal!', 'RT ' . $request->rt . ' sudah terdaftar di RW tersebut.');
            return redirect()->back();
        }

        // ❗ Cek duplikat no_hp
        if (DataRt::where('no_hp', $request->no_hp)->exists()) {
            Alert::error('Gagal!', 'Nomor HP sudah digunakan.');
            return redirect()->back();
        }

        $rt = User::create([
            'name' => $request->nama,
            'email' => 'ketua-rt' . $request->rt . '.' . $dataRt[0]->rw . '@kampungbulang',
            'password' => bcrypt('password'),
        ]);

        $data = new DataRt();
        $data->nama = $request->nama;
        $data->no_hp = $request->no_hp;
        $data->rt = $request->rt;
        $data->rw_id = $request->rw_id;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->user_id = $rt->id;
        $data->save();

        $rt->assignRole('rt');

        Alert::success('Sukses!', 'Berhasil menambah Data RT');
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = DataRt::where('id', $id)->first();

        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'rt' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
            'rw_id' => 'required',
        ]);

        // ❗ Cek duplikat RT di RW yang sama selain dirinya sendiri
        $duplicate = DataRt::where('rt', $request->rt)
            ->where('rw_id', $request->rw_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            Alert::error('Gagal!', 'RT ' . $request->rt . ' sudah digunakan di RW tersebut.');
            return redirect()->back();
        }

        // ❗ Cek duplikat no_hp selain dirinya sendiri
        $duplicateHp = DataRt::where('no_hp', $request->no_hp)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicateHp) {
            Alert::error('Gagal!', 'Nomor HP sudah digunakan oleh RT lain.');
            return redirect()->back();
        }

        $data->nama = $request->nama;
        $data->no_hp = $request->no_hp;
        $data->rt = $request->rt;
        $data->rw_id = $request->rw_id;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->update();

        $rt = User::where('id', $data->user_id)->update([
            'name' => $request->nama,
            'email' => 'ketua-rt' . $request->rt . '.' . $data->rw->rw . '@kampungbulang',
        ]);

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
