<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dosen;
use Faker\Generator as Faker;

$factory->define(Dosen::class, function (Faker $faker) {
    $daftar_titel = ["M.Kom", "M.Sc", "M.T", "M.Si"];

    return [
        'nid' => $faker->unique()->numerify('99######'),
        'nama' => $faker->firstName." ".$faker->lastName." ".
                  $faker->randomElement($daftar_titel),
        'jurusan_id' => $faker->numberBetween(1, App\Jurusan::count()),
    ];
});
