<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lurah extends Model
{
    protected $table = 'lurah'; // Nama tabelnya

    protected $fillable = ['nama', 'jabatan', 'nip']; // Kolom yang bisa diisi
}
