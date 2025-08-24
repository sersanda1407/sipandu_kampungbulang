<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('FONNTE_API_KEY', '');
        $this->baseUrl = env('FONNTE_API_URL', 'https://api.fonnte.com');
    }

    /**
     * Kirim pesan WhatsApp menggunakan Fonnte API - SEDERHANA
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Format nomor telepon
            $formattedNumber = $this->formatPhoneNumber($phoneNumber);

            if (empty($formattedNumber)) {
                Log::warning('Nomor telepon kosong, tidak dapat mengirim pesan');
                return false;
            }

            Log::info('Mengirim pesan ke: ' . $formattedNumber);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->post($this->baseUrl . '/send', [
                        'target' => $formattedNumber,
                        'message' => $message,
                    ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    Log::info('Pesan berhasil dikirim ke: ' . $formattedNumber);
                    return true;
                }
            }

            Log::error('Gagal mengirim pesan: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Error mengirim pesan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor telepon untuk API - SEDERHANA
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return null;
        }

        // Hilangkan karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Jika diawali dengan 0, ganti dengan 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    /**
     * Kirim notifikasi ke user bahwa pendaftaran berhasil - SEDERHANA
     */
    public function sendToUser($kk)
    {
        $message = "✅ *PENDAFTARAN BERHASIL* \n\n" .
            "Halo " . $kk->kepala_keluarga . ",\n\n" .
            "Pendaftaran Anda di Website SIPANDU telah berhasil.\n\n" .
            "👤 *Username*: " . $kk->no_kk . "\n" .
            "🔑 *Password*: password\n\n" .
            "Data sedang diverifikasi oleh RT/RW.\n" .
            "Jangan beri tahu akun anda kepada siapa pun karena menyimpan data sensitif! \n Anda akan dapat login setelah proses verifikasi selesai.";

        return $this->sendMessage($kk->no_telp, $message);
    }

    /**
     * Kirim notifikasi ke RT terkait - SEDERHANA
     */
    public function sendToRT($kk)
    {
        $rt = \App\DataRt::find($kk->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            return false;
        }

        $message = "📋 *PENDAFTARAN BARU* \n\n" .
            "Ada warga baru mendaftar di wilayah RT Anda:\n\n" .
            "👤 *Nama*: " . $kk->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kk->no_kk . "\n" .
            "📍 *Alamat*: " . $kk->alamat . "\n" .
            "📞 *No Telp*: " . $kk->no_telp . "\n\n" .
            "Segera lakukan verifikasi di sistem SIPANDU ya.";

        return $this->sendMessage($rt->no_hp, $message);
    }

    /**
     * Kirim notifikasi ke RW terkait - SEDERHANA
     */
    public function sendToRW($kk)
    {
        $rw = \App\DataRw::find($kk->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            return false;
        }

        $message = "📋 *PENDAFTARAN BARU* \n\n" .
            "Ada warga baru mendaftar di wilayah RW Anda:\n\n" .
            "👤 *Nama*: " . $kk->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kk->no_kk . "\n" .
            "📍 *Alamat*: " . $kk->alamat . "\n" .
            "📞 *No Telp*: " . $kk->no_telp . "\n\n" .
            "📍 *Wilayah RT*: " . ($kk->rt->rt ?? '') . "\n\n" .
            "Segera lakukan verifikasi di sistem SIPANDU ya.";

        return $this->sendMessage($rw->no_hp, $message);
    }

    /**
     * Kirim notifikasi verifikasi ke user - SEDERHANA
     */
    public function sendVerificationSuccess($kk)
    {
        $message = "🎉 *AKUN SUDAH AKTIF* \n\n" .
            "Halo " . $kk->kepala_keluarga . ",\n\n" .
            "Akun SIPANDU Anda sudah diverifikasi.\n\n" .
            "Sekarang Anda bisa login dengan:\n" .
            "👤 *Username*: " . $kk->no_kk . "\n" .
            "🔑 *Password*: password\n\n" .
            "Segera ganti *password* dulu ya ! \n Selamat menggunakan SIPANDU!";

        return $this->sendMessage($kk->no_telp, $message);
    }

    /**
     * Kirim notifikasi pembatalan verifikasi ke user - SEDERHANA
     */
    public function sendVerificationCancelledToUser($kk)
    {
        $rt = \App\DataRt::find($kk->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            Log::warning('RT atau nomor HP RT tidak tersedia untuk KK: ' . $kk->id);
            return false;
        }

        $rw = \App\DataRw::find($kk->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            return false;
        }

        if (empty($kk->no_telp)) {
            Log::warning('Nomor telepon user tidak tersedia untuk KK: ' . $kk->id);
            return false;
        }

        $message = "⚠️ *AKUN ANDA DITOLAK* \n\n" .
            "Halo " . $kk->kepala_keluarga . ",\n\n" .
            "Status verifikasi akun SIPANDU Anda telah ditolak.\n\n" .
            "👤 *Nama*: " . $kk->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kk->no_kk . "\n\n" .
            "Silakan hubungi RT/RW terkait untuk informasi lebih lanjut.\n\n" .
            "Ketua RT" . $rt->rt . "(" . $rt->nama . ")" . ":" . $rt->no_hp . "\n" .
            "Ketua RW" . $rw->rw . "(" . $rw->nama . ")" . ":" . $rw->no_hp . "\n" .
            "Terima kasih.";

        return $this->sendMessage($kk->no_telp, $message);
    }

    /**
     * Kirim notifikasi pembatalan verifikasi ke RT
     */
    public function sendVerificationCancelledToRT($kk)
    {
        $rt = \App\DataRt::find($kk->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            Log::warning('RT atau nomor HP RT tidak tersedia untuk KK: ' . $kk->id);
            return false;
        }

        $message = "⚠️ *DATA TELAH DITOLAK* \n\n" .
            "Status verifikasi telah ditolak untuk data:\n\n" .
            "👤 *Nama*: " . $kk->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kk->no_kk . "\n" .
            "📍 *Wilayah RT*: " . ($rt->rt ?? 'N/A') . "\n\n" .
            "Status: *PENDING*\n\n" .
            "Silakan lakukan verifikasi ulang jika diperlukan.";

        return $this->sendMessage($rt->no_hp, $message);
    }

    /**
     * Kirim notifikasi pembatalan verifikasi ke RW
     */
    public function sendVerificationCancelledToRW($kk)
    {
        $rw = \App\DataRw::find($kk->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            Log::warning('RW atau nomor HP RW tidak tersedia untuk KK: ' . $kk->id);
            return false;
        }

        $message = "⚠️ *VERIFIKASI DIBATALKAN* \n\n" .
            "Status verifikasi telah dibatalkan untuk:\n\n" .
            "👤 *Nama*: " . $kk->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kk->no_kk . "\n" .
            "📍 *RW*: " . ($rw->rw ?? 'N/A') . "\n\n" .
            "Status: *PENDING*\n\n" .
            "Silakan lakukan verifikasi ulang jika diperlukan.";

        return $this->sendMessage($rw->no_hp, $message);
    }

    /**
     * Kirim notifikasi penolakan verifikasi ke user
     */
    public function sendVerificationRejectedToUser($kkData)
    {
        $rt = \App\DataRt::find($kkData->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            Log::warning('RT atau nomor HP RT tidak tersedia untuk KK: ' . $kkData->id);
            return false;
        }

        $rw = \App\DataRw::find($kkData->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            return false;
        }

        if (empty($kkData->no_telp)) {
            Log::warning('Nomor telepon user tidak tersedia untuk KK: ' . $kkData->no_kk);
            return false;
        }

        $message = "❌ *VERIFIKASI DITOLAK* \n\n" .
            "Halo " . $kkData->kepala_keluarga . ",\n\n" .
            "Maaf, pendaftaran Anda di website SIPANDU telah ditolak.\n\n" .
            "Silakan hubungi RT/RW terkait untuk informasi lebih lanjut.\n\n" .
            "Ketua RT " . ($rt->rt ?? 'N/A') . " (" . $rt->nama . ")" . " : " . $rt->no_hp . "\n" .
            "Ketua RW " . ($rw->rw ?? 'N/A') . " (" . $rw->nama . ")" . " : " . $rw->no_hp . "\n" .
            "Terima kasih.";

        return $this->sendMessage($kkData->no_telp, $message);
    }

    /**
     * Kirim notifikasi penolakan verifikasi ke RT
     */
    public function sendVerificationRejectedToRT($kkData)
    {
        $rt = \App\DataRt::find($kkData->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            Log::warning('RT atau nomor HP RT tidak tersedia untuk KK: ' . $kkData->no_kk);
            return false;
        }

        $rw = \App\DataRw::find($kkData->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            return false;
        }

        $message = "❌ *VERIFIKASI TELAH DITOLAK* \n\n" .
            "Data calon warga baru berikut telah ditolak dan dihapus:\n\n" .
            "👤 *Nama*: " . $kkData->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kkData->no_kk . "\n" .
            "📍 *Alamat*: " . $kkData->alamat . "\n" .
            "📞 *No Telp*: " . $kkData->no_telp . "\n" .
            "📍 *Wilayah*: " . "RT " . ($rt->rt ?? 'N/A') . " / " . "RW " . ($rw->rw ?? 'N/A') . "\n" .
            "Status: *DITOLAK*\n\n" .
            "Data telah dihapus dari sistem.";

        return $this->sendMessage($rt->no_hp, $message);
    }

    /**
     * Kirim notifikasi penolakan verifikasi ke RW
     */
    public function sendVerificationRejectedToRW($kkData)
    {
        $rt = \App\DataRt::find($kkData->rt_id);

        if (!$rt || empty($rt->no_hp)) {
            Log::warning('RT atau nomor HP RT tidak tersedia untuk KK: ' . $kkData->no_kk);
            return false;
        }

        $rw = \App\DataRw::find($kkData->rw_id);

        if (!$rw || empty($rw->no_hp)) {
            Log::warning('RW atau nomor HP RW tidak tersedia untuk KK: ' . $kkData->no_kk);
            return false;
        }

        $message = "❌ *VERIFIKASI TELAH DITOLAK* \n\n" .
            "Data calon warga baru berikut telah ditolak dan dihapus:\n\n" .
            "👤 *Nama*: " . $kkData->kepala_keluarga . "\n" .
            "🔢 *No KK*: " . $kkData->no_kk . "\n" .
            "📍 *Alamat*: " . $kkData->alamat . "\n" .
            "📞 *No Telp*: " . $kkData->no_telp . "\n" .
            "📍 *Wilayah*: " . "RT " . ($rt->rt ?? 'N/A') . " / " . "RW " . ($rw->rw ?? 'N/A') . "\n" .
            "Status: *DITOLAK*\n\n" .
            "Data telah dihapus dari sistem.";

        return $this->sendMessage($rw->no_hp, $message);
    }
}