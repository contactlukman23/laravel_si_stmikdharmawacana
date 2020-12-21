<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $fillable = ['kode','nama','jurusan_id','dosen_id','jumlah_sks'];

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }

    public function dosen()
    {
        return $this->belongsTo('App\Dosen');
    }

    public function mahasiswas()
    {
        return $this->belongsToMany('App\Mahasiswa')->withTimestamps();
    }
}
