<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = ['nid','nama','jurusan_id'];

    public function matakuliahs()
    {
        return $this->hasMany('App\Matakuliah');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
}
