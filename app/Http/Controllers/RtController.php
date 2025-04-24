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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $user = Auth::user();
    $rw = DataRw::where('user_id', $user->id)->first();
    $rt = DataRt::where('user_id', $user->id)->first();

    if ($user->hasRole('rw')) {
        $data = DataRt::where('rw_id', '=', $user->Rw[0]->id)
            ->orderBy('rt', 'asc') // Urutkan berdasarkan RT
            ->get();
    } else {
        $data = DataRt::join('rw', 'rt.rw_id', '=', 'rw.id')
    ->orderBy('rw.rw', 'asc') // Urutkan RW dulu
    ->orderBy('rt.rt', 'asc') // Lalu RT
    ->select('rt.*') // Penting: ambil kolom dari data_rt saja
    ->get();

    }

    $select = DataRw::get();
    return view('rt.index', compact(['data', 'select', 'user']));
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = DataRt::find($id);
        User::where('id', '=', $data->user_id)->delete();
        // dd($data);
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
