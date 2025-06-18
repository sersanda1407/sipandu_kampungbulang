<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKk extends Model
{
    protected $table = 'kk';

    protected $fillable = [
        'kepala_keluarga',
        'no_kk',
        'image',
        'rt_id',
        'rw_id',
        'alamat',
        'no_telp',
        'jumlah_individu',
        'user_id',
        'verifikasi',
    ];

    // Relasi ke tabel rw
    public function Rw()
    {
        return $this->belongsTo('App\DataRw');
    }

    // Relasi ke tabel rt
    public function Rt()
    {
        return $this->belongsTo('App\DataRt');
    }

    // Relasi ke penduduk
    public function pdd()
    {
        return $this->hasMany('App\DataPenduduk');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Menghapus user otomatis saat KK dihapus
    protected static function booted()
    {
        static::deleting(function ($kk) {
            if ($kk->user) {
                $kk->user->delete();
            }
        });
    }
}
