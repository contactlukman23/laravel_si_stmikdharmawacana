<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MahasiswaMatakuliah;
use Faker\Generator as Faker;

$factory->define(MahasiswaMatakuliah::class, function (Faker $faker) {

    $mahasiswa_id = $faker->numberBetween(1, App\Mahasiswa::count());
    $jurusan_mahasiswa_id = App\Mahasiswa::find($mahasiswa_id)->jurusan_id;
    $array_matakuliah = App\Matakuliah::where('jurusan_id',$jurusan_mahasiswa_id)
                        ->get('id');

    return [
        'mahasiswa_id' => $mahasiswa_id,
        'matakuliah_id' => $faker->randomElement($array_matakuliah),
    ];
});
