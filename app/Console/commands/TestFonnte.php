<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\FonnteService;

class TestFonnte extends Command
{
    protected $signature = 'fonnte:test {phone}';
    protected $description = 'Test Fonnte API connection';

    public function handle()
    {
        $phone = $this->argument('phone');
        $fonnteService = new FonnteService();
        
        $message = "✅ Test Notifikasi SIPANDU\n\nIni adalah pesan test dari sistem SIPANDU. Jika Anda menerima pesan ini, berarti integrasi WhatsApp berhasil.";
        
        $result = $fonnteService->sendMessage($phone, $message);
        
        if ($result) {
            $this->info('Pesan test berhasil dikirim!');
        } else {
            $this->error('Gagal mengirim pesan test.');
        }
    }
}