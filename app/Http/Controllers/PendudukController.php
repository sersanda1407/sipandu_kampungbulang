<?php

namespace App\Http\Controllers;

use App\DataKk;
use App\DataPenduduk;
use App\DataRt;
use App\DataRw;
use App\Lurah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
use Barryvdh\DomPDF\Facade\Pdf as PDF;



class PendudukController extends Controller
{
    /**
 * @return \Illuminate\View\View
 */


    public function index()
    {
        /** @var \App\User $user */
        $user = Auth::user();

        if ($user->hasRole('rw')) {
            $data = DataPenduduk::where('rw_id', $user->Rw[0]->id)->get();
        } elseif ($user->hasRole('rt')) {
            $data = DataPenduduk::where('rt_id', $user->Rt[0]->id)->get();
        } else {
            $data = DataPenduduk::all();
        }

        $data = $data->sort(function ($a, $b) {
            if ($a->rt_id !== $b->rt_id) {
                return $a->rt_id - $b->rt_id;
            }

            if ($a->rw_id !== $b->rw_id) {
                return $a->rw_id - $b->rw_id;
            }

            $kkCompare = strcmp($a->kk->no_kk, $b->kk->no_kk);
            if ($kkCompare !== 0)
                return $kkCompare;

            $statusOrder = [
                'Kepala Rumah Tangga' => 1,
                'Isteri' => 2,
                'Anak' => 3,
                'Lainnya' => 4,
            ];

            $aStatus = $statusOrder[$a->status_keluarga] ?? 99;
            $bStatus = $statusOrder[$b->status_keluarga] ?? 99;

            if ($aStatus !== $bStatus)
                return $aStatus - $bStatus;

            if ($aStatus === 3 && $bStatus === 3) {
                return $b->usia - $a->usia;
            }

            return 0;
        });

        $selectRw = DataRw::get();

        // ✅ Ambil angka RT unik, sort dari kecil ke besar
        $selectRt = DataRt::select('id', 'rt')
            ->get()
            ->map(function ($item) {
                $item->rt = (int) $item->rt;
                return $item;
            })
            ->unique('rt')
            ->sortBy('rt')
            ->values();

        $kk = DataKk::get();
        $lurah = Lurah::first();

        return view('penduduk.index', compact('data', 'selectRt', 'selectRw', 'kk', 'lurah'));
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
    public function store(Request $request, $id)
    {
        $dataKk = DataKk::where('id', $id)->firstOrFail();

        $this->validate($request, [
            'nama' => 'required',
            'nik' => 'required',
            'gender' => 'required',
            'usia' => 'required',
            'alamat' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required',
            'status_pernikahan' => 'required',
            'status_keluarga' => 'required',
            'status_sosial' => 'required',
            'pekerjaan' => 'required',
            'gaji' => 'required',
            'image_ktp' => 'required|mimes:jpeg,jpg,png,gif,svg|max:3072',
            'no_hp' => 'required',
        ]);

        $data = new DataPenduduk();
        $data->nama = $request->nama;
        $data->nik = $request->nik;
        $data->kk_id = $dataKk->id;
        $data->rw_id = $dataKk->rw_id;
        $data->rt_id = $dataKk->rt_id;
        $data->gender = $request->gender;
        $data->usia = $request->usia;
        $data->tmp_lahir = $request->tmp_lahir;
        $data->tgl_lahir = $request->tgl_lahir;
        $data->agama = $request->agama;
        $data->alamat = $request->alamat;
        $data->status_pernikahan = $request->status_pernikahan;
        $data->status_keluarga = $request->status_keluarga;
        $data->status_sosial = $request->status_sosial;
        $data->pekerjaan = $request->pekerjaan;
        $data->gaji = (int) str_replace('.', '', $request->gaji);
        $data->no_hp = $request->no_hp;

        // Gunakan UUID untuk nama file gambar
        if ($request->hasFile('image_ktp')) {
            $img = $request->file('image_ktp');
            $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
            $request->file('image_ktp')->storeAs('foto_ktp', $filename, 'public');
            $data->image_ktp = $filename;
        }

        try {
            $data->save();
            Alert::success('Sukses!', 'Berhasil menambah Data Penduduk');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                Alert::error('Notifikasi', 'NIK Sudah Terdaftar');
            } else {
                Alert::error('Error', 'Terjadi kesalahan, silakan coba lagi.');
            }
        }

        return redirect()->back();
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
        $data = DataPenduduk::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'gender' => 'required',
            'usia' => 'required',
            'alamat' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required',
            'status_pernikahan' => 'required',
            'status_keluarga' => 'required',
            'status_sosial' => 'required',
            'pekerjaan' => 'required',
            'gaji' => 'required',
            'image_ktp' => 'nullable|mimes:jpeg,jpg,png,gif,svg|max:3072',
            'no_hp' => 'required',
        ]);

        // Cek kalau nik diinput berbeda dengan yang lama
        if ($request->nik != $data->nik) {
            $existing = DataPenduduk::where('nik', $request->nik)->first();

            if ($existing) {
                Alert::error('Gagal!', 'NIK sudah terdaftar, tidak bisa diubah.');
                return redirect()->back()->withInput();
            }
        }

        // Handle upload gambar jika ada file baru
        if ($request->hasFile('image_ktp')) {
            if ($data->image_ktp) {
                Storage::disk('public')->delete('foto_ktp/' . $data->image_ktp);
            }

            $filename = Str::uuid() . '.' . $request->file('image_ktp')->getClientOriginalExtension();
            $request->file('image_ktp')->storeAs('foto_ktp', $filename, 'public');

            $data->image_ktp = $filename;
        }

        // Update data KTP
        $data->nama = $request->nama;
        $data->nik = $request->nik;
        $data->gender = $request->gender;
        $data->usia = $request->usia;
        $data->tmp_lahir = $request->tmp_lahir;
        $data->tgl_lahir = $request->tgl_lahir;
        $data->agama = $request->agama;
        $data->alamat = $request->alamat;
        $data->status_pernikahan = $request->status_pernikahan;
        $data->status_keluarga = $request->status_keluarga;
        $data->status_sosial = $request->status_sosial;
        $data->pekerjaan = $request->pekerjaan;
        $data->gaji = (int) str_replace('.', '', $request->gaji);
        $data->no_hp = $request->no_hp;

        $data->save();

        Alert::success('Sukses!', 'Berhasil mengedit Data Penduduk');
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

        $data = DataPenduduk::find($id);
        if ($data->image_ktp) {
            Storage::delete('/foto_ktp/' . $data->image_ktp);
        }
        $data->delete();

        Alert::Success('Sukses!', 'Berhasil menghapus Data Penduduk');

        return redirect()->back();
    }

    private function sortPenduduk($data)
    {
        return $data->sort(function ($a, $b) {
            // Urutkan berdasarkan RT terlebih dahulu
            if ($a->rt_id !== $b->rt_id) {
                return $a->rt_id - $b->rt_id;
            }

            // Lalu urutkan berdasarkan RW
            if ($a->rw_id !== $b->rw_id) {
                return $a->rw_id - $b->rw_id;
            }

            // Kemudian urutkan berdasarkan no_kk
            $kkCompare = strcmp($a->kk->no_kk, $b->kk->no_kk);
            if ($kkCompare !== 0)
                return $kkCompare;

            // Urutkan berdasarkan status keluarga
            $statusOrder = [
                'Kepala Rumah Tangga' => 1,
                'Isteri' => 2,
                'Anak' => 3,
                'Lainnya' => 4,
            ];

            $aStatus = $statusOrder[$a->status_keluarga] ?? 99;
            $bStatus = $statusOrder[$b->status_keluarga] ?? 99;

            if ($aStatus !== $bStatus)
                return $aStatus - $bStatus;

            // Jika status sama-sama Anak, urutkan berdasarkan usia (anak tertua dulu)
            if ($aStatus === 3 && $bStatus === 3) {
                return $b->usia - $a->usia;
            }

            return 0;
        });
    }


    public function export($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Data tidak valid atau rusak.');
        }

        $data = DataKk::findOrFail($id);
        $penduduk = DataPenduduk::where('kk_id', $data->id)->get();
        $penduduk = $this->sortPenduduk($penduduk);

        $lurah = Lurah::first();

        $pdf = PDF::loadView('penduduk.export', compact('penduduk', 'lurah'))
            ->setPaper('a4', 'landscape')
            ->setWarnings(false);
        return $pdf->stream('kk.pdf');
    }


    public function exportRt($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Data tidak valid atau rusak.');
        }

        $rt = DataRt::with('rw')->findOrFail($id);
        $penduduk = DataPenduduk::where('rt_id', $rt->id)->get();
        $penduduk = $this->sortPenduduk($penduduk);

        $rw = $rt->rw;
        $lurah = Lurah::first();

        $pdf = PDF::loadView('penduduk.exportRt', compact('penduduk', 'rt', 'rw', 'lurah'))
            ->setPaper('a4', 'landscape')
            ->setWarnings(false);

        return $pdf->stream('Data_seluruh_warga_kampung_bulang_RT.pdf');
    }



    public function exportRw($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Data tidak valid atau rusak.');
        }

        $rw = DataRw::findOrFail($id);
        $penduduk = DataPenduduk::where('rw_id', $rw->id)->get();
        $penduduk = $this->sortPenduduk($penduduk);

        $lurah = Lurah::first();

        $pdf = PDF::loadView('penduduk.exportRw', compact('penduduk', 'lurah', 'rw'))
            ->setPaper('a4', 'landscape')
            ->setWarnings(false);

        return $pdf->stream('Data_seluruh_warga_kampung_bulang_RW.pdf');
    }


    public function exportAll()
    {
        $penduduk = DataPenduduk::all();
        $penduduk = $this->sortPenduduk($penduduk);

        $lurah = Lurah::first();

        $pdf = PDF::loadView('penduduk.exportAll', compact('penduduk', 'lurah'))
            ->setPaper('a4', 'landscape')
            ->setWarnings(false);
        return $pdf->stream('Data_Seluruh_Warga_Kampung_Bulang.pdf');
    }

