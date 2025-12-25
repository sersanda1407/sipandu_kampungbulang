<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\FonnteService;

class TestFonnteSimple extends Command
{
    protected $signature = 'fonnte:test-simple';
    protected $description = 'Test API WhatsApp dengan pilihan';

    public function handle()
    {
        $fonnteService = new FonnteService();
        
        $this->info('TEST API WhatsApp - SIPANDU');
        $this->info('==========================');
        
        $choice = $this->choice('Pilih test:', [
            'Test nomor tertentu',
            'Test ke RT',
            'Test ke RW', 
            'Test ke User',
            'Test verifikasi user'
        ], 0);

        switch ($choice) {
            case 'Test nomor tertentu':
                $phone = $this->ask('Masukkan nomor telepon (contoh: 081234567890)');
                $message = "Test Notifikasi SIPANDU\n\nIni adalah pesan test.";
                $result = $fonnteService->sendMessage($phone, $message);
                break;
                
            case 'Test ke RT':
                $kkId = $this->ask('Masukkan ID KK untuk test RT');
                $kk = \App\DataKk::find($kkId);
                if ($kk) {
                    $result = $fonnteService->sendToRT($kk);
                } else {
                    $this->error('KK tidak ditemukan');
                    return;
                }
                break;
                
            case 'Test ke RW':
                $kkId = $this->ask('Masukkan ID KK untuk test RW');
                $kk = \App\DataKk::find($kkId);
                if ($kk) {
                    $result = $fonnteService->sendToRW($kk);
                } else {
                    $this->error('KK tidak ditemukan');
                    return;
                }
                break;
                
            case 'Test ke User':
                $kkId = $this->ask('Masukkan ID KK untuk test User');
                $kk = \App\DataKk::find($kkId);
                if ($kk) {
                    $result = $fonnteService->sendToUser($kk);
                } else {
                    $this->error('KK tidak ditemukan');
                    return;
                }
                break;
                
            case 'Test verifikasi user':
                $kkId = $this->ask('Masukkan ID KK untuk test verifikasi');
                $kk = \App\DataKk::find($kkId);
                if ($kk) {
                    $result = $fonnteService->sendVerificationSuccess($kk);
                } else {
                    $this->error('KK tidak ditemukan');
                    return;
                }
                break;
        }
        
        if ($result) {
            $this->info('Pesan berhasil dikirim!');
        } else {
            $this->error('Gagal mengirim pesan');
        }
        
        $this->info('Cek log: storage/logs/laravel.log');
    }
}