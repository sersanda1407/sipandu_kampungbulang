<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRw extends Model
{
    protected $table = 'rw';

    protected $fillable = [
        'nama',
        'no_hp',
        'rw',
        'alamat_rw',
        'gmail_rw',
        'image_rw',
        'periode_awal',
        'periode_akhir',
        'user_id'
    ];

    public function rt()
    {
        return $this->hasMany('App\DataRt', 'rw_id');
    }

    public function kk()
    {
        return $this->hasMany('App\DataKk', 'rw_id');
    }

    public function pdd()
    {
        return $this->hasMany('App\DataPenduduk', 'rw_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
