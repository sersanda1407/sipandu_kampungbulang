<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\DataKk;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'kepala_keluarga' => 'required|string|max:255',
            'no_kk' => 'required|string|max:255|unique:users,email',
            'alamat' => 'required|string',
            'rt_id' => 'required|integer',
            'rw_id' => 'required|integer',
            'image' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
        ]);
    }

    protected function create(array $data)
    {
        try {
            // Buat akun user
            $user = User::create([
                'name' => $data['kepala_keluarga'],
                'email' => $data['no_kk'],
                'password' => Hash::make('password'),
            ]);

            // Simpan data KK
            $kk = new DataKk();
            $kk->kepala_keluarga = $data['kepala_keluarga'];
            $kk->no_kk = $data['no_kk'];
            $kk->rt_id = $data['rt_id'];
            $kk->rw_id = $data['rw_id'];
            $kk->alamat = $data['alamat'];
            $kk->user_id = $user->id;

            // Simpan gambar jika tersedia
            if (request()->hasFile('image')) {
                $image = request()->file('image');
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('foto_kk', $filename, 'public');
                $kk->image = $filename;
            }

            $kk->save();

            // Beri role 'warga'
            $user->assignRole('warga');

            session()->flash('success', 'Pendaftaran berhasil. Silakan login menggunakan No KK dan password: password');
            return $user;
        } catch (\Illuminate\Database\QueryException $e) {
            session()->flash('error', $e->errorInfo[1] == 1062 ? 'No KK sudah terdaftar.' : 'Terjadi kesalahan saat pendaftaran.');
            return redirect()->back()->withInput();
        }
    }
}
