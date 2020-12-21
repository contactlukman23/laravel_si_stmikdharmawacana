<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Mahasiswa;
use Faker\Generator as Faker;

$factory->define(Mahasiswa::class, function (Faker $faker) {
    return [
        'nim' => $faker->unique()->numerify('10######'),
        'nama' => $faker->firstName." ".$faker->lastName,
        'jurusan_id' => $faker->numberBetween(1, App\Jurusan::count()),
    ];
});
