<?php

namespace App\Http\Controllers;

use App\DataKk;
use App\Providers\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    protected $fonnteService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->fonnteService = new FonnteService();
    }

    /**
     * Verifikasi KK dan kirim notifikasi WhatsApp
     */
    public function verify($id)
    {
        try {
            $kk = DataKk::findOrFail($id);
            
            // Update status verifikasi
            $kk->verifikasi = 'diterima';
            $kk->save();

            Log::info('KK diverifikasi: ' . $kk->id);

            // Kirim notifikasi WhatsApp ke user
            $result = $this->fonnteService->sendVerificationSuccess($kk);
            
            if ($result) {
                Log::info('Notifikasi verifikasi berhasil dikirim ke user: ' . $kk->no_telp);
                return redirect()->back()->with('success', 'KK berhasil diverifikasi dan notifikasi WhatsApp telah dikirim ke warga.');
            } else {
                Log::warning('KK diverifikasi tetapi notifikasi gagal dikirim: ' . $kk->id);
                return redirect()->back()->with('success', 'KK berhasil diverifikasi tetapi notifikasi WhatsApp gagal dikirim.');
            }
        } catch (\Exception $e) {
            Log::error('Verification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan verifikasi dan kirim notifikasi
     */
    public function unverify($id)
    {
        try {
            $kk = DataKk::findOrFail($id);
            
            // Simpan data sebelum diubah untuk notifikasi
            $previousStatus = $kk->verifikasi;
            
            // Update status verifikasi
            $kk->verifikasi = 'pending';
            $kk->save();

            Log::info('Verifikasi dibatalkan untuk KK: ' . $kk->id);

            // Kirim notifikasi hanya jika sebelumnya statusnya 'diterima'
            if ($previousStatus === 'diterima') {
                $this->sendUnverifyNotifications($kk);
                return redirect()->back()->with('success', 'Status verifikasi dibatalkan dan notifikasi telah dikirim.');
            }

            return redirect()->back()->with('success', 'Status verifikasi dibatalkan.');

        } catch (\Exception $e) {
            Log::error('Unverification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

/**
 * Tolak verifikasi, hapus data, dan kirim notifikasi
 */
public function reject($id)
{
    try {
        $kk = DataKk::findOrFail($id);
        
        // Simpan data untuk notifikasi sebelum dihapus
        $kkDataForNotification = [
            'kepala_keluarga' => $kk->kepala_keluarga,
            'no_kk' => $kk->no_kk,
            'no_telp' => $kk->no_telp,
            'alamat' => $kk->alamat,
            'rt_id' => $kk->rt_id,
            'rw_id' => $kk->rw_id
        ];

        // Hapus user terkait jika ada
        if ($kk->user) {
            $kk->user->delete();
            Log::info('User terkait dihapus: ' . $kk->user->id);
        }

        // Hapus data KK
        $kk->delete();
        Log::info('KK ditolak dan dihapus: ' . $id);

        // Kirim notifikasi penolakan menggunakan data yang disimpan
        $this->sendRejectionNotifications((object)$kkDataForNotification);
        
        return redirect()->back()->with('success', 'KK berhasil ditolak, data dihapus, dan notifikasi telah dikirim.');

    } catch (\Exception $e) {
        Log::error('Rejection error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

/**
 * Kirim notifikasi penolakan
 */
protected function sendRejectionNotifications($kkData)
{
    try {
        Log::info('Mengirim notifikasi penolakan untuk KK: ' . $kkData->no_kk);

        // 1. Kirim notifikasi ke user
        $userResult = $this->fonnteService->sendVerificationRejectedToUser($kkData);
        Log::info('Notifikasi penolakan ke user: ' . ($userResult ? 'Berhasil' : 'Gagal'));

        // 2. Kirim notifikasi ke RT
        $rtResult = $this->fonnteService->sendVerificationRejectedToRT($kkData);
        Log::info('Notifikasi penolakan ke RT: ' . ($rtResult ? 'Berhasil' : 'Gagal'));

        // 3. Kirim notifikasi ke RW
        $rwResult = $this->fonnteService->sendVerificationRejectedToRW($kkData);
        Log::info('Notifikasi penolakan ke RW: ' . ($rwResult ? 'Berhasil' : 'Gagal'));

    } catch (\Exception $e) {
        Log::error('Error notifikasi penolakan: ' . $e->getMessage());
    }
}

    /**
     * Kirim notifikasi pembatalan verifikasi
     */
    protected function sendUnverifyNotifications($kk)
    {
        try {
            Log::info('Mengirim notifikasi pembatalan verifikasi untuk KK: ' . $kk->id);

            // 1. Kirim notifikasi ke user
            $userResult = $this->fonnteService->sendVerificationCancelledToUser($kk);
            Log::info('Notifikasi pembatalan ke user: ' . ($userResult ? 'Berhasil' : 'Gagal'));

            // 2. Kirim notifikasi ke RT
            $rtResult = $this->fonnteService->sendVerificationCancelledToRT($kk);
            Log::info('Notifikasi pembatalan ke RT: ' . ($rtResult ? 'Berhasil' : 'Gagal'));

            // 3. Kirim notifikasi ke RW
            $rwResult = $this->fonnteService->sendVerificationCancelledToRW($kk);
            Log::info('Notifikasi pembatalan ke RW: ' . ($rwResult ? 'Berhasil' : 'Gagal'));

        } catch (\Exception $e) {
            Log::error('Error notifikasi pembatalan: ' . $e->getMessage());
        }
    }

    /**
     * Kirim pengingat verifikasi ke RT/RW
     */
    public function sendVerificationReminder($id)
    {
        try {
            $kk = DataKk::findOrFail($id);
            
            $results = [];
            
            // Kirim pengingat ke RT
            $rtResult = $this->fonnteService->sendToRT($kk);
            $results[] = 'RT: ' . ($rtResult ? 'Berhasil' : 'Gagal');

            // Kirim pengingat ke RW
            $rwResult = $this->fonnteService->sendToRW($kk);
            $results[] = 'RW: ' . ($rwResult ? 'Berhasil' : 'Gagal');

            Log::info('Pengingat verifikasi dikirim untuk KK: ' . $kk->id . ' - ' . implode(', ', $results));

            return redirect()->back()->with('success', 'Pengingat verifikasi telah dikirim. ' . implode(', ', $results));
        } catch (\Exception $e) {
            Log::error('Reminder error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}