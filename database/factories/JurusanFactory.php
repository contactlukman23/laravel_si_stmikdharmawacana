<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Jurusan;
use Faker\Generator as Faker;

$factory->define(Jurusan::class, function (Faker $faker) {
    $daftar_jurusan = ["Ilmu Komputer",
                       "Sistem Informasi",
                       "Teknik Informatika"];

     return [
         'nama' => $faker->unique()->randomElement($daftar_jurusan),
         'kepala_jurusan' => "Dr. ".$faker->firstName." ".$faker->lastName,
         'daya_tampung' => $faker->numberBetween(5,8)*10,
     ];
});