public function exportFiltered(Request $request)
{
    $query = DataPenduduk::query();

    $rt = null;
    $rw = null;

    try {
        if ($request->filled('rw_id')) {
            $rwId = decrypt($request->rw_id);
            $query->where('rw_id', $rwId);
            $rw = DataRw::find($rwId);
        }

        if ($request->filled('rt_id')) {
            $rtId = decrypt($request->rt_id);
            $query->where('rt_id', $rtId);
            $rt = DataRt::with('rw')->find($rtId);

            if ($rt && !$rw) {
                $rw = $rt->rw;
            }
        }
    } catch (DecryptException $e) {
        abort(404, 'ID tidak valid atau rusak.');
    }

    // ⬇️ tambahkan eager load
    $penduduk = $query->with(['rt', 'rw', 'kk'])->get();
    $penduduk = $this->sortPenduduk($penduduk);

    $lurah = Lurah::first();

    $pdf = PDF::loadView('penduduk.exportFiltered', compact('penduduk', 'lurah', 'rt', 'rw'))
        ->setPaper('a4', 'landscape')
        ->setWarnings(false);
    return $pdf->stream('Data_seluruh_warga_kampung_bulang_Filter_rt&rw.pdf');
}




public function filter(Request $request)
{
    $selectRw = DataRw::all();

    $selectRt = DataRt::select('id', 'rt')
        ->get()
        ->map(function ($item) {
            $item->rt = (int) $item->rt;
            return $item;
        })
        ->unique('rt')
        ->sortBy('rt')
        ->values();

    $rwId = null;
    $rtId = null;

    try {
        if ($request->filled('rw_id')) {
            $rwId = decrypt($request->rw_id);
        }

        if ($request->filled('rt_id')) {
            $rtId = decrypt($request->rt_id);
        }
    } catch (DecryptException $e) {
        Alert::error('Filter Gagal', 'Data filter tidak valid.');
        return redirect()->back();
    }

    if ($rtId && !$rwId) {
        Alert::error('Filter Gagal', 'Silakan pilih RW terlebih dahulu jika ingin memfilter berdasarkan RT.');
        return redirect()->back();
    }

    $query = DataPenduduk::query();

    if ($rwId) {
        $query->where('rw_id', $rwId);
    }

    if ($rtId) {
        $query->where('rt_id', $rtId);
    }

    $data = $query->get();

    // ✅ ambil data RT dan RW dari ID terdekripsi
    $rw = $rwId ? DataRw::find($rwId) : null;
    $rt = $rtId ? DataRt::with('rw')->find($rtId) : null;

    if ($rt && !$rw) {
        $rw = $rt->rw;
    }

    return view('penduduk.index', compact(
        'data', 'selectRw', 'selectRt',
        'rwId', 'rtId', 'rw', 'rt'
    ));
}





    // public function expot()
    // {
    //     return (new PendudukExport)->download('invoices.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    // }
}
