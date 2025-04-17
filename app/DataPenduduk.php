<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPenduduk extends Model
{
    protected $table = 'penduduk';

    protected $fillable = [
        'nama',
        'nik',
        'kk_id',
        'rw_id',
        'rt_id',
        'gender',
        'usia', 
        'tmp_lahir',
        'tgl_lahir',
        'agama', 
        'alamat',
        'status_pernikahan',
        'status_keluarga',
        'pekerjaan',
        'no_hp',
    ];

    public function rt () {
        return $this->belongsTo('App\DataRt');
    }

    public function rw () {
        return $this->belongsTo('App\DataRw');
    }

    public function kk () {
        return $this->belongsTo('App\DataKk');
    }


}
