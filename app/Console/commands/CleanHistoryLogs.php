<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\HistoryLogHelper;

class CleanHistoryLogs extends Command
{
    protected $signature = 'logs:clean';
    protected $description = 'Hapus data history log yang berusia lebih dari 3 bulan';

    public function handle()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        // Logging detail mulai
        \Log::info('====== MEMULAI PEMBERSIHAN HISTORY LOG ======');
        \Log::info('Starting history log cleanup for records before: ' . $threeMonthsAgo->format('Y-m-d H:i:s'));
        $this->info("Membersihkan data history log sebelum: " . $threeMonthsAgo->format('Y-m-d H:i:s'));
        
        // Hitung total data yang akan dihapus
        $countToDelete = DB::table('history_logs')
            ->where('created_at', '<', $threeMonthsAgo)
            ->count();
        
        $this->info("Data yang akan dihapus: " . $countToDelete . " records");
        \Log::info('Total records to delete: ' . $countToDelete);
        
        if ($countToDelete > 0) {
            // Tampilkan sample data yang akan dihapus (opsional, untuk debugging)
            $sampleRecords = DB::table('history_logs')
                ->where('created_at', '<', $threeMonthsAgo)
                ->orderBy('created_at', 'asc')
                ->take(5)
                ->get(['id', 'activity_type', 'description', 'created_at']);
            
            \Log::info('Sample records to be deleted:', $sampleRecords->toArray());
            
            $this->info("Contoh data yang akan dihapus:");
            foreach ($sampleRecords as $record) {
                $this->line("- ID: {$record->id}, Type: {$record->activity_type}, Date: {$record->created_at}");
            }
        }
        
        // Hapus data yang lebih dari 3 bulan
        $deletedCount = DB::table('history_logs')
            ->where('created_at', '<', $threeMonthsAgo)
            ->delete();
        
        $this->info("Berhasil menghapus $deletedCount data history log yang berusia lebih dari 3 bulan.");
        \Log::info('Successfully deleted ' . $deletedCount . ' history log records.');
        
        // Catat log pembersihan ke history log
        if ($deletedCount > 0) {
            createHistoryLog('system_cleanup', 'Sistem menghapus ' . $deletedCount . ' data history log yang berusia lebih dari 3 bulan');
            \Log::info('Cleanup activity logged to history log.');
        } else {
            \Log::info('No records were deleted, skipping history log entry.');
        }
        
        // Hitung sisa data
        $remainingCount = DB::table('history_logs')->count();
        $this->info("Total data history log sekarang: " . $remainingCount . " records");
        \Log::info('Remaining history log records: ' . $remainingCount);
        
        \Log::info('====== PEMBERSIHAN HISTORY LOG SELESAI ======');
        $this->info("Pembersihan history log selesai.");
        
        return 0;
    }
}