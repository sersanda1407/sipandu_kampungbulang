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
        'jumlah_individu'
    ];

    public function Rw()
    {
        return $this->belongsTo('App\DataRw');
    }

    public function Rt() {
        return $this->belongsTo('App\DataRt');
    }
    public function pdd() {
        return $this->hasMany('App\DataPenduduk');
    }

}
