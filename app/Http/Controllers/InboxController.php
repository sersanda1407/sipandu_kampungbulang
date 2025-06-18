<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataKk;
use App\DataRt;
use App\DataRw;
use App\User;

class InboxController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();

    // Ambil ID RW dan RT dari user yang login (berdasarkan relasi)
    $rw_user_id = \App\DataRw::where('user_id', $user->id)->value('id');
    $rt_user_id = \App\DataRt::where('user_id', $user->id)->value('id');

    // Filter default dari request (bisa dipakai kalau superadmin akses dan ingin filter manual)
    $filter_rw = $request->get('rw');
    $filter_rt = $request->get('rt');

    // Jika login sebagai RW
    if ($rw_user_id) {
        $filter_rw = $rw_user_id;
    }

    // Jika login sebagai RT
    if ($rt_user_id) {
        $filter_rt = $rt_user_id;
    }

    // Query hanya data KK yang belum diverifikasi (pending)
    $query = \App\DataKk::where('verifikasi', 'pending');

    // Tambahkan filter berdasarkan RT dan RW
    if ($filter_rw) {
        $query->where('rw_id', $filter_rw);
    }

    if ($filter_rt) {
        $query->where('rt_id', $filter_rt);
    }

    // Tambahkan fitur pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('kepala_keluarga', 'like', "%$search%")
              ->orWhere('no_kk', 'like', "%$search%")
              ->orWhere('alamat', 'like', "%$search%");
        });
    }

    // Ambil data yang difilter & paginasi
    $entries = $request->input('entries', 5);
    $data = $query->orderBy('created_at', 'desc')->paginate($entries);

    return view('inbox.index', compact('data'));
}



public function verifikasi(Request $request, $id)
{
    $kk = DataKk::findOrFail($id);

    if ($request->acc == 1) {
        $kk->verifikasi = 'diterima';
        $kk->save();
        return redirect()->route('inbox.index')->with('success', 'Data telah diverifikasi.');
    }

    // Jika ditolak, data akan dihapus (karena tidak disimpan di tabel khusus)
    $kk->delete();
    return redirect()->route('inbox.index')->with('error', 'Data telah ditolak dan dihapus.');
}


}
