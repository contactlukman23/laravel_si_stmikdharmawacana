<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/','JurusanController@index');

Route::resource('jurusans','JurusanController');
Route::resource('dosens','DosenController');
Route::resource('mahasiswas','MahasiswaController');
Route::resource('matakuliahs','MatakuliahController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/jurusan-dosen/{jurusan_id}',
           'JurusanController@jurusanDosen')->name('jurusan-dosen');
Route::get('/jurusan-mahasiswa/{jurusan_id}',
           'JurusanController@jurusanMahasiswa')->name('jurusan-mahasiswa');

// Route untuk tombol "Buat Matakuliah" di halaman show dosen
Route::get('/buat-matakuliah/{dosen}',
           'MatakuliahController@buatMatakuliah')->name('buat-matakuliah');

// Route untuk tombol "Ambil Matakuliah" di halaman show mahasiswa
Route::get('/mahasiswas/ambil-matakuliah/{mahasiswa}',
           'MahasiswaController@ambilMatakuliah')->name('ambil-matakuliah');

Route::post('/mahasiswas/ambil-matakuliah/{mahasiswa}',
            'MahasiswaController@prosesAmbilMatakuliah')
            ->name('proses-ambil-matakuliah');

// Route untuk tombol "Daftarkan Mahasiswa" di halaman show matakuliah
Route::get('/matakuliahs/daftarkan-mahasiswa/{matakuliah}',
           'MatakuliahController@daftarkanMahasiswa')
           ->name('daftarkan-mahasiswa');

Route::post('/matakuliahs/daftarkan-mahasiswa/{matakuliah}',
           'MatakuliahController@prosesDaftarkanMahasiswa')
           ->name('proses-daftarkan-mahasiswa');

// Route untuk halaman search / pencarian
Route::get('/pencarian', 'PencarianController@index');
Route::get('/pencarian/proses', 'PencarianController@proses');
