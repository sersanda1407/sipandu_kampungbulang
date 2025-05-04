<?php

namespace App\Http\Controllers;

use App\DataRw;
use Illuminate\Http\Request;
use App\User;
use App\Lurah;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => ['required', 'digits_between:8,12'],
            'rw' => 'required',
            'image_rw' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
        ]);
    
        try {
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
    
            // Membuat User untuk Ketua RW
            $rw = User::create([
                'name' => $request->nama,
                'email' => 'ketua-rw' . $request->rw . '@kampungbulang',
                'password' => bcrypt('password'),
            ]);
    
            // Menyimpan data RW
            $data = new DataRw();
            $data->nama = $request->nama;
            $data->no_hp = $request->no_hp;
            $data->rw = $request->rw;
            $data->periode_awal = $request->periode_awal;
            $data->periode_akhir = $request->periode_akhir;
            $data->user_id = $rw->id;
    
            // Menyimpan Gambar dengan UUID
            if ($request->hasFile('image_rw')) {
                $img = $request->file('image_rw');
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension(); // Menggunakan UUID untuk nama file gambar
                $request->file('image_rw')->storeAs('foto_rw', $filename, 'public'); // Menyimpan gambar ke folder 'foto_rw'
                $data->image_rw = $filename;
            }
    
            // Menyimpan data RW
            $data->save();
    
            // Memberikan role 'rw' ke user
            $rw->assignRole('rw');
    
            Alert::success('Sukses!', 'Berhasil menambah Data RW');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                Alert::error('Gagal!', 'Nomor RW sudah terdaftar.');
                return redirect()->back()->withInput();
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan.');
                return redirect()->back()->withInput();
            }
        }
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
            'nama'          => 'required',
            'no_hp'         => ['required','digits_between:8,12'],
            'rw'            => 'required',
            'image_rw' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            'periode_awal'  => 'required',
            'periode_akhir' => 'required',
        ]);
    
        // Cek jika no_hp diubah dan sudah ada di data lain
        if ($request->no_hp != $data->no_hp) {
            if (DataRw::where('no_hp', $request->no_hp)->exists()) {
                Alert::error('Gagal!', 'Nomor Handphone sudah digunakan oleh data lain.');
                return redirect()->back()->withInput();
            }
        }
    
        // Cek jika rw diubah dan sudah ada di data lain
        if ($request->rw != $data->rw) {
            if (DataRw::where('rw', $request->rw)->exists()) {
                Alert::error('Gagal!', 'Nomor RW ' . $request->rw . ' sudah terdaftar.');
                return redirect()->back()->withInput();
            }
        }
    
        // Hapus dan ganti gambar jika ada file baru
        if ($request->hasFile('image_rw')) {
            if ($data->image_rw) {
                Storage::disk('public')->delete('foto_rw/' . $data->image_rw);
            }
    
            $img = $request->file('image_rw');
            $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('foto_rw', $filename, 'public');
            $data->image_rw = $filename;
        }
    
        // Update data RW
        $data->nama          = $request->nama;
        $data->no_hp         = $request->no_hp;
        $data->rw            = $request->rw;
        $data->periode_awal  = $request->periode_awal;
        $data->periode_akhir = $request->periode_akhir;
        $data->save();
    
        // Update User jika ada
        if ($data->user_id) {
            User::where('id', $data->user_id)->update([
                'name'  => $request->nama,
                'email' => 'ketua-rw' . $request->rw . '@kampungbulang',
            ]);
        }
    
        Alert::success('Sukses!', 'Berhasil mengedit Data RW');
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
