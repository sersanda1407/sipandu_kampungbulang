<?php

namespace App\Http\Controllers;

use App\DataRw;
use Illuminate\Http\Request;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class RwController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DataRw::get();

        return view('rw.index', compact('data'));
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
            'nama' => 'required',
            'no_hp' => 'required',
            'rw' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $rw = User::create([
            'name' => $request->nama,
            'email' => 'rw' . $request->rw . '@gmail.com',
            'password' =>  bcrypt('password'),

        ]);

        $data = new DataRw();
        $data->nama = $request->nama;
        $data->no_hp = $request->no_hp;
        $data->rw = $request->rw;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->user_id = $rw->id;
        // dd($data);
        $data->save();

        $rw->assignRole('rw');

        Alert::success('Sukses!', 'Berhasil menambah Data RW');

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
        $data = DataRw::where('id', $id)->firstOrFail();

        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'rw' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        $data->nama = $request->nama;
        $data->no_hp = $request->no_hp;
        $data->rw = $request->rw;
        $data->periode_awal = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->update();

        User::where('id', '=', $data->user_id)->update([
            'name' => $request->nama,
            'email' => 'rw' . $request->rw . '@gmail.com',
        ]);

        Alert::success('Sukses!', 'Berhasil mengedit Data RW');

        return redirect()->route('rw.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = DataRw::find($id);

        User::where('id', '=', $data->user_id)->delete();

        $data->delete();

        Alert::Success('Sukses!', 'Berhasil menghapus Data RW');

        return redirect()->route('rw.index');
    }
}
