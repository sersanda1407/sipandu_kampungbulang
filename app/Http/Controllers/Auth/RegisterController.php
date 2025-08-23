<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\DataKk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Providers\FonnteService;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->fonnteService = new FonnteService();
    }

    public function storePublic(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kepala_keluarga' => 'required|string|max:255',
                'no_kk' => 'required|string|max:255|unique:users,email',
                'alamat' => 'required|string',
                'rt_id' => 'required|integer',
                'rw_id' => 'required|integer',
                'no_telp' => 'required|string|max:15',
                'image' => 'required|mimes:jpeg,jpg,png,gif,svg,webp|max:3072',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Buat akun user
            $user = User::create([
                'name' => $request->kepala_keluarga,
                'email' => $request->no_kk,
                'password' => Hash::make('password'),
            ]);

            // Simpan data KK
            $kk = new DataKk();
            $kk->kepala_keluarga = $request->kepala_keluarga;
            $kk->no_kk = $request->no_kk;
            $kk->rt_id = $request->rt_id;
            $kk->rw_id = $request->rw_id;
            $kk->alamat = $request->alamat;
            $kk->no_telp = $request->no_telp;
            $kk->user_id = $user->id;
            $kk->verifikasi = 'pending';

            // Simpan gambar
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('foto_kk', $filename, 'public');
                $kk->image = $filename;
            }

            $kk->save();

            // Beri role 'warga'
            $user->assignRole('warga');

            // 🔥 KIRIM NOTIFIKASI WHATSAPP
            $this->sendWhatsAppNotifications($kk);

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Notifikasi WhatsApp telah dikirim. Silakan login menggunakan No KK dan password: password setelah verifikasi.');

        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->errorInfo[1] == 1062 ? 'No KK sudah terdaftar.' : 'Terjadi kesalahan saat pendaftaran.';
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->with('success', 'Pendaftaran berhasil. Notifikasi WhatsApp mungkin tidak terkirim.')
                ->withInput();
        }
    }

    protected function sendWhatsAppNotifications($kk)
    {
        try {
            Log::info('Mengirim notifikasi WhatsApp untuk KK: ' . $kk->id);

            // 1. Kirim ke user
            $userResult = $this->fonnteService->sendToUser($kk);
            Log::info('Notifikasi ke user: ' . ($userResult ? 'Berhasil' : 'Gagal'));

            // 2. Kirim ke RT
            $rtResult = $this->fonnteService->sendToRT($kk);
            Log::info('Notifikasi ke RT: ' . ($rtResult ? 'Berhasil' : 'Gagal'));

            // 3. Kirim ke RW
            $rwResult = $this->fonnteService->sendToRW($kk);
            Log::info('Notifikasi ke RW: ' . ($rwResult ? 'Berhasil' : 'Gagal'));

        } catch (\Exception $e) {
            Log::error('WhatsApp notifications error: ' . $e->getMessage());
        }
    }
}