<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryLogController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403, 'Mau lihat apa sih?');
        }
        // Ambil data log dari tabel history_logs
        $logs = DB::table('history_logs')
                ->orderBy('created_at', 'desc')
                ->get();
        
        return view('histori.index', compact('logs'));
    }
}