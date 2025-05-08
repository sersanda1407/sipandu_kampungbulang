<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRt extends Model
{
    protected $table = 'rt';

    protected $fillable = [
        'nama',
        'no_hp',
        'rt',
        'alamat_rt',
        'gmail_rt',
        'rw_id',
        'image_rt',
        'periode_awal',
        'periode_akhir'
    ];

    public function Rw()
    {
        return $this->belongsTo('App\DataRw');
    }
    public function Kk()
    {
        return $this->belongsTo('App\DataRw');
    }

    public function pdd () {
        return $this->hasMany('App\DataPenduduk','rt_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
