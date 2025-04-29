<?php

namespace App\Http\Controllers;

use App\DataRw;
use Illuminate\Http\Request;
use App\User;
use App\Lurah;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;

class RwController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DataRw::orderByRaw('CAST(rw AS UNSIGNED) ASC')->get();
        $lurah = Lurah::first();
        return view('rw.index', compact('data','lurah'));
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
        // Validasi dasar tanpa unique untuk no_hp
        $request->validate([
            'nama' => 'required',
            'no_hp' => ['required', 'digits_between:8,12'],
            'rw' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);

        // Cek manual untuk no_hp duplicate
        $existingNoHp = DataRw::where('no_hp', $request->no_hp)->first();
        if ($existingNoHp) {
            Alert::error('Gagal!', 'Nomor Handphone sudah digunakan oleh data lain.');
            return redirect()->back()->withInput();
        }

        // Cek manual untuk rw duplicate
        if (DataRw::where('rw', $request->rw)->exists()) {
            Alert::error('Gagal!', 'Nomor RW ' . $request->rw . ' sudah terdaftar.');
            return redirect()->back()->withInput();
        }

        // Bila semua valid, buat user + data RW
        $rw = User::create([
            'name' => $request->nama,
            'email' => 'ketua-rw' . $request->rw . '@kampungbulang',
            'password' => bcrypt('password'),
        ]);

        $data = DataRw::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'rw' => $request->rw,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'user_id' => $rw->id,
        ]);

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
        $data = DataRw::findOrFail($id);
    
        $request->validate([
            'nama'           => 'required',
            'no_hp'          => ['required','digits_between:8,12'],
            'rw'             => 'required',
            'periode_awal'   => 'required',
            'periode_akhir'  => 'required',
        ]);
    
        if (DataRw::where('no_hp', $request->no_hp)
                  ->where('id','!=',$id)->exists()) {
            Alert::error('Gagal!', 'Nomor Handphone sudah digunakan.');
            return redirect()->back()->withInput();
        }
    
        if (DataRw::where('rw', $request->rw)
                  ->where('id','!=',$id)->exists()) {
            Alert::error('Gagal!', 'Nomor RW sudah digunakan.');
            return redirect()->back()->withInput();
        }
    
        // Update DataRw
        $data->update([
            'nama'           => $request->nama,
            'no_hp'          => $request->no_hp,
            'rw'             => $request->rw,
            'periode_awal'   => $request->periode_awal,
            'periode_akhir'  => $request->periode_akhir,
        ]);
    
        // Update User model via instance
        $user = User::find($data->user_id);
        $user->name  = $request->nama;
        $user->email = 'ketua-rw' . $request->rw . '@kampungbulang';
        $user->save();
    
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

   public function resetPassword($id)
{
    // Ambil data RW, atau 404 jika tidak ada
    $data = DataRw::findOrFail($id);

    // Pastikan ada user terkait
    $user = User::find($data->user_id);
    if (!$user) {
        Alert::error('Gagal!', 'User RW tidak ditemukan.');
        return redirect()->route('rw.index');
    }

    // Reset password ke string 'password'
    $user->password = bcrypt('password');
    $user->save();

    Alert::success('Sukses!', 'Password berhasil direset ke: password');

    // Kembalikan ke halaman indeks RW, bukan RT
    return redirect()->route('rw.index');
}

}
